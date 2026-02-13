<?php

namespace App\Livewire\HikCentralImages;

use App\Models\AnprPassingRecord;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ViewEventImage extends Component
{
    public $recordId;

    public $record;

    public $imageData;

    public $hasImage = false;

    public $plateNo;

    public $eventTime;

    public $cameraInfo;

    public $vehicleSpeed;

    public $direction;

    public function mount($recordId = null)
    {
        if ($recordId) {
            $this->loadImage($recordId);
        }
    }

    public function loadImage($recordId)
    {
        Log::info('[VIEW_EVENT_IMAGE] loadImage llamado', ['recordId' => $recordId]);

        $this->recordId = $recordId;
        $this->resetImageState();

        // Buscar el registro con la relación eventImage
        $this->record = AnprPassingRecord::with('eventImage')->find($recordId);

        Log::info('[VIEW_EVENT_IMAGE] Resultado de búsqueda', [
            'record_id' => $recordId,
            'record_encontrado' => ! is_null($this->record),
            'tiene_event_image' => $this->record && $this->record->eventImage ? 'Sí' : 'No',
            'image_base64_no_vacio' => $this->record && $this->record->eventImage && ! empty($this->record->eventImage->image_base64) ? 'Sí' : 'No',
        ]);

        if ($this->record) {
            $this->plateNo = $this->record->plate_no ?? 'N/A';
            $this->eventTime = $this->record->cross_time;

            // Verificar si tiene imagen
            if ($this->record->eventImage && ! empty($this->record->eventImage->image_base64)) {
                $this->imageData = 'data:image/jpeg;base64,'.$this->record->eventImage->image_base64;
                $this->hasImage = true;
                Log::info('[VIEW_EVENT_IMAGE] Imagen cargada exitosamente', [
                    'plate_no' => $this->plateNo,
                    'base64_length' => strlen($this->record->eventImage->image_base64),
                ]);
            } else {
                $this->hasImage = false;
                Log::info('[VIEW_EVENT_IMAGE] No hay imagen disponible', [
                    'plate_no' => $this->plateNo,
                    'razon' => $this->record->eventImage ? 'image_base64 vacío' : 'no tiene eventImage',
                ]);
            }
        } else {
            Log::warning('[VIEW_EVENT_IMAGE] Registro no encontrado', ['record_id' => $recordId]);
            $this->hasImage = false;
            $this->plateNo = 'Registro no encontrado';
        }
    }

    private function resetImageState()
    {
        $this->imageData = null;
        $this->hasImage = false;
        $this->plateNo = null;
        $this->eventTime = null;
        $this->record = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetImageState();
    }

    public function backToList()
    {
        return redirect()->route('anpr.index');
    }

    public function render()
    {
        Log::info('[VIEW_EVENT_IMAGE] Render llamado', [
            'record_id' => $this->recordId,
            'record_cargado' => ! is_null($this->record),
            'tiene_imagen' => $this->hasImage,
            'plate_no' => $this->plateNo,
        ]);

        return view('livewire.hik-central-images.view-event-image')
            ->layout('layouts.app');
    }
}
