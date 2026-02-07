<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\CashReceived;
use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $query = Accounts::query();

            if ($request->name) {
                $query->where('customer_name', 'LIKE', '%' . $request->name . '%');
            }
            if ($request->phone) {
                $query->where('customer_phone', $request->phone);
            }
            if ($request->status) {
                if ($request->status == 'Clear') {
                    $query->where('prev_balance', 0);
                } elseif ($request->status == 'Remainings') {
                    $query->where('prev_balance', '>', 0);
                } elseif ($request->status == 'Credit') {
                    $query->where('prev_balance', '<', 0);
                }
            }
            if ($request->acc_id) {
                $query->where('id', $request->acc_id);
            }
            $remBalanceQuery = clone $query;
            $totalRem = $remBalanceQuery->where('prev_balance', '>', 0)->sum('prev_balance');
            $accounts = $query->orderBy('id', 'asc')->paginate(100);
            return view('accounts.index', compact('accounts', 'totalRem'));
        } else {
            return abort(401);
        }
    }








    public function create()
    {
        if (Gate::allows('is_admin')) {
            $account = new Accounts();
            return view("accounts.create", compact('account'));
        } else {
            return abort(401);
        }
    }


    public function edit($id)
    {
        if (Gate::allows('is_admin')) {
            $account = Accounts::find($id);
            if (!$account)
                return redirect()->back();
            return view("accounts.create", compact('account'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {

            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20|unique:accounts,customer_phone',
                'customer_address' => 'nullable|string|max:500',
            ]);

            $account = new Accounts();
            $account->customer_name = $request->customer_name;
            $account->customer_phone = $request->customer_phone;
            $account->customer_address = $request->customer_address;
            $account->save();

            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Account Created! You can add another one.');
            }
            return redirect()->route('index.account')->with('success', 'Account Created Successfully!');
        } else {
            return abort(401);
        }
    }




    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {

            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20|unique:accounts,customer_phone,' . $id,
                'customer_address' => 'nullable|string|max:500',
            ]);

            $account = Accounts::findOrFail($id);
            $account->customer_name = $request->customer_name;
            $account->customer_phone = $request->customer_phone;
            $account->customer_address = $request->customer_address;
            $account->update();

            return redirect()->route('index.account')->with('success', 'Account Updated Successfully!');
        } else {
            return abort(401);
        }
    }


    public function ledger($id)
    {

        if (Gate::allows('is_admin')) {
            $account = Accounts::findOrFail($id);
            if ($account->id == 1) {
                return redirect()->back()->with('error', 'Ledger For Counter Sale Not Available');
            }
            $invoices = Invoices::where('account_id', $id)->get();
            $cashReceiveds = CashReceived::where('account_id', $id)->get();
            $ledgerEntries = collect();

            foreach ($invoices as $invoice) {
                $ledgerEntries->push([
                    'date' => $invoice->created_at,
                    'type' => 'Sale Invoice',
                    'id' => $invoice->id,
                    'debit' => $invoice->total_bill,
                    'credit' => 0,
                ]);
            }

            foreach ($cashReceiveds as $cash) {
                $ledgerEntries->push([
                    'date' => $cash->created_at,
                    'type' => 'Cash Received',
                    'id' => $cash->id,
                    'debit' => 0,
                    'credit' => $cash->ammount,
                ]);
            }
            $ledgerEntries = $ledgerEntries->sortBy('date')->values();

            return view('accounts.ledger', compact('account', 'ledgerEntries'));
        } else {
            return abort(401);
        }
    }





   public function details($id)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }

        $account = DB::table('accounts')->where('id', $id)->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Account not found');
        }

        if ($account->id == 1) {
            return redirect()->back()->with('error', 'Details For Counter Sale Not Available');
        }

        $cash = DB::table('cash_receiveds')
            ->where('account_id', $id)
            ->select('id', 'narration', 'ammount', 'customer_name', 'created_at', 'final_rem')
            ->orderBy('created_at', 'desc')
            ->get();

        $saleinvoices = DB::table('invoices')
        ->join('stocks','invoices.stock_id','stocks.id')
            ->where('account_id', $id)
            ->select('invoices.invoice_id', 'invoices.id', 'invoices.buyer_name', 'invoices.buyer_phone', 'invoices.backup', 'invoices.stock_id', 'invoices.sold_date', 'invoices.total_bill','invoices.profit','stocks.company_name','stocks.model_name')
            ->orderBy('invoices.sold_date', 'desc')
            ->get();

        $invoices = Invoices::where('account_id', $id)->get();
        $cashReceiveds = CashReceived::where('account_id', $id)->get();

        $ledgerEntries = collect();

        foreach ($invoices as $invoice) {
            $ledgerEntries->push([
                'date' => $invoice->created_at,
                'type' => 'Invoice',
                'id' => $invoice->id,
                'debit' => $invoice->total_bill,
                'credit' => 0,
            ]);
        }

        foreach ($cashReceiveds as $cashItem) {
            $ledgerEntries->push([
                'date' => $cashItem->created_at,
                'type' => 'Cash Received',
                'id' => $cashItem->id,
                'debit' => 0,
                'credit' => $cashItem->ammount,
            ]);
        }

        $ledgerEntries = $ledgerEntries->sortBy('date')->values();

        return view('accounts.details', compact(
            'account',
            'cash',
            'saleinvoices',
            'ledgerEntries'
        ));
    }


}
