<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FlightLog;
use App\Models\MisionFlytbase;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FlightLogsAdminTable extends Component
{
    use WithPagination;

    public $fechaDesde = '';
    public $fechaHasta = '';
    public $droneName = '';
    public $misionNombre = '';
    public $clienteId = '';
    public $sortField = 'flight_starttime';
    public $sortDirection = 'desc';

    protected $queryString = [
        'fechaDesde' => ['except' => ''],
        'fechaHasta' => ['except' => ''],
        'droneName' => ['except' => ''],
        'misionNombre' => ['except' => ''],
        'clienteId' => ['except' => ''],
        'sortField' => ['except' => 'flight_starttime'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function limpiarFiltros()
    {
        $this->reset(['fechaDesde', 'fechaHasta', 'droneName', 'misionNombre', 'clienteId']);
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function render()
    {
        // Query principal de flight logs - SIN FILTRO POR CLIENTE
        $query = FlightLog::with(['mision.cliente', 'piloto']);

        // Filtros
        if ($this->fechaDesde) {
            $query->whereDate('flight_starttime', '>=', Carbon::parse($this->fechaDesde));
        }

        if ($this->fechaHasta) {
            $query->whereDate('flight_starttime', '<=', Carbon::parse($this->fechaHasta));
        }

        if ($this->droneName) {
            $query->where('drone_name', 'like', '%' . $this->droneName . '%');
        }

        if ($this->misionNombre) {
            $query->whereHas('mision', function ($q) {
                $q->where('nombre', 'like', '%' . $this->misionNombre . '%');
            });
        }

        if ($this->clienteId) {
            $query->whereHas('mision', function ($q) {
                $q->where('cliente_id', $this->clienteId);
            });
        }

        // Ordenamiento
        if ($this->sortField === 'mision') {
            $query->join('misiones_flytbase', 'flytbase_flight_logs.mision_flytbase_id', '=', 'misiones_flytbase.id')
                  ->select('flytbase_flight_logs.*', 'misiones_flytbase.nombre as mision_nombre')
                  ->orderBy('misiones_flytbase.nombre', $this->sortDirection);
        } elseif ($this->sortField === 'cliente') {
            $query->join('misiones_flytbase', 'flytbase_flight_logs.mision_flytbase_id', '=', 'misiones_flytbase.id')
                  ->join('clientes', 'misiones_flytbase.cliente_id', '=', 'clientes.id')
                  ->select('flytbase_flight_logs.*', 'clientes.nombre as cliente_nombre')
                  ->orderBy('clientes.nombre', $this->sortDirection);
        } elseif ($this->sortField === 'piloto') {
            $query->leftJoin('pilotos_flytbase', 'flytbase_flight_logs.piloto_flytbase_id', '=', 'pilotos_flytbase.id')
                  ->select('flytbase_flight_logs.*', 'pilotos_flytbase.nombre as piloto_nombre')
                  ->orderBy('pilotos_flytbase.nombre', $this->sortDirection);
        } else {
            $query->orderBy('flytbase_flight_logs.' . $this->sortField, $this->sortDirection);
        }

        $flightLogs = $query->paginate(12);

        // Obtener todas las misiones disponibles para los filtros
        $misionesDisponibles = MisionFlytbase::with('cliente')
            ->when($this->misionNombre, function ($query) {
                $query->where('nombre', 'like', '%' . $this->misionNombre . '%');
            })
            ->when($this->clienteId, function ($query) {
                $query->where('cliente_id', $this->clienteId);
            })
            ->orderBy('nombre')
            ->get();
        
        // Obtener todos los drones disponibles
        $dronesDisponibles = FlightLog::select('drone_name')
            ->distinct()
            ->when($this->droneName, function ($query) {
                $query->where('drone_name', 'like', '%' . $this->droneName . '%');
            })
            ->orderBy('drone_name')
            ->get()
            ->pluck('drone_name')
            ->filter()
            ->values();

        // Obtener todos los clientes disponibles
        $clientesDisponibles = Cliente::orderBy('nombre')->get();

        return view('livewire.flight-logs-admin-table', [
            'flightLogs' => $flightLogs,
            'misiones' => $misionesDisponibles,
            'drones' => $dronesDisponibles,
            'clientes' => $clientesDisponibles,
        ]);
    }
}

