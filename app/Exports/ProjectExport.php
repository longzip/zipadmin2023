<?php

namespace App\Exports;

use App\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectExport implements FromCollection, WithHeadings
{
    private $contacts;

    public function __construct($contacts)
    {
        $this->contacts = $contacts;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->contacts;
    }

    public function headings(): array
    {
        return [
            'Ngày',
            'Chức danh',
            'Tên',
            'Số điện thoại',
            'Địa chỉ',
            'Tỉnh thành',
            
        ];
    }

}
