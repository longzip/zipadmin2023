<?php

namespace App\Exports;

use App\OrderDelivery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;	
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class GiaoExport implements FromCollection,WithHeadings,WithTitle,ShouldAutoSize,WithStrictNullComparison
{
    private $day;

    public function __construct()
    {
        $this->day  = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
    }
    
    public function collection()
    {
        return OrderDelivery::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nguyên Vật Liệu',
            'Code',
            'Tồn Kho', 
            'Tồn Kho Mới',   
        ];
    }

    public function title(): string
    {
        return 'Ngày ' . $this->day;
    }
}
