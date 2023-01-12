<?php

namespace App\Imports;

use App\ChamCong;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class ChamCongImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new ChamCong([
           'ma_nv'     => $row[0],
           'name'    => $row[1],
           'in'    => $row[3],
           'out'    => $row[4],
           'date' =>  date('Y-m-d', strtotime($row[2])),
        ]);
    }
}