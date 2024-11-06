use App\Http\Controllers\Api\PriorityEventsController;

Route::get('/priority-events', [PriorityEventsController::class, 'index']); 