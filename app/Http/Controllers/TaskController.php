<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasksPerPage = 10;
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters('status_id', 'created_by_id', 'assigned_to_id')
            ->paginate($tasksPerPage);
        $users = User::all();
        $taskStatuses = TaskStatus::all();
        session()->flashInput($request->input());
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
        $labels = Label::all();
        return view('task.create', compact('task', 'users', 'taskStatuses', 'labels'));
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
            'labels' => $request->input('labels'),
        ];
        $validator = Validator::make($data, [
            'name' => 'required|unique:App\Models\Task,name',
            'description' => '',
            'status_id' => 'required|exists:App\Models\TaskStatus,id',
            'assigned_to_id' => 'nullable|exists:App\Models\User,id',
            'labels' => 'nullable|array',
            'labels.*' => 'nullable|distinct|exists:App\Models\Label,id',
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

        if (!empty($data['labels'])) {
            $labels = Label::find(array_filter($data['labels']));
            $task->labels()->sync($labels);
        }

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
        $labels = Label::all();

        if (optional($request->user())->cannot('update', $task)) {
            abort(403);
        }
        return view('task.edit', compact('task', 'users', 'taskStatuses', 'labels'));
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
            'labels' => $request->input('labels'),
        ];
        $validator = Validator::make($data, [
            'name' => 'required|unique:App\Models\Task,name,' . $task->id,
            'description' => '',
            'status_id' => 'required|exists:App\Models\TaskStatus,id',
            'assigned_to_id' => 'nullable|exists:App\Models\User,id',
            'labels' => 'nullable|array',
            'labels.*' => 'nullable|distinct|exists:App\Models\Label,id',
        ], trans('validation.custom.task'));

        if ($validator->fails()) {
            return response()
                ->redirectToRoute('task.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        $task->fill($data);
        $task->save();

        if (!empty($data['labels'])) {
            $labels = Label::find(array_filter($data['labels']));
            $task->labels()->sync($labels);
        }

        flash(trans('flash.task.updated'))->success();
        return redirect()
            ->route('task.index');
    }
}
