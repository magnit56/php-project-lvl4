<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskStatusController extends Controller
{
    public function index()
    {
        $statusesPerPage = 10;
        $taskStatuses = TaskStatus::paginate($statusesPerPage);
        return view('taskStatus.index', compact('taskStatuses'));
    }

    public function show(Request $request, $id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        if (optional($request->user())->cannot('view', $taskStatus)) {
            abort(403);
        }

        return view('taskStatus.show', compact('taskStatus'));
    }

    public function create(Request $request)
    {
        if (optional($request->user())->cannot('create', TaskStatus::class)) {
            abort(403);
        }
        $taskStatus = new TaskStatus();
        return view('taskStatus.create', compact('taskStatus'));
    }

    public function store(Request $request)
    {
        if (optional($request->user())->cannot('create', TaskStatus::class)) {
            abort(403);
        }

        $data = ['name' => $request->input('name')];
        $validator = Validator::make($data, [
            'name' => 'required|unique:App\Models\TaskStatus',
        ], trans('validation.custom.status'));

        if ($validator->fails()) {
            return response()
                ->redirectToRoute('taskStatus.create')
                ->withErrors($validator)
                ->withInput();
        }

        $taskStatus = new TaskStatus();
        $taskStatus->fill($data);
        $taskStatus->save();

        flash(trans('flash.status.created'))->success();
        return redirect()
            ->route('taskStatus.index');
    }

    public function destroy(Request $request, $id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        if (optional($request->user())->cannot('delete', $taskStatus)) {
            abort(403);
        }
        if ($taskStatus->tasks->count() > 0) {
            flash(trans('flash.status.delete_failed'))->error();
            return redirect()->route('taskStatus.index');
        }
        $taskStatus->delete();
        flash(trans('flash.status.deleted'))->success();
        return redirect()->route('taskStatus.index');
    }

    public function edit(Request $request, $id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        if (optional($request->user())->cannot('update', $taskStatus)) {
            abort(403);
        }

        return view('taskStatus.edit', compact('taskStatus'));
    }

    public function update(Request $request, $id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        if (optional($request->user())->cannot('update', $taskStatus)) {
            abort(403);
        }

        $data = ['name' => $request->input('name')];

        $validator = Validator::make($data, [
            'name' => 'required|unique:App\Models\TaskStatus,name,' . $taskStatus->id
        ], trans('validation.custom.status'));

        if ($validator->fails()) {
            return response()
                ->redirectToRoute('taskStatus.edit', $taskStatus->id)
                ->withErrors($validator)
                ->withInput();
        }

        $taskStatus->fill($data);
        $taskStatus->save();
        flash(trans('flash.status.updated'))->success();
        return redirect()
            ->route('taskStatus.index');
    }
}
