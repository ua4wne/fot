<?php
namespace App\Http\Controllers\Export;
use App\Models\Firm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FirmsExport implements FromCollection, WithHeadings
{
    use Exportable;
    public function collection()
    {
        return Firm::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Type',
            'Name',
            'Full_name',
            'Group_id',
            'inn',
            'kpp',
            'acc_id',
            'create_at',
            'update_at'
        ];
    }
}