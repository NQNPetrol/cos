@extends('layouts.cliente')

@section('title', 'Flight Logs')

@section('content')
<div class="container mx-auto px-4 py-8">
    

    <!-- Componente Livewire -->
    <livewire:flight-logs-client-table />
</div>
@endsection