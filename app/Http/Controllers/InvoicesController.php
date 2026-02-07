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
                'stocks.id as stock_id',
            )
            ->join('stocks', 'stocks.id', '=', 'invoices.stock_id');
        if ($request->filled('invoice_id')) {
            $query->where('invoices.invoice_id', $request->invoice_id);
        }
        if ($request->filled('account_id')) {
            $query->where('invoices.account_id', $request->account_id);
        }
        if ($request->filled('invoice_no')) {
            $query->where('invoices.id', $request->invoice_no);
        }
        if ($request->filled('date')) {
            $query->whereDate('invoices.sold_date', $request->date);
        }

        $invoices = $query
            ->orderBy('invoices.created_at', 'desc')
            ->paginate(500);
            $accounts=DB::table('accounts')->get();
        return view('invoices.index', compact('invoices','accounts'));
    }






    public function view($id)
    {
        if (Gate::allows('is_admin')) {
            $invoice = DB::table('invoices')
                ->join('stocks', 'invoices.stock_id', 'stocks.id')
                ->select('stocks.company_name','stocks.model_name','stocks.health','stocks.activation_status','stocks.pta_status','stocks.imei1','stocks.imei2','stocks.country_status','stocks.ram','stocks.rom','stocks.type','invoices.id','invoices.invoice_id','invoices.buyer_name','invoices.buyer_phone','invoices.backup','invoices.sold_date','invoices.total_bill','invoices.created_at')
                ->where('invoices.id', $id)
                ->first();
            return view('invoices.view', compact('invoice'));
        } else {
            return abort(401);
        }
    }
}
