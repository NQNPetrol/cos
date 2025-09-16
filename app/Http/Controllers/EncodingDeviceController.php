<?php

namespace App\Http\Controllers;

use App\Models\EncodingDevice;
use App\Services\HikCentralService;
use Illuminate\Http\Request;

class EncodingDeviceController extends Controller
{
    protected HikCentralService $hikCentral;

    public function __construct(HikCentralService $hikCentral)
    {
        $this->hikCentral = $hikCentral;
    }

    /**
     * Importa todos los dispositivos y los guarda en la DB
     */
    public function import()
    {
        try {
            $devices = $this->hikCentral->getEncodingDevices(1, 100);

            foreach ($devices as $device) {
                EncodingDevice::updateOrCreate(
                    ['encode_dev_index_code' => $device['encodeDevIndexCode']],
                    [
                        'name'   => $device['encodeDevName'],
                        'ip'     => $device['encodeDevIp'],
                        'port'   => $device['encodeDevPort'],
                        'status' => $device['status'],
                    ]
                );
            }

            return response()->json(['message' => 'Dispositivos importados correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Lista los dispositivos desde DB
     */
    public function index()
    {
        return EncodingDevice::all();
    }
}
