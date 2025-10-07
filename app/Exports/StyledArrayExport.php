<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;

class StyledArrayExport implements FromArray, WithHeadings, WithStyles
{
    protected $array;
    protected $headings;
    public function __construct(array $array, array $headings)
    {
        $this->array = $array;
        $this->headings = $headings;
    }
    public function array(): array
    {
        return $this->array;
    }
    public function headings(): array
    {
        return $this->headings;
    }
    public function styles(Worksheet $sheet)
    {
        // Encabezado azul con texto blanco
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '1565c0'],
            ],
        ]);
        // Filas alternas
        $rowCount = count($this->array) + 1;
        for ($i = 2; $i <= $rowCount; $i++) {
            $color = ($i % 2 == 0) ? 'E3F0FF' : 'FFFBE6';
            $sheet->getStyle('A'.$i.':' . $sheet->getHighestColumn() . $i)->applyFromArray([
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => $color],
                ],
            ]);
        }
        // Bordes suaves
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $rowCount)
            ->getBorders()->getAllBorders()->setBorderStyle('thin')->setColor(new Color('BBB'));
        // Fuente Nunito
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $rowCount)
            ->getFont()->setName('Nunito');
        // Ajuste de ancho
        foreach(range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        return [];
    }
}
