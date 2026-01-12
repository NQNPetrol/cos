<?php

namespace App\Livewire\ActivityLog;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class Index extends Component
{
    use WithPagination;

    public ?int $userId = null;
    public string $search = '';
    public string $logType = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'logType' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'userId' => ['except' => null, 'as' => 'user'],
    ];

    public function mount(): void
    {
        // Check if user parameter is passed in URL
        if (request()->has('user')) {
            $this->userId = (int) request()->get('user');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingLogType(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'logType', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Activity::query()
            ->with(['causer', 'subject'])
            ->latest();

        // Filter by user if specified
        if ($this->userId) {
            $query->where('causer_id', $this->userId)
                  ->where('causer_type', 'App\Models\User');
        }

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('log_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('causer', function ($subQ) {
                      $subQ->where('name', 'like', '%' . $this->search . '%')
                           ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Log type filter
        if ($this->logType) {
            $query->where('log_name', $this->logType);
        }

        // Date range filter
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $activities = $query->paginate(20);

        // Get distinct log types for filter dropdown
        $logTypes = Activity::distinct()->pluck('log_name')->filter()->values();

        return view('livewire.activity-log.index', [
            'activities' => $activities,
            'logTypes' => $logTypes,
            'isFiltered' => $this->userId !== null,
            'filteredUser' => $this->userId ? \App\Models\User::find($this->userId) : null,
        ])->layout('layouts.app');
    }
}

