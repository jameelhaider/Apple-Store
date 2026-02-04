<?php

use App\Models\CloseMonths;
use Illuminate\Support\Carbon;
use App\Mail\DatabaseBackupMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\DemandsController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\CashReceivedController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\CloseMonthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/









Route::get('admin/generate-pdf', function () {

    if (Gate::allows('is_admin')) {
        $types = DB::table('types')->where('type', 'parts')->get();
        $accounts = DB::table('accounts')->orderby('created_at', 'asc')->get();
        $partscompanies = DB::table('companies')->where('type', 'parts')->get();
        $toolscompanies = DB::table('companies')->where('type', 'tools')->get();
        return view('generatepdf', compact('types', 'partscompanies', 'toolscompanies', 'accounts'));
    } else {
        return abort(401);
    }
})->name('index.generate.pdf');



Route::get('/send-database-backup', function () {
    $date = Carbon::now()->format('d_M_y_h_i_A');
    $filename = "Backup_Apple_Store_$date.sql";
    $tables = DB::select('SHOW TABLES');
    $tableNames = array_map('current', $tables);
    $sql = '';

    foreach ($tableNames as $table) {
        $createTableQuery = DB::select("SHOW CREATE TABLE `$table`");
        $sql .= $createTableQuery[0]->{'Create Table'} . ";\n\n";
        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            $values = array_map(function ($value) {
                return DB::connection()->getPdo()->quote($value);
            }, (array) $row);

            $sql .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
        }
        $sql .= "\n\n";
    }
    Mail::to(['jameelhaider047@gmail.com', 'rana.awais.9110@gmail.com'])
        ->send(new DatabaseBackupMail($sql, $filename));
    return redirect()->back()->with('success', 'Database backup sent to email successfully.');
})->name('send.database.backup');


Route::get('/start-month', function () {
    $currentMonth = Carbon::now()->format('Y-m');
    $existingEntry = CloseMonths::where('month', $currentMonth)->first();
    if ($existingEntry) {
        return response()->json(['error' => 'Already Started'], 400);
    }
    $closeMonth = new CloseMonths();
    $closeMonth->month = $currentMonth;
    $closeMonth->save();
    return response()->json(['message' => 'Month Start Successfully', 'month' => $currentMonth]);
})->name('cronjob.startmonth');


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect('/admin');
    });
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/admin');
    });


    Route::get('/export-database', function () {
        $date = Carbon::now()->format('d_M_y_h_i_A');
        $filename = "Backup_Apple_Store_$date.sql";
        $tables = DB::select('SHOW TABLES');
        $tableNames = array_map('current', $tables);
        $sql = '';

        foreach ($tableNames as $table) {
            $createTableQuery = DB::select("SHOW CREATE TABLE `$table`");
            $sql .= $createTableQuery[0]->{'Create Table'} . ";\n\n";
            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                $values = array_map(function ($value) {
                    return DB::connection()->getPdo()->quote($value);
                }, (array) $row);

                $sql .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
            }
            $sql .= "\n\n";
        }

        return Response::make($sql, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    })->name('export.database');








    Route::group(['prefix' => 'admin'], function () {
        Route::get('/change-password', [HomeController::class, 'changepassword'])->name("change.password");
        Route::post('/update-password', [HomeController::class, 'updatepassword'])->name("update.password");


        Route::get('/', function () {
            if (Gate::allows('is_admin')) {
                return view('admin');
            } else {
                return abort(401);
            }
        });


    Route::post('/stock/markAsSold', [StocksController::class, 'markassold'])->name('stock.markAsSold');


    Route::get('/invoices', [InvoicesController::class, 'index'])->name('invoices.index');



        //Stocks
        Route::group(['prefix' => 'stocks'], function () {
            //CRUD
            Route::post('/submit', [StocksController::class, 'submit'])->name("submit.stock");
            Route::get('/create/{type}', [StocksController::class, 'create'])->name("create.stock");
            Route::get('/edit/{type}/{id}', [StocksController::class, 'edit'])->name("stock.edit");
            Route::post('/update/{id}', [StocksController::class, 'update'])->name("update.stock");
            // Route::get('/delete/{id}', [StocksController::class, 'delete'])->name("stock.delete");
            Route::get('/{type}', [StocksController::class, 'index'])->name("stock.index");
            // view
            Route::get('/{id}/view', [StocksController::class, 'view'])->name("stock.view");
        });


        //closemonth
        Route::group(['prefix' => 'close-month'], function () {
            //CRUD
            Route::post('/submit', [CloseMonthController::class, 'submit'])->name("submit.closemonth");
            Route::get('/create', [CloseMonthController::class, 'create'])->name("create.closemonth");
            Route::post('/update/{id}', [CloseMonthController::class, 'update'])->name("update.closemonth");
            //index
            Route::get('/', [CloseMonthController::class, 'index'])->name("index.closemonth");
            Route::post('/submit/expence', [ExpensesController::class, 'submit'])->name("expense.submit");
            Route::get('/view/{month_id}', [ExpensesController::class, 'view'])->name("expense.view");
            Route::get('/delete/expense/{id}', [ExpensesController::class, 'delete'])->name("expense.delete");
        });





        Route::get('/sale-history', [AccountsController::class, 'salehistory'])->name("sale.history");
        //profits
        Route::group(['prefix' => 'profit-stats'], function () {
            Route::get('/', [ExpensesController::class, 'stats'])->name("stats.profit");
        });



    });
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
