<?php

namespace App\Exports;

use App\Contact;
use Maatwebsite\Excel\Concerns\FromCollection;

class ContacsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Contact::all();
    }
}
