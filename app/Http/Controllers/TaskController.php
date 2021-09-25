<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    public function index(Request $request): View|Factory
    {
        $tasksPerPage = 10;
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters(
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id'),
            )
            ->paginate($tasksPerPage);
        $users = User::all();
        $taskStatuses = TaskStatus::all();
        session()->flashInput($request->input());
        return view('task.index', compact('tasks', 'users', 'taskStatuses'));
    }

    public function show(Request $request, int|string $id): View|Factory
    {
        $task = Task::findOrFail($id); // @phpstan-ignore-line
        return view('task.show', compact('task'));
    }

    public function create(Request $request): View|Factory
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

    public function store(Request $request): RedirectResponse
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
        ], trans('validation.custom.task')); // @phpstan-ignore-line

        if ($validator->fails()) {
            // @phpstan-ignore-next-line
            return response()
                ->redirectToRoute('task.create')
                ->withErrors($validator)
                ->withInput();
        }

        $task = new Task();
        $task->fill($data);
        $task->created_by_id = $request->user()->id; // @phpstan-ignore-line
        $task->save();

        if (!empty($data['labels'])) { // @phpstan-ignore-line
            $labels = Label::find(array_filter($data['labels'])); // @phpstan-ignore-line
            $task->labels()->sync($labels); // @phpstan-ignore-line
        }

        flash(trans('flash.task.created'))->success(); // @phpstan-ignore-line
        // @phpstan-ignore-next-line
        return redirect()
            ->route('task.index');
    }

    public function destroy(Request $request, int|string $id): RedirectResponse
    {
        $task = Task::findOrFail($id); // @phpstan-ignore-line
        if (optional($request->user())->cannot('delete', $task)) {
            abort(403);
        }
        $task->delete();
        flash(trans('flash.task.deleted'))->success(); // @phpstan-ignore-line
        return redirect()->route('task.index'); // @phpstan-ignore-line
    }

    public function edit(Request $request, int|string $id): View|Factory
    {
        $task = Task::findOrFail($id); // @phpstan-ignore-line
        $users = User::all();
        $taskStatuses = TaskStatus::all();
        $labels = Label::all();

        if (optional($request->user())->cannot('update', $task)) {
            abort(403);
        }
        return view('task.edit', compact('task', 'users', 'taskStatuses', 'labels'));
    }

    public function update(Request $request, int|string $id): RedirectResponse
    {
        $task = Task::findOrFail($id); // @phpstan-ignore-line
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
        ], trans('validation.custom.task')); // @phpstan-ignore-line

        if ($validator->fails()) {
            // @phpstan-ignore-next-line
            return response()
                ->redirectToRoute('task.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        $task->fill($data);
        $task->save();

        if (!empty($data['labels'])) { // @phpstan-ignore-line
            $labels = Label::find(array_filter($data['labels'])); // @phpstan-ignore-line
            $task->labels()->sync($labels); // @phpstan-ignore-line
        }

        flash(trans('flash.task.updated'))->success(); // @phpstan-ignore-line
        // @phpstan-ignore-next-line
        return redirect()
            ->route('task.index');
    }
}
