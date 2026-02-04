<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class InvoicesController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('is_admin')) {
            abort(401, 'Unauthorized action.');
        }
        $query = Invoices::query()
            ->select(
                'invoices.*',
                'stocks.company_name',
                'stocks.model_name',
            )
            ->join('stocks', 'stocks.id', '=', 'invoices.stock_id');
        if ($request->filled('invoice_id')) {
            $query->where('invoices.invoice_id', $request->invoice_id);
        }
        if ($request->filled('invoice_no')) {
            $query->where('invoices.id', $request->invoice_no);
        }
        if ($request->filled('date')) {
            $query->whereDate('invoices.created_at', $request->date);
        }

        $invoices = $query
            ->orderBy('invoices.created_at', 'desc')
            ->paginate(500);

        return view('invoices.index', compact('invoices'));
    }
}
