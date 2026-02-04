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
        if (Gate::allows('is_admin')) {
            $query = Invoices::query()
                ->select('invoices.total_bill as total_bill','invoices.customer_name as customer_name', 'invoices.total_items as total_items', 'invoices.id as id', 'invoices.invoice_id as invoice_id', 'invoices.created_at as created_at', 'invoices.profit as profit', 'invoices.invoice_to as invoice_to', 'invoices.account_id as acc_id', 'invoices.status as status');
            if ($request->invoice_id) {
                $query->where('invoices.invoice_id', $request->invoice_id);
            }
            if ($request->invoice_no) {
                $query->where('invoices.id', $request->invoice_no);
            }
            if ($request->account_id) {
                $query->where('invoices.account_id', $request->account_id);
            }
            if ($request->date) {
                $query->whereDate('invoices.created_at', $request->date);
            }
            $invoices = $query
                ->orderBy('invoices.created_at', 'desc')
                ->paginate(500);
            $accounts = DB::table('accounts')
                ->select('customer_name', 'id')
                ->orderBy('id', 'asc')
                ->get();
            return view('invoices.index', compact('invoices', 'accounts'));
        } else {
            return abort(401);
        }
    }

}
