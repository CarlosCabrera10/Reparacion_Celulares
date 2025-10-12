<?php
require_once __DIR__ . "/../models/ReportesModel.php";
require_once __DIR__ . "/../libs/fpdf.php";



class ReportesController {
    private $model;


    public function __construct() {
        $this->model = new ReportesModel();
    }

    
    
    public function index() {
        // Traer todos los datos para los tres gráficos
        $reparacionesMes = $this->model->reparacionesPorMes();
        $reparacionesTecnico = $this->model->reparacionesPorTecnico();
        $reparacionesMarca = $this->model->reparacionesPorMarca();

        include __DIR__ . "/../views/reportes/index.php";
    }

    public function generarMes() {
        $data = $this->model->reparacionesPorMes();

        // Preparar datos
        $meses = array_column($data, 'mes');
        $pendientes = array_column($data, 'pendientes');
        $enReparacion = array_column($data, 'en_reparacion');
        $listos = array_column($data, 'listos');
        $entregados = array_column($data, 'entregados');

        // Crear PDF con FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Reporte de Reparaciones por Mes', 0, 1, 'C');
        $pdf->Ln(10);

        // Add table with data
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 10, 'Mes', 1);
        $pdf->Cell(30, 10, 'Pendientes', 1);
        $pdf->Cell(30, 10, 'En Reparacion', 1);
        $pdf->Cell(30, 10, 'Listos', 1);
        $pdf->Cell(30, 10, 'Entregados', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        for ($i = 0; $i < count($meses); $i++) {
            $pdf->Cell(30, 10, $meses[$i], 1);
            $pdf->Cell(30, 10, $pendientes[$i], 1);
            $pdf->Cell(30, 10, $enReparacion[$i], 1);
            $pdf->Cell(30, 10, $listos[$i], 1);
            $pdf->Cell(30, 10, $entregados[$i], 1);
            $pdf->Ln();
        }

        $pdf->Ln(10);

        // Draw chart
        $width = 180;
        $height = 100;
        $x = 10;
        $y = $pdf->GetY();
        $allValues = array_merge($pendientes, $enReparacion, $listos, $entregados);
        $max = max($allValues);
        $scale = $height / $max;
        $numMonths = count($meses);
        $barWidth = $width / $numMonths / 4;

        for ($i = 0; $i < $numMonths; $i++) {
            $pdf->SetFillColor(255, 0, 0); // Red for pendientes
            $pdf->Rect($x + $i * $barWidth * 4, $y + $height - $pendientes[$i] * $scale, $barWidth, $pendientes[$i] * $scale, 'F');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 6);
            $pdf->Text($x + $i * $barWidth * 4 + $barWidth/2 - 2, $y + $height - $pendientes[$i] * $scale - 2, $pendientes[$i]);

            $pdf->SetFillColor(0, 255, 0); // Green for en_reparacion
            $pdf->Rect($x + $i * $barWidth * 4 + $barWidth, $y + $height - $enReparacion[$i] * $scale, $barWidth, $enReparacion[$i] * $scale, 'F');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Text($x + $i * $barWidth * 4 + $barWidth + $barWidth/2 - 2, $y + $height - $enReparacion[$i] * $scale - 2, $enReparacion[$i]);

            $pdf->SetFillColor(0, 0, 255); // Blue for listos
            $pdf->Rect($x + $i * $barWidth * 4 + 2 * $barWidth, $y + $height - $listos[$i] * $scale, $barWidth, $listos[$i] * $scale, 'F');
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Text($x + $i * $barWidth * 4 + 2 * $barWidth + $barWidth/2 - 2, $y + $height - $listos[$i] * $scale - 2, $listos[$i]);

            $pdf->SetFillColor(255, 255, 0); // Yellow for entregados
            $pdf->Rect($x + $i * $barWidth * 4 + 3 * $barWidth, $y + $height - $entregados[$i] * $scale, $barWidth, $entregados[$i] * $scale, 'F');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Text($x + $i * $barWidth * 4 + 3 * $barWidth + $barWidth/2 - 2, $y + $height - $entregados[$i] * $scale - 2, $entregados[$i]);

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Text($x + $i * $barWidth * 4, $y + $height + 5, $meses[$i]);
        }

