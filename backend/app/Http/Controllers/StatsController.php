<?php

namespace App\Http\Controllers;

use App\Services\StatsService;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __construct(private StatsService $stats) {}

    public function admin()
    {
        return response()->json($this->stats->admin());
    }

    public function client(Request $request)
    {
        return response()->json($this->stats->client($request->user()));
    }

    public function evolution(Request $request)
    {
        $request->validate([
            'debut' => 'nullable|date',
            'fin'   => 'nullable|date|after_or_equal:debut',
        ]);

        return response()->json($this->stats->evolution($request->debut, $request->fin));
    }
}