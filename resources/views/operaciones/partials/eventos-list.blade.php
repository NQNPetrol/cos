<div class="operaciones-eventos-list" id="operaciones-eventos-list">
    <div class="eventos-list-header">
        <h3>Eventos Recientes</h3>
        <button class="refresh-btn" id="refresh-eventos-btn" title="Actualizar">
            <i class="bi bi-arrow-clockwise"></i>
        </button>
    </div>
    
    <div class="eventos-list-content" id="eventos-list-content" data-eventos-loaded="{{ isset($eventos) && $eventos->count() > 0 ? '1' : '0' }}">
        @if(isset($eventos) && $eventos->count() > 0)
            @foreach($eventos as $evento)
                <div class="evento-card" 
                     data-event-id="{{ $evento['id'] }}" 
                     data-lat="{{ $evento['latitud'] ?? '' }}" 
                     data-lng="{{ $evento['longitud'] ?? '' }}">
                    <div class="evento-header">
                        <span class="evento-cliente-tag">{{ $evento['cliente'] }}</span>
                        <span class="evento-estado-badge {{ strtolower(str_replace(' ', '-', $evento['estado'])) }}">
                            {{ $evento['estado'] }}
                        </span>
                    </div>
                    <div class="evento-body">
                        <div class="evento-categoria">
                            <i class="bi bi-tag-fill" style="font-size: 12px; margin-right: 4px;"></i>
                            {{ $evento['categoria'] }}
                        </div>
                        <div class="evento-descripcion">{{ $evento['descripcion'] }}</div>
                        <div class="evento-fecha">
                            <i class="bi bi-clock" style="font-size: 11px; margin-right: 4px;"></i>
                            {{ $evento['fecha_hora_formatted'] }}
                        </div>
                        @if($evento['latitud'] && $evento['longitud'])
                        <div class="evento-ubicacion" style="font-size: 11px; color: var(--fb-text-secondary); margin-top: 4px;">
                            <i class="bi bi-geo-alt-fill" style="font-size: 10px; margin-right: 4px;"></i>
                            Con ubicación
                        </div>
                        @endif
                    </div>
                    <div class="evento-footer">
                        <button class="evento-footer-btn" onclick="event.stopPropagation(); window.open('/eventos/{{ $evento['id'] }}/reporte', '_blank')">
                            Ver Detalles
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="eventos-empty">
                <i class="bi bi-inbox" style="font-size: 48px; color: var(--fb-text-secondary); margin-bottom: 16px;"></i>
                <p style="color: var(--fb-text-secondary);">No hay eventos disponibles</p>
            </div>
        @endif
    </div>
    
    @if(isset($eventos) && $eventos->hasPages())
    <!-- Paginación inicial -->
    <div class="eventos-pagination" id="eventos-pagination">
        <button class="pagination-btn" id="prev-page-btn" {{ $eventos->onFirstPage() ? 'disabled' : '' }}>
            <i class="bi bi-chevron-left"></i> Anterior
        </button>
        <span class="pagination-info" id="pagination-info">
            Página {{ $eventos->currentPage() }} de {{ $eventos->lastPage() }} ({{ $eventos->total() }} total)
        </span>
        <button class="pagination-btn" id="next-page-btn" {{ $eventos->hasMorePages() ? '' : 'disabled' }}>
            Siguiente <i class="bi bi-chevron-right"></i>
        </button>
    </div>
    @endif
    
    <!-- Paginación (oculta inicialmente) -->
    <div class="eventos-pagination" id="eventos-pagination" style="display: none;">
        <button class="pagination-btn" id="prev-page-btn" disabled>
            <i class="bi bi-chevron-left"></i> Anterior
        </button>
        <span class="pagination-info" id="pagination-info"></span>
        <button class="pagination-btn" id="next-page-btn">
            Siguiente <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>