        $pdf->Output('reporte_mes.pdf', 'I');
    }

    public function generarMesExcel() {
        $data = $this->model->reparacionesPorMes();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_mes.csv"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');

        // Encabezados
        fputcsv($output, ['Mes', 'Pendientes', 'En Reparacion', 'Listos', 'Entregados']);

        // Datos
        foreach ($data as $item) {
            fputcsv($output, [$item['mes'], $item['pendientes'], $item['en_reparacion'], $item['listos'], $item['entregados']]);
        }

        fclose($output);
        exit;
    }

    public function generarTecnico() {
    $data = $this->model->reparacionesPorTecnico() ?? [];

    // Si no hay datos, mostramos PDF con mensaje
    if (empty($data)) {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0, 10, utf8_decode('Reporte de Reparaciones por Técnico'), 0, 1, 'C');
        $pdf->Ln(8);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0, 10, utf8_decode('No hay datos para mostrar.'), 0, 1, 'C');
        return $pdf->Output('reporte_tecnico.pdf', 'I');
    }

    // Preparar datos
    $tecnicos = array_column($data, 'tecnico');
    $totales  = array_map('intval', array_column($data, 'total'));

    // Crear PDF con FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, utf8_decode('Reporte de Reparaciones por Técnico'), 0, 1, 'C');
    $pdf->Ln(10);

    // Tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(120, 10, utf8_decode('Técnico'), 1);
    $pdf->Cell(40, 10, 'Total', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    for ($i = 0; $i < count($tecnicos); $i++) {
        $pdf->Cell(120, 10, utf8_decode((string)$tecnicos[$i]), 1);
        $pdf->Cell(40, 10, (string)$totales[$i], 1);
        $pdf->Ln();
    }

    $pdf->Ln(10);

    // Gráfico: cada barra con color distinto
    $width  = 180;
    $height = 100;
    $x      = 10;
    $y      = $pdf->GetY();

    $maxVal = max(1, (int)max($totales)); // evita división por cero
    $scale  = $height / $maxVal;

    $numTecnicos = max(1, count($tecnicos));
    $barWidth    = ($width / $numTecnicos) * 0.85; // un poco de separación
    $gap         = ($width / $numTecnicos) * 0.15; // espacio entre barras

    // Paleta de colores (rotará si hay más técnicos que colores)
    $colors = [
        [255, 99, 132],  // rojo suave
        [54, 162, 235],  // azul
        [255, 206, 86],  // amarillo
        [75, 192, 192],  // turquesa
        [153, 102, 255], // violeta
        [255, 159, 64],  // naranja
        [40, 167, 69],   // verde
        [23, 162, 184],  // teal
        [108, 117, 125], // gris
        [255, 193, 7],   // ámbar
    ];

    for ($i = 0; $i < $numTecnicos; $i++) {
        $c = $colors[$i % count($colors)];
        $pdf->SetFillColor($c[0], $c[1], $c[2]);
        $pdf->SetDrawColor(255, 255, 255); // borde claro para separar visualmente

        $bx = $x + $i * ($barWidth + $gap);
        $bh = max(0, $totales[$i] * $scale);
        $by = $y + $height - $bh;

        // Barra
        $pdf->Rect($bx, $by, $barWidth, $bh, 'FD');

        // Etiqueta (técnico) debajo
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Text($bx, $y + $height + 5, utf8_decode((string)$tecnicos[$i]));

        // Valor encima de la barra
        if ($bh > 6) { // solo si hay altura suficiente
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetTextColor(0, 0, 0);
            $tw = $pdf->GetStringWidth((string)$totales[$i]);
            $pdf->Text($bx + ($barWidth/2) - ($tw/2), $by - 2, (string)$totales[$i]);
        }
    }

    return $pdf->Output('reporte_tecnico.pdf', 'I');
}


    public function generarTecnicoExcel() {
        $data = $this->model->reparacionesPorTecnico();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_tecnico.csv"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');

        // Encabezados
        fputcsv($output, ['Tecnico', 'Total', 'Tiempo Promedio']);

        // Datos
        foreach ($data as $item) {
            fputcsv($output, [$item['tecnico'], $item['total'], $item['tiempo_promedio']]);
        }

        fclose($output);
        exit;
    }

  public function generarMarca() {
    $data    = $this->model->reparacionesPorMarca();
    $marcas  = array_column($data, 'marca');
    $totales = array_map('intval', array_column($data, 'total'));

    $pdf = new class extends FPDF {
        public function sectorPath(float $xc, float $yc, float $r, float $a, float $b, string $style='FD'): void {
            $k = $this->k; $h = $this->h;

      
            $a = deg2rad($a + 90);
            $b = deg2rad($b + 90);
            $d = abs($b - $a); if ($d == 0) return;

            $xc *= $k; $yc = ($h - $yc) * $k; $r *= $k;

            $this->_out(sprintf('%.2F %.2F m', $xc, $yc));
            $this->_out(sprintf('%.2F %.2F l', $xc + $r*cos($a), $yc - $r*sin($a)));

            $n  = ceil($d / (M_PI/2));
            $da = $d / $n;
            $alpha = $da/2;
            $t = 4/3 * tan($alpha/2);

            for ($i=0; $i<$n; $i++) {
                $a1 = $a + $i*$da;
                $a2 = $a1 + $da;
                $cos1=cos($a1); $sin1=sin($a1);
                $cos2=cos($a2); $sin2=sin($a2);

                $x1 = $xc + $r * ($cos1 - $t * $sin1);
                $y1 = $yc - $r * ($sin1 + $t * $cos1);
                $x2 = $xc + $r * ($cos2 + $t * $sin2);
                $y2 = $yc - $r * ($sin2 - $t * $cos2);
                $x3 = $xc + $r *  $cos2;
                $y3 = $yc - $r *  $sin2;

                $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1,$y1,$x2,$y2,$x3,$y3));
            }

            $op = ($style === 'F') ? 'f' : (($style === 'FD' || $style === 'DF') ? 'b' : 's');
            $this->_out(sprintf('%.2F %.2F l %s', $xc, $yc, $op));
        }

        public function simplePie(float $x, float $y, float $r, array $values, array $labels, array $colors): void {
            $sum = array_sum($values); if ($sum <= 0) $sum = 1;

            $cx = $x + $r; $cy = $y + $r;
            $angleStart = 0.0;

            foreach ($values as $i => $val) {
                $angle = ($val / $sum) * 360.0;
                $angleEnd = $angleStart + $angle;

                $c = $colors[$i % count($colors)];
                $this->SetFillColor($c[0], $c[1], $c[2]);
                $this->SetDrawColor(255,255,255);
                $this->sectorPath($cx, $cy, $r, $angleStart, $angleEnd, 'FD');

                // etiqueta interna “% (valor)”
                $mid = deg2rad(($angleStart + $angleEnd)/2.0 - 90);
                $pr  = $r * 0.60;
                $tx  = $cx + $pr * cos($mid);
                $ty  = $cy + $pr * sin($mid);

                $pct = ($val > 0) ? round($val * 100 / $sum) : 0;
                $txt = $pct.'% ('.$val.')';

                $this->SetFont('Arial','B',8.5);
                $luma = 0.2126*$c[0] + 0.7152*$c[1] + 0.0722*$c[2];
                $this->SetTextColor($luma > 180 ? 0 : 255, $luma > 180 ? 0 : 255, $luma > 180 ? 0 : 255);
                $tw = $this->GetStringWidth($txt);
                $this->Text($tx - $tw/2, $ty + 3, $txt);

                $angleStart = $angleEnd;
            }

            // Leyenda a la derecha
            $legendX = $x + 2*$r + 40;
            $legendY = $y + 6;
       

            $this->SetFont('Arial','',9);
            $ly = $legendY;
            foreach ($values as $i => $val) {
                $c = $colors[$i % count($colors)];
                $this->SetFillColor($c[0], $c[1], $c[2]);
                $this->SetDrawColor(20,20,20);
                $this->Rect($legendX, $ly - 4, 5, 5, 'FD');

                $label = $labels[$i] ?? ('Item '.($i+1));
                $txt   = utf8_decode($label).' ('.$val.')';
                $this->SetTextColor(0,0,0);
                $this->Text($legendX + 7, $ly, $txt);
                $ly += 7;
            }
        }
    };

    $pdf->AddPage();
    $pdf->SetTitle('Reporte de Reparaciones por Marca');
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,utf8_decode('Reporte de Reparaciones por Marca'),0,1,'C');
    $pdf->Ln(8);

    if (empty($marcas)) {
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,10,utf8_decode('No hay datos para mostrar.'),0,1,'C');
        return $pdf->Output('reporte_marca.pdf','I');
    }

    $colors = [
        [255, 99, 132],[54,162,235],[255,206,86],
        [75,192,192],[153,102,255],[255,159,64],
        [40,167,69],[23,162,184],[108,117,125],[255,193,7],
    ];

    $x = 20; $y = $pdf->GetY(); $r = 42;


    $pdf->simplePie($x, $y, $r, $totales, $marcas, $colors);


    $pdf->SetY($y + 2*$r + 10);
    $pdf->Ln(2);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(110,7,'Marca',1);
    $pdf->Cell(30,7,'Total',1,1,'R');

    $pdf->SetFont('Arial','',10);
    foreach ($marcas as $i => $m) {
        $pdf->Cell(110,7,utf8_decode($m),1);
        $pdf->Cell(30,7,(int)$totales[$i],1,1,'R');
    }

    return $pdf->Output('reporte_marca.pdf','I');
}

    public function generarMarcaExcel() {
        $data = $this->model->reparacionesPorMarca();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_marca.csv"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');

        // Encabezados
        fputcsv($output, ['Marca', 'Total']);

        // Datos
        foreach ($data as $item) {
            fputcsv($output, [$item['marca'], $item['total']]);
        }

        fclose($output);
        exit;
    }


}
