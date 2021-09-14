<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        $tasksPerPage = 10;
        $tasks = Task::paginate($tasksPerPage);
        $users = User::all();
        $taskStatuses = TaskStatus::all();
        return view('task.index', compact('tasks', 'users', 'taskStatuses'));
    }

    public function show(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        return view('task.show', compact('task'));
    }

    public function create(Request $request)
    {
        if (optional($request->user())->cannot('create', Task::class)) {
            abort(403);
        }
        $users = User::all();
        $taskStatuses = TaskStatus::all();
        $task = new Task();
        return view('task.create', compact('task', 'users', 'taskStatuses'));
    }

    public function store(Request $request)
    {
        if (optional($request->user())->cannot('create', Task::class)) {
            abort(403);
        }
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status_id' => $request->input('status_id'),
            'assigned_to_id' => $request->input('assigned_to_id'),
        ];
        $validator = Validator::make($data, [
            'name' => 'required|unique:App\Models\Task,name',
            'description' => '',
            'status_id' => 'required|exists:App\Models\TaskStatus,id',
            'assigned_to_id' => 'nullable|exists:App\Models\User,id',
        ], trans('validation.custom.task'));

        if ($validator->fails()) {
            return response()
                ->redirectToRoute('task.create')
                ->withErrors($validator)
                ->withInput();
        }

        $task = new Task();
        $task->fill($data);
        $task->created_by_id = $request->user()->id;
        $task->save();

        flash(trans('flash.task.created'))->success();
        return redirect()
            ->route('task.index');
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        if (optional($request->user())->cannot('delete', $task)) {
            abort(403);
        }
        $task->delete();
        flash(trans('flash.task.deleted'))->success();
        return redirect()->route('task.index');
    }

    public function edit(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $users = User::all();
        $taskStatuses = TaskStatus::all();
        if (optional($request->user())->cannot('update', $task)) {
            abort(403);
        }
        return view('task.edit', compact('task', 'users', 'taskStatuses'));
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        if (optional($request->user())->cannot('update', $task)) {
            abort(403);
        }
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status_id' => $request->input('status_id'),
            'assigned_to_id' => $request->input('assigned_to_id'),
        ];
        $validator = Validator::make($data, [
            'name' => 'required|unique:App\Models\Task,name,' . $task->id,
            'description' => '',
            'status_id' => 'required|exists:App\Models\TaskStatus,id',
            'assigned_to_id' => 'nullable|exists:App\Models\User,id',
        ], trans('validation.custom.task'));

        if ($validator->fails()) {
            return response()
                ->redirectToRoute('task.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        $task->fill($data);
        $task->save();
        flash(trans('flash.task.updated'))->success();
        return redirect()
            ->route('task.index');
    }
}
