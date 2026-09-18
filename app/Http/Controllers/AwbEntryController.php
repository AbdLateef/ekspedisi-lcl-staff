<?php

namespace App\Http\Controllers;

use App\Models\CustomerAwb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AwbEntryController extends Controller
{
    /**
     * Show the main mobile entry form and recent entries.
     */
    public function index()
    {
        $recentEntries = CustomerAwb::where('created_by', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return view('awb.index', compact('recentEntries'));
    }

    /**
     * Store a newly created AWB entry and trigger n8n webhook.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'no_container'  => 'nullable|string|max:255',
            'awb'           => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'jumlah_coli'   => 'nullable|integer|min:1',
            'panjang'       => 'nullable|numeric|min:0',
            'lebar'         => 'nullable|numeric|min:0',
            'tinggi'        => 'nullable|numeric|min:0',
            'berat'         => 'nullable|numeric|min:0',
        ]);

        $record = CustomerAwb::create([
            'customer_name' => $validated['customer_name'],
            'no_container'  => $validated['no_container'] ?? null,
            'awb'           => $validated['awb'],
            'deskripsi'     => $validated['deskripsi'] ?? null,
            'jumlah_coli'   => $validated['jumlah_coli'] ?? null,
            'panjang'       => $validated['panjang'] ?? null,
            'lebar'         => $validated['lebar'] ?? null,
            'tinggi'        => $validated['tinggi'] ?? null,
            'berat'         => $validated['berat'] ?? null,
            'created_by'    => auth()->id(),
        ]);

        // Trigger n8n webhook if configured
        $webhookUrl = config('services.n8n.webhook_url');
        if (!empty($webhookUrl)) {
            try {
                Http::timeout(5)->post($webhookUrl, [
                    'event' => 'customer_awb.created',
                    'data'  => [
                        'id'            => $record->id,
                        'customer_name' => $record->customer_name,
                        'no_container'  => $record->no_container,
                        'awb'           => $record->awb,
                        'deskripsi'     => $record->deskripsi,
                        'jumlah_coli'   => $record->jumlah_coli,
                        'panjang'       => $record->panjang,
                        'lebar'         => $record->lebar,
                        'tinggi'        => $record->tinggi,
                        'berat'         => $record->berat,
                        'created_by'    => auth()->user()->name ?? auth()->user()->email,
                        'created_at'    => $record->created_at->toIso8601String(),
                    ],
                ]);
            } catch (\Throwable $e) {
                Log::error('n8n Webhook dispatch failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('awb.index')->with('success', 'Data AWB berhasil disimpan!');
    }

    /**
     * Search customers via external API (ekspedisi-lcl).
     */
    public function searchCustomers(Request $request)
    {
        $apiUrl = config('services.ekspedisi_lcl.url') . '/customers';
        $apiKey = config('services.ekspedisi_lcl.key');

        if (empty($apiUrl) || empty($apiKey)) {
            return response()->json(['status' => 'error', 'data' => []]);
        }

        try {
            $response = Http::timeout(4)
                ->withHeaders(['X-API-Key' => $apiKey])
                ->get($apiUrl, [
                    'q'     => $request->query('q'),
                    'limit' => 50,
                ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }
        } catch (\Throwable $e) {
            Log::error('Customer API search failed: ' . $e->getMessage());
        }

        return response()->json(['status' => 'error', 'data' => []]);
    }

    /**
     * Search SEA container numbers via external API (ekspedisi-lcl).
     */
    public function searchContainers(Request $request)
    {
        $apiUrl = config('services.ekspedisi_lcl.url') . '/containers';
        $apiKey = config('services.ekspedisi_lcl.key');

        if (empty($apiUrl) || empty($apiKey)) {
            return response()->json(['status' => 'error', 'data' => []]);
        }

        try {
            $response = Http::timeout(4)
                ->withHeaders(['X-API-Key' => $apiKey])
                ->get($apiUrl, [
                    'q'     => $request->query('q'),
                    'limit' => 50,
                ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }
        } catch (\Throwable $e) {
            Log::error('Container API search failed: ' . $e->getMessage());
        }

        return response()->json(['status' => 'error', 'data' => []]);
    }
}
