@extends('layouts.cliente')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-gray-900 to-slate-800">
        <div class="bg-slate-800 border-b border-slate-700 sticky top-0 z-10">
            <div class="container mx-auto px-6 py-4">
                <nav class="flex items-center space-x-2 text-sm text-slate-300 mb-2">
                    <a href="{{ route('client.eventos.index') }}" class="hover:text-blue-400 transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        Eventos
                    </a>
                    <span class="text-slate-500">/</span>
                    <span class="text-slate-400 font-medium">Vista Previa del Reporte del Evento #{{ $evento->id }}</span>
                </nav>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-1">Vista Previa del Evento</h1>
                        <p class="text-slate-400 mt-1">Revisa en la vista previa que todos los datos del evento sean correctos antes de generar un reporte.
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-900/30 text-blue-300 border border-blue-700/50">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            {{ $evento->fecha_hora ? $evento->fecha_hora->format('d/m/Y H:i') : 'Sin fecha' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <div class="xl:col-span-2">
                    <div class="bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-700 to-indigo-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                 <h2 class="text-xl font-semibold text-white flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Previsualización
                                </h2>
                                <div class="flex items-center space-x-2 text-blue-200 text-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Generado: {{ now()->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="p-8 bg-slate-700/50">
                            <div class="bg-slate-800 rounded-lg shadow-inner border border-slate-600 p-6 h-[1122px] overflow-y-auto scrollbar-dark" style="height: 1122px;">
                                <iframe 
                                    src="{{ route('eventos.reporte.preview-iframe', $evento) }}" 
                                    class="w-full border-none rounded"
                                    onload="this.style.height = this.contentWindow.document.body.scrollHeight + 'px';" style="background: #1e293b;">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-1">
                    <div class="bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 mb-6 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Generar Reporte
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-slate-400 text-sm mb-4">
                                Genera un reporte PDF con todos los detalles del evento para compartir o archivar.
                            </p>
                            <form action="{{ route('client.eventos.reporte.generate', $evento) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center group">
                                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M7 13h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v4a2 2 0 002 2z"/>
                                    </svg>
                                    Descargar PDF
                                </button>
                            </form>
                            @if($reportesGenerados->count())
                                <div class="mt-4 p-3 bg-blue-900/30 rounded-lg border border-blue-700/50">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-blue-300 text-sm font-medium">
                                            {{ $reportesGenerados->count() }} reporte(s) generado(s)
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($reportesGenerados->count())
                    <div class="bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Historial de Reportes
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-slate-400 text-sm mb-4">
                                Versiones anteriores de reportes generados para este evento.
                            </p>
                            <div class="space-y-3 max-h-96 overflow-y-auto scrollbar-dark">
                                @foreach($reportesGenerados->sortByDesc('created_at') as $index => $reporte)
                                <div class="group border border-slate-700 rounded-lg p-4 hover:border-blue-600 hover:bg-slate-700/50 transition-all duration-200">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-1">
                                                @if($index === 0)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-900/30 text-emerald-300 border border-emerald-700/50 mr-2">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Más reciente
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm font-medium text-slate-200">
                                                {{ $reporte->created_at->format('d/m/Y H:i') }}
                                            </p>
                                            <p class="text-xs text-slate-400 mt-1">
                                                Por {{ $reporte->usuario->name ?? 'Usuario desconocido' }}
                                            </p>
                                        </div>
                                        <a href="{{ route('client.reportes.view', $reporte) }}" target="_blank"
                                           class="inline-flex items-center px-3 py-1 bg-blue-900/30 text-blue-300 rounded-lg hover:bg-blue-800/50 transition-colors text-sm font-medium group-hover:scale-105 transform duration-200 border border-blue-700/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                            </svg>
                                            Abrir
                                        </a>
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $reporte->nombre_archivo }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 overflow-hidden">
                        <div class="p-6 text-center">
                            <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-slate-200 mb-2">Sin reportes generados</h3>
                            <p class="text-slate-400 text-sm">
                                Aún no se han generado reportes para este evento. Usa el botón "Generar PDF" para crear el primer reporte.
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Toast notifications -->
    @if(session('success'))
    <div id="toast-success" class="fixed bottom-4 right-4 flex items-center w-full max-w-xs p-4 mb-4 text-emerald-400 bg-slate-800 rounded-lg shadow-2xl z-50 border border-slate-700" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-400 bg-emerald-900/30 rounded-lg border border-emerald-700/50">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3 text-sm font-normal text-slate-200">{{ session('success') }}</div>
        <button type="button" onclick="document.getElementById('toast-success').remove()" class="ml-auto -mx-1.5 -my-1.5 bg-slate-700 text-slate-400 hover:text-slate-200 rounded-lg focus:ring-2 focus:ring-slate-600 p-1.5 hover:bg-slate-600 inline-flex h-8 w-8">
            <span class="sr-only">Cerrar</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if (toast) toast.remove();
        }, 5000);
    </script>
    @endif
    <style>
        /* Estilos personalizados para la barra de scroll */
        .scrollbar-dark::-webkit-scrollbar {
            width: 8px;
        }
        
        .scrollbar-dark::-webkit-scrollbar-track {
            background: #334155; /* slate-700 */
            border-radius: 4px;
        }
        
        .scrollbar-dark::-webkit-scrollbar-thumb {
            background: #475569; /* slate-600 */
            border-radius: 4px;
        }
        
        .scrollbar-dark::-webkit-scrollbar-thumb:hover {
            background: #64748b; /* slate-500 */
        }
        
        /* Para Firefox */
        .scrollbar-dark {
            scrollbar-width: thin;
            scrollbar-color: #475569 #334155;
        }
    </style>
@endsection