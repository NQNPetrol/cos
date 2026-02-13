<?php

namespace App\Livewire;

use App\Models\FlightLog;
use App\Models\MisionFlytbase;
use App\Models\UserCliente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class FlightLogsClientTable extends Component
{
    use WithPagination;

    public $fechaDesde = '';

    public $fechaHasta = '';

    public $droneName = '';

    public $misionNombre = '';

    public $clienteId = '';

    protected $queryString = [
        'fechaDesde' => ['except' => ''],
        'fechaHasta' => ['except' => ''],
        'droneName' => ['except' => ''],
        'misionNombre' => ['except' => ''],
        'clienteId' => ['except' => ''],
    ];

    public function mount()
    {
        $user = Auth::user();
        $userCliente = UserCliente::where('user_id', $user->id)->first();

        if ($userCliente) {
            $this->clienteId = $userCliente->cliente_id;
        }
    }

    public function limpiarFiltros()
    {
        $this->reset(['fechaDesde', 'fechaHasta', 'droneName', 'misionNombre']);
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        // Obtener los IDs de cliente a los que pertenece el usuario
        $userClientes = UserCliente::where('user_id', $user->id)->get();
        $clienteIds = $userClientes->pluck('cliente_id')->toArray();

        if (empty($clienteIds)) {
            return view('livewire.flight-logs-client-table', [
                'flightLogs' => collect([]),
                'misiones' => collect([]),
                'drones' => collect([]),
            ]);
        }

        // Obtener las misiones de los clientes del usuario
        $misionesQuery = MisionFlytbase::whereIn('cliente_id', $clienteIds);

        if ($this->misionNombre) {
            $misionesQuery->where('nombre', 'like', '%'.$this->misionNombre.'%');
        }

        $misionIds = $misionesQuery->pluck('id')->toArray();

        // Query principal de flight logs
        $query = FlightLog::with(['mision', 'piloto'])
            ->whereIn('mision_flytbase_id', $misionIds)
            ->when($this->fechaDesde, function ($query) {
                $query->whereDate('flight_starttime', '>=', Carbon::parse($this->fechaDesde));
            })
            ->when($this->fechaHasta, function ($query) {
                $query->whereDate('flight_starttime', '<=', Carbon::parse($this->fechaHasta));
            })
            ->when($this->droneName, function ($query) {
                $query->where('drone_name', 'like', '%'.$this->droneName.'%');
            })
            ->orderBy('flight_starttime', 'desc');

        $flightLogs = $query->paginate(10);

        // Obtener todas las misiones disponibles para los filtros
        $misionesDisponibles = MisionFlytbase::whereIn('cliente_id', $clienteIds)
            ->with('cliente')
            ->get();

        $dronesDisponibles = FlightLog::whereIn('mision_flytbase_id', $misionIds)
            ->select('drone_name')
            ->distinct()
            ->orderBy('drone_name')
            ->get()
            ->pluck('drone_name')
            ->filter()
            ->values();

        return view('livewire.flight-logs-client-table', [
            'flightLogs' => $flightLogs,
            'misiones' => $misionesDisponibles,
            'drones' => $dronesDisponibles,
        ]);
    }
}
