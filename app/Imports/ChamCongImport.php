<?php

namespace App\Imports;

use App\ChamCong;
use Maatwebsite\Excel\Concerns\ToModel;

class ChamCongImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function  __construct($pos)
    {
        $this->pos = $pos;
    }

    public function model(array $row)
    {
      // dd($row[2]);
        $date = \Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]));
        ChamCong::where('ma_nv',$row[0])->where('date',$date)->where('name',$row[1])->delete();
        return new ChamCong([
           'ma_nv'     => $row[0],
           'name'    => $row[1],
           'info'    => empty($row[5]) ? null : $row[5],
           'in'    => empty($row[3]) ? null : $row[3],
           'type'    => $this->pos,
           'out'    => empty($row[4]) ? null : $row[4],
           'date' => $date ,
        ]);

    }
}
