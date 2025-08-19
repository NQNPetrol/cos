<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\ReporteGenerado;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function preview(Evento $evento)
    {
        $reportesGenerados = $evento->reportesGenerados()->with('usuario')->get();
        $evento->load(['reportesGenerados.usuario', 'cliente', 'categoria']);
        return view('eventos.preview', [
            'evento' => $evento->load('reportesGenerados.usuario'),
            'reportesGenerados' => $evento->reportesGenerados()
                ->with('usuario')
                ->get()
        ]);
    }

    public function generate(Evento $evento)
    {
        $evento->load('media');
        $pdf = Pdf::loadView('reportes.pdf', compact('evento'));

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('defaultFont', 'sans-serif');

        $filename = "reporte_{$evento->id}_" . now()->format('Ymd_His') . '.pdf';
        $path = "reportes/{$filename}";

        try {

            Storage::disk('public')->put($path, $pdf->output());

            //registro en bd
            $reporte = ReporteGenerado::create([
                'evento_id' => $evento->id,
                'user_id' => auth()->id(),
                'nombre_archivo' => $filename,
                'ruta_archivo' => $path
            ]);

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el reporte:' . $e->getMessage());
        }
    }

    public function view(ReporteGenerado $reporte)
    {
        if (!Storage::disk('public')->exists($reporte->ruta_archivo)) {
            return back()->with('error', 'El archivo no existe');
        }
        
        $filePath = Storage::disk('public')->path($reporte->ruta_archivo);
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $reporte->nombre_archivo . '"'
        ]);
    }

    public function previewIframe(Evento $evento)
    {
        $evento->load('media');
        return view('reportes.template', compact('evento'));
    }

    public function download(ReporteGenerado $reporte)
    {
        if (!Storage::disk('public')->exists($reporte->ruta_archivo)) {
            return back()->with('error', 'El archivo no existe');
        }
        return Storage::disk('public')->download($reporte->ruta_archivo, $reporte->nombre_archivo);
    }
}
