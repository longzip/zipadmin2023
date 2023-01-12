<?php

namespace App\Exports;

use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Lead\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactsExport implements FromCollection, WithHeadings
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
            'STT',
            // 'id',
            'Showroom',
            'Chức danh',
            'Tên',
            'Số điện thoại',
            'Ngày Vào',
            'Thời gian vào',
            'Thời gian ra',
            'Chi Tiết',
            'Ghi Chú',
            'Ngày Tạo',
            'Trạng thái',
            'Sản Phẩm',         
        ];
    }

}
