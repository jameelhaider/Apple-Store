<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Invoices;
use App\Models\Stocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class StocksController extends Controller
{
    public function index(Request $request, $type)
    {
        if (Gate::allows('is_admin')) {
            $query = Stocks::query()
                ->select('company_name', 'id', 'purchase', 'sale', 'imei1', 'imei2', 'model_name', 'type', 'created_at', 'pta_status')
                ->where('type', $type)
                ->where('status', 'Available');
            if ($request->model_name) {
                $query->where('stocks.model_name', $request->model_name);
            }
            if ($request->company_name) {
                $query->where('stocks.company_name', $request->company_name);
            }
            if ($request->imei1) {
                $query->where('stocks.imei1', $request->imei1);
            }

            if ($request->pta_status) {
                $query->where('stocks.pta_status', $request->pta_status);
            }
            $stocks = $query
                ->orderBy('created_at', 'desc')
                ->paginate(1000);
            $accounts = DB::table('accounts')->get();
            return view('stocks.index', compact('stocks', 'accounts'));
        } else {
            return abort(401);
        }
    }

    public function view($id)
    {
        if (Gate::allows('is_admin')) {
            $stock = DB::table('stocks')
                ->where('stocks.id', $id)->first();
            return view('stocks.view', compact('stock'));
        } else {
            return abort(401);
        }
    }




    public function create($type)
    {
        if (Gate::allows('is_admin')) {
            $stock = new Stocks();
            return view("stocks.create", compact('stock'));
        } else {
            return abort(401);
        }
    }


    public function edit($type, $id)
    {
        if (Gate::allows('is_admin')) {
            $stock = Stocks::find($id);
            if (!$stock)
                return redirect()->back();
            return view("stocks.create", compact('stock'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $validated = $request->validate([
                'company_name'           => 'required',
                'model_name'             => 'required',
                'health'       => 'nullable|integer|min:1|max:100',
                'purchase'       => 'required|integer|min:1',
                'sale'       => 'required|integer|min:2',
                'imei1' => [
                    'required',
                    'digits:15',
                ],
                'imei2' => [
                    'nullable',
                    'digits:15',
                ],
                'pta_status'           => 'required|in:Official Approved,Not Approved,Not Approved (4 months remaining),Patch Approved,CPID Approved',
                'ram'                  => 'nullable|string|max:20',
                'rom'                  => 'required|string|max:20',
                'activation_status'         => 'required|in:Active,Non Active',
                'country_status'       => 'required|in:JV,Factory,Yes,No',
            ]);
            $stock = new Stocks();
            $stock->purchase = $request->purchase;
            $stock->sale = $request->sale;
            if ($request->type == 'apple') {
                $stock->model_name = $request->model_name;
            } else {
                $stock->model_name = ucfirst(strtolower($request->model_name));
            }

            $stock->company_name = $request->company_name;
            $stock->rom = $request->rom;
            $stock->ram = $request->ram;
            $stock->health = $request->health;
            $stock->status = 'Available';
            $stock->imei1 = $request->imei1;
            $stock->imei2 = $request->imei2;
            $stock->pta_status = $request->pta_status;
            $stock->activation_status = $request->activation_status;
            $stock->country_status = $request->country_status;
            $stock->type = $request->type;
            $stock->save();
            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Stock Added! You can add another one.');
            }
            return redirect()->route('stock.index', ['type' => $request->type])->with('success', 'Stock Added Successfully!');
        } else {
            return abort(401);
        }
    }


    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $validated = $request->validate([
                'company_name'           => 'required',
                'model_name'             => 'required',
                'health'       => 'nullable|integer|min:1|max:100',
                'purchase'       => 'required|integer|min:1',
                'sale'       => 'required|integer|min:2',
                'imei1' => [
                    'required',
                    'digits:15',
                ],
                'imei2' => [
                    'nullable',
                    'digits:15',
                ],
                'pta_status'           => 'required|in:Official Approved,Not Approved,Not Approved (4 months remaining),Patch Approved,CPID Approved',
                'ram'                  => 'nullable|string|max:20',
                'rom'                  => 'required|string|max:20',
                'activation_status'         => 'required|in:Active,Non Active',
                'country_status'       => 'required|in:JV,Factory,Yes,No',
            ]);


            $stock = Stocks::find($id);
            $stock->purchase = $request->purchase;
            $stock->sale = $request->sale;
            if ($request->type == 'apple') {
                $stock->model_name = $request->model_name;
            } else {
                $stock->model_name = ucfirst(strtolower($request->model_name));
            }
            $stock->company_name = $request->company_name;
            $stock->rom = $request->rom;
            $stock->ram = $request->ram;
            $stock->health = $request->health;
            $stock->imei1 = $request->imei1;
            $stock->imei2 = $request->imei2;
            $stock->pta_status = $request->pta_status;
            $stock->activation_status = $request->activation_status;
            $stock->country_status = $request->country_status;
            $stock->type = $request->type;
            $stock->save();
            return redirect()->route('stock.index', ['type' => $request->type])->with('success', 'Stock updated successfully!');
        } else {
            return abort(401);
        }
    }



    // public function delete($id)
    // {
    //     if (Gate::allows('is_admin')) {
    //         $stock = Stocks::find($id);
    //         if (!is_null($stock)) {
    //             $stock->delete();
    //         }
    //         return redirect()->back()->with('success', 'Stock Deleted Successfully');
    //     } else {
    //         return abort(401);
    //     }
    // }


    public function markassold(Request $request)
    {
        if (!Gate::allows('is_admin')) {
            abort(401, 'Unauthorized action.');
        }
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'sold_out_date' => 'required|date',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'nullable|string|max:20',
            'sale_price' => 'required|numeric|min:0',
        ]);
        $account = DB::table('accounts')->where('id', $request->account_id)->first();
        $stock = Stocks::findOrFail($request->stock_id);
        $profit = $request->sale_price - $stock->purchase;
        $invoice = Invoices::create([
            'invoice_id'  => 'AS' . rand(1000, 9999),
            'stock_id'    => $stock->id,
            'profit'      => $profit,
            'sold_date'   => $request->sold_out_date,
            'total_bill'   => $request->sale_price,
            'buyer_name'  => $request->buyer_name,
            'buyer_phone' => $request->buyer_phone,
            'backup' => $request->backup,
            'account_id' => $request->account_id,
        ]);
        $stock->update(['status' => 'Sold Out']);
        if ($account->id != 1) {
            DB::table('accounts')
                ->where('id', $account->id)
                ->update([
                    'prev_balance' => $account->prev_balance + $request->sale_price
                ]);
        }
        return redirect()->route('invoice.view', ['id' => $invoice->id])->with('success', 'Stock marked as sold successfully.');
    }



    public function returned($id, $invoice_id)
    {
        if (!Gate::allows('is_admin')) {
            abort(401);
        }
        DB::transaction(function () use ($id, $invoice_id) {
            $invoice = Invoices::findOrFail($invoice_id);
            $stock   = Stocks::findOrFail($id);
            $stock->update([
                'status' => 'Available'
            ]);
            if ($invoice->account_id != 1) {
                $account = Accounts::findOrFail($invoice->account_id);

                $account->update([
                    'prev_balance' => $account->prev_balance - $invoice->total_bill
                ]);
            }
            $invoice->delete();
        });

        return redirect()->back()->with(
            'success',
            'Stock marked as returned successfully.'
        );
    }
}
