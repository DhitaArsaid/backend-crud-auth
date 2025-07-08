<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        Log::debug('TodoController@index accessed by user ID: ' . optional($request->user())->id);
        return $request->user()->todos()->get();
    }

    public function store(Request $request)
    {
        Log::debug('TodoController@store accessed');

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $todo = $request->user()->todos()->create($validated);

        Log::info('Todo created', ['todo' => $todo]);

        return response()->json([
            'message' => 'Todo created successfully.',
            'data' => $todo,
        ], 201);
    }

    public function show(Request $request, Todo $todo)
    {
        Log::debug('TodoController@show accessed', ['todo_id' => $todo->id]);

        if ($request->user()->id !== $todo->user_id) {
            Log::warning('Unauthorized access attempt to show todo', ['user_id' => $request->user()->id]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $todo;
    }

    public function update(Request $request, Todo $todo)
    {
        Log::debug('TodoController@update accessed', ['todo_id' => $todo->id]);

        if ($request->user()->id !== $todo->user_id) {
            Log::warning('Unauthorized update attempt', ['user_id' => $request->user()->id]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $todo->update($validated);

        Log::info('Todo updated', ['todo' => $todo]);

        return response()->json([
            'message' => 'Todo updated successfully.',
            'data' => $todo,
        ], 200);
    }

    public function destroy(Request $request, Todo $todo)
    {
        Log::debug('TodoController@destroy accessed', ['todo_id' => $todo->id]);

        if ($request->user()->id !== $todo->user_id) {
            Log::warning('Unauthorized delete attempt', ['user_id' => $request->user()->id]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $todo->delete();

        Log::info('Todo deleted', ['todo_id' => $todo->id]);

        return response()->json(null, 204);
    }
}
