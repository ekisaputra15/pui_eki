<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\WaitressHistory;

class TableApiController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        
        return response()->json([
            'success' => true,
            'data' => $tables
        ]);
    }

    public function clearTable(Request $request)
    {
        $request->validate([
            'table_id' => 'required'
        ]);

        $table = Table::where('name', $request->table_id)->first();
        
        if (!$table) {
            return response()->json(['success' => false, 'message' => 'Meja tidak ditemukan.'], 404);
        }

        if ($table->status !== 'dirty') {
            return response()->json(['success' => false, 'message' => 'Meja tidak sedang kotor.'], 400);
        }

        $table->update(['status' => 'clear']);

        // Log to history
        WaitressHistory::create([
            'table_id' => $table->name,
            'waitress_name' => $request->user()->name ?? 'System'
        ]);

        return response()->json([
            'success' => true, 
            'message' => "Meja {$table->name} berhasil dibersihkan!",
            'data' => $table
        ]);
    }

    public function history()
    {
        $history = WaitressHistory::orderBy('created_at', 'desc')->take(20)->get();
        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}
