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
use Spatie\Permission\Models\Role;

class LaporanPHPExport implements FromCollection, WithHeadings,  WithTitle,  WithCustomStartCell, WithEvents
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
        return 'Laporan PHP';
    }

    public function startCell(): string
    {
        return 'A2'; // Mulai dari sel A2
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge cell dari A1 sampai I1
                $event->sheet->mergeCells('A2:L2');
                $event->sheet->mergeCells('A3:L3');
                $event->sheet->mergeCells('A4:L4');
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

                $event->sheet->getStyle('A3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Mengatur tinggi baris agar garis berdempetan
                $event->sheet->getStyle('A4:L4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $event->sheet->getStyle('A6:L6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $event->sheet->getStyle('A6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $event->sheet->getStyle('B6:C6')->applyFromArray([
                    'alignment' => [
                        'wrapText' => true,
                    ],
                ]);

                $event->sheet->getStyle('A')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                foreach (range('B', 'B') as $column) {
                    $event->sheet->getColumnDimension($column)->setWidth(18);  // Sesuaikan lebar sel di sini
                }

                foreach (range('C', 'C') as $column) {
                    $event->sheet->getColumnDimension($column)->setWidth(25);  // Sesuaikan lebar sel di sini
                }

                foreach (range('L', 'L') as $column) {
                    $event->sheet->getColumnDimension($column)->setWidth(28);  // Sesuaikan lebar sel di sini
                }

                foreach (range('A', 'A') as $column) {
                    $event->sheet->getColumnDimension($column)->setWidth(6);  // Sesuaikan lebar sel di sini
                }

                foreach (range('D', 'K') as $column) {
                    $event->sheet->getColumnDimension($column)->setWidth(20);  // Sesuaikan lebar sel di sini
                }

                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();
                $range = 'A6:' . $highestColumn . $highestRow;
                $event->sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'wrapText' => true,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);


                $inspektur =
                    DB::table('inspekturs')
                    ->join('wilayahs', 'inspekturs.wilayah_id', '=', 'wilayahs.id')
                    ->where('inspekturs.wilayah_id', auth()->user()->wilayah_id)
                    ->select(
                        'wilayahs.name as wilayah',
                        'inspekturs.name',
                        'inspekturs.nip',
                        'inspekturs.pangkat_gol',
                    )
                    ->whereNull('inspekturs.deleted_at')
                    ->first();
                if ($inspektur != null) {
                    $penandaTangan = "Muara Bulian, " . date('d F Y') . "\n" . $inspektur->wilayah . "\n\n\n" . $inspektur->name . "\n" . $inspektur->pangkat_gol . "\n NIP: " . $inspektur->nip;
                    $event->sheet->setCellValue('L' . ($event->sheet->getHighestRow() + 3), $penandaTangan);
                    $event->sheet->getStyle('L' . ($event->sheet->getHighestRow()))->getAlignment()->setWrapText(true);
                    $event->sheet->getStyle('L' . ($event->sheet->getHighestRow()))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $event->sheet->getRowDimension($event->sheet->getHighestRow())->setRowHeight(100);
                } else {
                    $penandaTangan = '';
                }
            },
        ];
    }
}
