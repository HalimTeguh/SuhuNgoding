<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TTestingExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item, $index) {

            $student = Student::where('id', $item->student_id)->first();
            
            return [
                'no' => $index + 1,
                'name' => $student->user->name ?? '-',
                'nim' => $student->NIS ?? '-',
                'class_type' => ucfirst($item->class_type),
                'pre_test' => $item->pre_test_score ?? '-',
                'post_test' => $item->post_test_score ?? '-',
            ];
        });
    }

    /**
     * Returns the headings for the exported data.
     *
     * @return array An array of column headings for the export file.
     */

    public function headings(): array
    {
        return [
            'no',
            'name',
            'nim',
            'class_type',
            'pre_test_score',
            'post_test_score',
        ];
    }
}
