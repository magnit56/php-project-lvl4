<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TaskStatusController extends Controller
{
    public function index(): View|Factory
    {
        $statusesPerPage = 10;
        $taskStatuses = TaskStatus::paginate($statusesPerPage); // @phpstan-ignore-line
        return view('taskStatus.index', compact('taskStatuses'));
    }

    public function show(Request $request, int|string $id): View|Factory
    {
        $taskStatus = TaskStatus::findOrFail($id); // @phpstan-ignore-line
        if (optional($request->user())->cannot('view', $taskStatus)) {
            abort(403);
        }

        return view('taskStatus.show', compact('taskStatus'));
    }

    public function create(Request $request): View|Factory
    {
        if (optional($request->user())->cannot('create', TaskStatus::class)) {
            abort(403);
        }
        $taskStatus = new TaskStatus();
        return view('taskStatus.create', compact('taskStatus'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (optional($request->user())->cannot('create', TaskStatus::class)) {
            abort(403);
        }

        $data = ['name' => $request->input('name')];
        $validator = Validator::make($data, [
            'name' => 'required|unique:App\Models\TaskStatus',
        ], trans('validation.custom.status')); // @phpstan-ignore-line

        if ($validator->fails()) {
            // @phpstan-ignore-next-line
            return response()
                ->redirectToRoute('taskStatus.create')
                ->withErrors($validator)
                ->withInput();
        }

        $taskStatus = new TaskStatus();
        $taskStatus->fill($data);
        $taskStatus->save();

        flash(trans('flash.status.created'))->success(); // @phpstan-ignore-line
        // @phpstan-ignore-next-line
        return redirect()
            ->route('taskStatus.index');
    }

    public function destroy(Request $request, int|string $id): RedirectResponse
    {
        $taskStatus = TaskStatus::findOrFail($id); // @phpstan-ignore-line
        if (optional($request->user())->cannot('delete', $taskStatus)) {
            abort(403);
        }
        if ($taskStatus->tasks->count() > 0) {
            flash(trans('flash.status.delete_failed'))->error(); // @phpstan-ignore-line
            return redirect()->route('taskStatus.index'); // @phpstan-ignore-line
        }
        $taskStatus->delete();
        flash(trans('flash.status.deleted'))->success(); // @phpstan-ignore-line
        return redirect()->route('taskStatus.index'); // @phpstan-ignore-line
    }

    public function edit(Request $request, int|string $id): View|Factory
    {
        $taskStatus = TaskStatus::findOrFail($id); // @phpstan-ignore-line
        if (optional($request->user())->cannot('update', $taskStatus)) {
            abort(403);
        }

        return view('taskStatus.edit', compact('taskStatus'));
    }

    public function update(Request $request, int|string $id): RedirectResponse
    {
        $taskStatus = TaskStatus::findOrFail($id); // @phpstan-ignore-line
        if (optional($request->user())->cannot('update', $taskStatus)) {
            abort(403);
        }

        $data = ['name' => $request->input('name')];

        $validator = Validator::make($data, [
            'name' => 'required|unique:App\Models\TaskStatus,name,' . $taskStatus->id
        ], trans('validation.custom.status')); // @phpstan-ignore-line

        if ($validator->fails()) {
            // @phpstan-ignore-next-line
            return response()
                ->redirectToRoute('taskStatus.edit', $taskStatus->id)
                ->withErrors($validator)
                ->withInput();
        }

        $taskStatus->fill($data);
        $taskStatus->save();
        flash(trans('flash.status.updated'))->success(); // @phpstan-ignore-line
        // @phpstan-ignore-next-line
        return redirect()
            ->route('taskStatus.index');
    }
}
