<?php

namespace App\Http\Controllers;

use App\Models\Contestant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Imports\UsersImport;
use App\Imports\ContestantsImport;
use App\Mail\VoteCodeEmail;
use App\Models\VoteParticipant;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class AuthController extends Controller
{
    public function LoginStart(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $passGenerate = substr(uniqid(),0,5);
        $password = Hash::make($passGenerate);
        $user = User::where('email', $request->email)->first();

        if($user->update(['password' => $password]))
        {
            Mail::to($request->email)
            ->send(new VoteCodeEmail($user,$passGenerate));
            return response()->json(['message' => 'Enter the Vote Code sent to your email'],201);
        }

        return response()->json(['message' => 'You are not included in this voting'],401);
        
    }
    
    public function LoginFinish(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:4',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
        ]);
    }

    public function contestant()
    {
        Excel::import(new ContestantsImport, request()->file('data_file'));

        return back()->with('success', 'All good!');
    }

    public function fetchAllContestants()
    {
        $contestants = Contestant::all();

        return response()->json(['message' => 'Successfully fetched','contestants' => $contestants],201);
    }

    public function submitVote(Request $request)
    {
        $request->validate([
            'contestant' => 'required|array'
        ]);

        $contestants = $request->input('contestant');

        foreach($contestants as $contestant) {
            $contest = Contestant::where('id',$contestant['id'])->first();

            $contest->update([
                'total_votes' => $contest->total_votes + $contestant['vote']
            ]); 
        }

        VoteParticipant::updateOrCreate([
            'user_id' => $request->user()->id,
        ],[
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Vote complete!! Thank you for participating in the Pool'],201);
    }

    public function user()
    {
        Excel::import(new UsersImport, request()->file('data_file'));

        return back()->with('success', 'All good!');
    }
}
