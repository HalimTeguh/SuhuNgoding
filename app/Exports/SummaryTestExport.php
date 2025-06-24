<?php

namespace App\Exports;

use App\Models\TTesting;
use Maatwebsite\Excel\Concerns\FromCollection;

class SummaryTestExport implements FromCollection
{
    protected $data;
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'NIM',
            'Tipe Kelas',
            'Pre-Test',
            'Progress Modul',
            'Post-Test'
        ];
    }
}
