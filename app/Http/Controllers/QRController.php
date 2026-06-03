<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class QRController extends Controller
{
    public function generateSinglePDF($name)
    {
        $table = \App\Models\Table::where('name', $name)->firstOrFail();
        
        $host = request()->getHost();
        if (in_array($host, ['localhost', '127.0.0.1', '0.0.0.0'])) {
            $host = gethostbyname(gethostname());
        }
        $scanUrl = request()->getScheme() . '://' . $host . (request()->getPort() ? ':' . request()->getPort() : '') . '/scan/' . $table->name;
        
        $qrCode = QrCode::size(200)->generate($scanUrl);
        $tables = collect([$table]);
        return view('admin.tables.print', compact('tables', 'qrCode'));
    }

    public function generateAllPDF()
    {
        $tables = \App\Models\Table::all();
        return view('admin.tables.print', compact('tables'));
    }
}
