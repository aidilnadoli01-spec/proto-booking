<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    // Render Display Antrean View
    public function index()
    {
        return view('display.antrean');
    }

    public function getCurrentQueue()
    {
        $current = Antrean::where('status', 'dipanggil')
            ->orderBy('updated_at', 'desc')
            ->first();
            
        return response()->json($current ? [
            'id' => $current->id,
            'nomor_antrian' => $current->nomor_antrean,
            'status' => $current->status
        ] : null);
    }

    public function getNextQueue()
    {
        $next = Antrean::where('status', 'menunggu')
            ->orderBy('tanggal_periksa', 'asc')
            ->orderBy('nomor_antrean', 'asc')
            ->first();
            
        return response()->json($next ? [
            'id' => $next->id,
            'nomor_antrian' => $next->nomor_antrean,
            'status' => $next->status
        ] : null);
    }

    // Optional endpoints for the TV display test panel, syncing with Antrean
    public function callQueue(Request $request)
    {
        // For testing from TV display panel
        $next = Antrean::where('status', 'menunggu')
            ->orderBy('tanggal_periksa', 'asc')
            ->orderBy('nomor_antrean', 'asc')
            ->first();
        
        if ($next) {
            Antrean::where('status', 'dipanggil')
                ->update(['status' => 'selesai']);
            
            $next->update(['status' => 'dipanggil']);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function completeQueue(Request $request)
    {
        $current = Antrean::where('status', 'dipanggil')
            ->orderBy('updated_at', 'desc')
            ->first();
            
        if ($current) {
            $current->update(['status' => 'selesai']);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function addDummyQueue()
    {
        // Dummy test for real Antrean table is harder due to foreign keys. 
        // We will just do nothing to prevent errors on real DB schema.
        return response()->json(['success' => false, 'message' => 'Gunakan Menu Admin untuk tambah data']);
    }
}
