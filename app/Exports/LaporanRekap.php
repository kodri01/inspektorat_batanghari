<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use Spatie\Permission\Models\Role;

class LaporanRekap implements FromCollection, WithHeadings,  WithTitle, WithCustomStartCell, WithEvents
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // return $this->data;

        return collect(array_slice($this->data, 1));
    }

    public function headings(): array
    {
        return $this->data[0];
    }

    public function title(): string
    {
        return 'Laporan Rekap LHP';
    }

    public function startCell(): string
    {
        return 'A2'; // Mulai dari sel A2
    }

    public function registerEvents(): array
    {
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();

        // Pastikan $modelrole tidak null sebelum menggunakan first()
        if ($modelrole) {
            $role = Role::where('id', $modelrole->role_id)->first();
            // Pastikan $role tidak null sebelum menggunakan first()
            if ($role && $role->name == 'superadmin') {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Merge cell dari A1 sampai I1
                        $event->sheet->mergeCells('A2:K2');
                        // $event->sheet->mergeCells('A6:D6');

                        // Mengatur font size, tipe bold, dan alignment center untuk kalimat "Laporan Data Atlet KONI Provinsi Jambi Tahun 2023"
                        $event->sheet->getStyle('A2')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 14,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);


                        $event->sheet->getStyle('A4')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 12,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                        ]);

                        $event->sheet->getStyle('B4:K4')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 12,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                                'wrapText' => true,
                            ],
                        ]);

                        foreach (range('B', 'K') as $column) {
                            $event->sheet->getColumnDimension($column)->setWidth(18);  // Sesuaikan lebar sel di sini
                        }
                        foreach (range('A', 'A') as $column) {
                            $event->sheet->getColumnDimension($column)->setWidth(6);  // Sesuaikan lebar sel di sini
                        }


                        $highestRow = $event->sheet->getHighestRow();
                        $highestColumn = $event->sheet->getHighestColumn();
                        $range = 'A4:' . $highestColumn . $highestRow;
                        $event->sheet->getStyle($range)->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '000000'],
                                ],
                            ],
                        ]);
                    },
                ];
            } else {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Merge cell dari A1 sampai I1
                        $event->sheet->mergeCells('A2:J2');
                        // $event->sheet->mergeCells('A6:D6');

                        // Mengatur font size, tipe bold, dan alignment center untuk kalimat "Laporan Data Atlet KONI Provinsi Jambi Tahun 2023"
                        $event->sheet->getStyle('A2')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 14,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);


                        $event->sheet->getStyle('A4')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 12,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                        ]);

                        $event->sheet->getStyle('B4:J4')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 12,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                                'wrapText' => true,
                            ],
                        ]);

                        foreach (range('B', 'J') as $column) {
                            $event->sheet->getColumnDimension($column)->setWidth(18);  // Sesuaikan lebar sel di sini
                        }
                        foreach (range('A', 'A') as $column) {
                            $event->sheet->getColumnDimension($column)->setWidth(6);  // Sesuaikan lebar sel di sini
                        }


                        $highestRow = $event->sheet->getHighestRow();
                        $highestColumn = $event->sheet->getHighestColumn();
                        $range = 'A4:' . $highestColumn . $highestRow;
                        $event->sheet->getStyle($range)->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '000000'],
                                ],
                            ],
                        ]);
                    },
                ];
            }
        }
    }
}
