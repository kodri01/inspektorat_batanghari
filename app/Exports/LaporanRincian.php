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

class LaporanRincian implements FromCollection, WithHeadings,  WithTitle, ShouldAutoSize, WithCustomStartCell, WithEvents
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
        return 'Laporan Rincian';
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
                        $event->sheet->mergeCells('A2:I2');
                        $event->sheet->mergeCells('A3:I3');

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



                        $event->sheet->getStyle('A5:I5')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 12,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);

                        $highestRow = $event->sheet->getHighestRow();
                        $highestColumn = $event->sheet->getHighestColumn();
                        $range = 'A5:' . $highestColumn . $highestRow;
                        $event->sheet->getStyle($range)->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '000000'],
                                ],
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
                            $event->sheet->setCellValue('H' . ($event->sheet->getHighestRow() + 3), $penandaTangan);
                            $event->sheet->getStyle('H' . ($event->sheet->getHighestRow()))->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('H' . ($event->sheet->getHighestRow()))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getRowDimension($event->sheet->getHighestRow())->setRowHeight(100);
                        } else {
                            $penandaTangan = "Muara Bulian, " . date('d F Y') . "\n" . "INSPEKTUR" . "\n\n\n" . "Muhammad Rokim, SE,CGCAE" . "\n Pembina TK.1 " . "\n" . "NIP: 197104091995031003";
                            $event->sheet->setCellValue('I' . ($event->sheet->getHighestRow() + 3), $penandaTangan);
                            $event->sheet->getStyle('I' . ($event->sheet->getHighestRow()))->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('I' . ($event->sheet->getHighestRow()))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getRowDimension($event->sheet->getHighestRow())->setRowHeight(100);
                        }
                    },
                ];
            } else {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Merge cell dari A1 sampai I1
                        $event->sheet->mergeCells('A2:H2');
                        $event->sheet->mergeCells('A3:H3');
                        $event->sheet->mergeCells('A4:H4');
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
                        $event->sheet->getStyle('A4:H4')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 12,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);

                        $event->sheet->getStyle('A6:H6')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 12,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);

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
                            $event->sheet->setCellValue('H' . ($event->sheet->getHighestRow() + 3), $penandaTangan);
                            $event->sheet->getStyle('H' . ($event->sheet->getHighestRow()))->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('H' . ($event->sheet->getHighestRow()))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getRowDimension($event->sheet->getHighestRow())->setRowHeight(100);
                        } else {
                            $penandaTangan = '';
                        }
                    },
                ];
            }
        }
    }
}