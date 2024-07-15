<?

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\AbcReportController;
use App\Http\Controllers\TurnoverReportController;
use App\Http\Controllers\ClusterController;

Route::get('/stocks', [StockController::class, 'index']);
Route::get('/abc-reports', [AbcReportController::class, 'index']);
Route::get('/turnover-reports', [TurnoverReportController::class, 'index']);
Route::get('/clusters', [ClusterController::class, 'index']);