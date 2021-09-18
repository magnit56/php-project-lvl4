<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
{
    public function index()
    {
        $labelsPerPage = 10;
        $labels = Label::paginate($labelsPerPage);
        return view('label.index', compact('labels'));
    }

    public function show(Request $request, $id)
    {
        $label = Label::findOrFail($id);
        if (optional($request->user())->cannot('view', $label)) {
            abort(403);
        }

        return view('label.show', compact('label'));
    }

    public function create(Request $request)
    {
        if (optional($request->user())->cannot('create', Label::class)) {
            abort(403);
        }
        $label = new Label();
        return view('label.create', compact('label'));
    }

    public function store(Request $request)
    {
        if (optional($request->user())->cannot('create', Label::class)) {
            abort(403);
        }
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];
        $validator = Validator::make($data, [
            'name' => 'required|unique:App\Models\Label',
            'description' => '',
        ], trans('validation.custom.label'));

        if ($validator->fails()) {
            return response()
                ->redirectToRoute('label.create')
                ->withErrors($validator)
                ->withInput();
        }

        $label = new Label();
        $label->fill($data);
        $label->save();

        flash(trans('flash.label.created'))->success();
        return redirect()
            ->route('label.index');
    }

    public function destroy(Request $request, $id)
    {
        $label = Label::findOrFail($id);
        if (optional($request->user())->cannot('delete', $label)) {
            abort(403);
        }
        if ($label->tasks->count() > 0) {
            flash(trans('flash.label.delete_failed'))->error();
            return redirect()->route('label.index');
        }
        $label->delete();
        flash(trans('flash.label.deleted'))->success();
        return redirect()->route('label.index');
    }

    public function edit(Request $request, $id)
    {
        $label = Label::findOrFail($id);
        if (optional($request->user())->cannot('update', $label)) {
            abort(403);
        }

        return view('label.edit', compact('label'));
    }

    public function update(Request $request, $id)
    {
        $label = Label::findOrFail($id);
        if (optional($request->user())->cannot('update', $label)) {
            abort(403);
        }

        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        $validator = Validator::make($data, [
            'name' => 'required|unique:App\Models\Label,name,' . $label->id,
            'description' => '',
        ], trans('validation.custom.label'));

        if ($validator->fails()) {
            return response()
                ->redirectToRoute('label.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        $label->fill($data);
        $label->save();
        flash(trans('flash.label.updated'))->success();
        return redirect()
            ->route('label.index');
    }
}
