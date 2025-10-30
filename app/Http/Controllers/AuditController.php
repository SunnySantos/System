<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        $logs = Audit::search($request)->with('auditable')->paginate(10)->withQueryString();

        return view('audit.index', compact('logs'));
    }
}
