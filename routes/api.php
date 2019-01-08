<?php

use App\Task;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Card API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your card. These routes
| are loaded by the ServiceProvider of your card. You're free to add
| as many additional routes to this file as your card may require.
|
*/

// Route::get('/endpoint', function (Request $request) {
//     //
// });

Route::get('/tasks', function (Request $request) {
    $tasks = Task::get();
    $tasks = $tasks->map(function ($data) {
        return [
            $data->id,
            $data->description,
            $data->date_of_commencement->toDateString(),
            $data->date_of_completion->toDateString(),
            null,
            $data->percentage,
            $data->dependencies->pluck('id'),
        ];
    });

    if (\request()->ajax()) {
        return response()->json($tasks->toArray());
    }
});
