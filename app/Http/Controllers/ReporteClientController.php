<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\ReporteGenerado;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReporteClientController extends Controller
{
    public function preview(Evento $evento)
    {
        $reportesGenerados = $evento->reportesGenerados()->with('usuario')->get();
        $evento->load(['reportesGenerados.usuario', 'cliente', 'categoria']);
        return view('eventos.client.preview-client', [
            'evento' => $evento->load('reportesGenerados.usuario'),
            'reportesGenerados' => $evento->reportesGenerados()
                ->with('usuario')
                ->get()
        ]);
    }

    public function generate(Evento $evento)
    {
        try {
            Log::info('Iniciando generación de PDF para evento: ' . $evento->id);
            
            
            $evento->load(['media', 'categoria', 'cliente', 'supervisor', 'creador', 'personas']);
            
            // Log de debug
            Log::info('Media cargados para evento ' . $evento->id . ':', [
                'count' => $evento->media->count(),
                'files' => $evento->media->pluck('file_name')->toArray()
            ]);

            $imagenesBase64 = [];
            foreach ($evento->media as $media) {
                try {
                    $fullPath = Storage::disk('public')->path($media->file_path);
                    
                    if (file_exists($fullPath)) {
                        $imageData = file_get_contents($fullPath);
                        if ($imageData !== false) {
                            $base64 = base64_encode($imageData);
                            $mimeType = $media->file_type ?: 'image/jpeg';
                            $imagenesBase64[] = [
                                'data' => "data:$mimeType;base64,$base64",
                                'name' => $media->file_name,
                                'size' => $media->file_size
                            ];
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Error al procesar imagen: ' . $media->file_name, ['error' => $e->getMessage()]);
                }
            }

            Log::info('Imágenes procesadas para PDF: ' . count($imagenesBase64));
            
            // Configuración específica para DomPDF
            $pdf = Pdf::loadView('reportes.pdf', compact('evento', 'imagenesBase64'));
            
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'chroot' => public_path(),
                'dpi' => 96,
                'defaultPaperSize' => 'a4',
                'fontHeightRatio' => 1.1,
                'isFontSubsettingEnabled' => true,
                'isPhpEnabled' => false,
                'defaultMediaType' => 'screen',
                'isCssFloatEnabled' => true,
                'debugKeepTemp' => false,
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugLayoutPaddingBox' => false
            ]);

            $filename = "reporte_{$evento->id}_" . now()->format('Ymd_His') . '.pdf';
            $path = "reportes/{$filename}";

            // Generar el PDF con mejor manejo de codificación
            $pdfContent = $pdf->output();
            
            if (empty($pdfContent)) {
                throw new \Exception('El contenido del PDF está vacío');
            }
            
            Log::info('PDF generado exitosamente');

            // Asegurar que el directorio existe
            $directory = dirname(Storage::disk('public')->path($path));
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Guardar el archivo con manejo binario explícito
            $saved = Storage::disk('public')->put($path, $pdfContent, 'public');
            
            if (!$saved || !Storage::disk('public')->exists($path)) {
                throw new \Exception('Error al guardar el archivo PDF en: ' . $path);
            }

            // Verificar que el archivo se guardó correctamente
            $savedSize = Storage::disk('public')->size($path);
            Log::info('Archivo guardado correctamente');

            // Registro en base de datos
            $reporte = ReporteGenerado::create([
                'evento_id' => $evento->id,
                'user_id' => auth()->id(),
                'nombre_archivo' => $filename,
                'ruta_archivo' => $path
            ]);

            Log::info('Reporte registrado en BD con ID: ' . $reporte->id);

            // Retornar el PDF directamente para descarga
            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => strlen($pdfContent),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al generar PDF', [
                'evento_id' => $evento->id ?? 'N/A',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    public function view(ReporteGenerado $reporte)
    {
        if (!Storage::disk('public')->exists($reporte->ruta_archivo)) {
            Log::warning('Archivo de reporte no encontrado: ' . $reporte->ruta_archivo);
            return back()->with('error', 'El archivo no existe');
        }
        
        $content = Storage::disk('public')->get($reporte->ruta_archivo);
        
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $reporte->nombre_archivo . '"',
            'Content-Length' => strlen($content),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    public function previewIframe(Evento $evento)
    {
        $evento->load(['media', 'categoria', 'cliente', 'supervisor', 'creador', 'personas']);
        $imagenesBase64 = [];
        foreach ($evento->media as $media) {
            try {
                $fullPath = Storage::disk('public')->path($media->file_path);
                
                if (file_exists($fullPath)) {
                    $imageData = file_get_contents($fullPath);
                    if ($imageData !== false) {
                        $base64 = base64_encode($imageData);
                        $mimeType = $media->file_type ?: 'image/jpeg';
                        $imagenesBase64[] = [
                            'data' => "data:$mimeType;base64,$base64",
                            'name' => $media->file_name,
                            'size' => $media->file_size
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Error al procesar imagen para preview: ' . $media->file_name);
            }
        }
        return view('client.reportes.template', compact('evento', 'imagenesBase64'));
    }

    public function download(ReporteGenerado $reporte)
    {
        if (!Storage::disk('public')->exists($reporte->ruta_archivo)) {
            Log::warning('Archivo de reporte no encontrado para descarga: ' . $reporte->ruta_archivo);
            return back()->with('error', 'El archivo no existe');
        }
       $content = Storage::disk('public')->get($reporte->ruta_archivo);
        
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $reporte->nombre_archivo . '"',
            'Content-Length' => strlen($content),
        ]);
    }
}
