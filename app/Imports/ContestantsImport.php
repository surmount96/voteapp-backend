<?php

namespace App\Imports;

use App\Models\Contestant;
use Maatwebsite\Excel\Concerns\ToModel;

class ContestantsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Contestant([
            'name'     => $row[0],
            'position'    => $row[1], 
        ]);
    }
}
