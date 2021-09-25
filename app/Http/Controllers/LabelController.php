<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
{
    public function index(): View|Factory
    {
        $labelsPerPage = 10;
        // @phpstan-ignore-next-line
        $labels = Label::paginate($labelsPerPage);
        return view('label.index', compact('labels'));
    }

    public function show(Request $request, int|string $id): View|Factory
    {
        // @phpstan-ignore-next-line
        $label = Label::findOrFail($id);
        if (optional($request->user())->cannot('view', $label)) {
            abort(403);
        }
        return view('label.show', compact('label'));
    }

    public function create(Request $request): View|Factory
    {
        if (optional($request->user())->cannot('create', Label::class)) {
            abort(403);
        }
        $label = new Label();
        return view('label.create', compact('label'));
    }

    public function store(Request $request): RedirectResponse
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
            // @phpstan-ignore-next-line
        ], trans('validation.custom.label')); // @phpstan-ignore-line

        if ($validator->fails()) {
            // @phpstan-ignore-next-line
            return response()
                ->redirectToRoute('label.create')
                ->withErrors($validator)
                ->withInput();
        }

        $label = new Label();
        $label->fill($data);
        $label->save();

        // @phpstan-ignore-next-line
        flash(trans('flash.label.created'))->success();

        // @phpstan-ignore-next-line
        return redirect()
            ->route('label.index');
    }

    public function destroy(Request $request, int|string $id): RedirectResponse
    {
        // @phpstan-ignore-next-line
        $label = Label::findOrFail($id);
        if (optional($request->user())->cannot('delete', $label)) {
            abort(403);
        }
        if ($label->tasks->count() > 0) {
            // @phpstan-ignore-next-line
            flash(trans('flash.label.delete_failed'))->error();
            // @phpstan-ignore-next-line
            return redirect()->route('label.index');
        }
        $label->delete();
        // @phpstan-ignore-next-line
        flash(trans('flash.label.deleted'))->success();
        // @phpstan-ignore-next-line
        return redirect()->route('label.index');
    }

    public function edit(Request $request, int|string $id): View|Factory
    {
        // @phpstan-ignore-next-line
        $label = Label::findOrFail($id);
        if (optional($request->user())->cannot('update', $label)) {
            abort(403);
        }

        return view('label.edit', compact('label'));
    }

    public function update(Request $request, int|string $id): RedirectResponse
    {
        // @phpstan-ignore-next-line
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
        ], trans('validation.custom.label')); // @phpstan-ignore-line

        if ($validator->fails()) {
            // @phpstan-ignore-next-line
            return response()
                ->redirectToRoute('label.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        $label->fill($data);
        $label->save();
        // @phpstan-ignore-next-line
        flash(trans('flash.label.updated'))->success();
        // @phpstan-ignore-next-line
        return redirect()
            ->route('label.index');
    }
}
