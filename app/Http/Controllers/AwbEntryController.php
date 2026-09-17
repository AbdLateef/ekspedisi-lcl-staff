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
            'awb'           => 'required|string|max:255',
        ]);

        $record = CustomerAwb::create([
            'customer_name' => $validated['customer_name'],
            'awb'           => $validated['awb'],
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
                        'awb'           => $record->awb,
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
}
