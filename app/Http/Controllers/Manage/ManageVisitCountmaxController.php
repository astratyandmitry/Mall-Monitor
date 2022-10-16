<?php

namespace App\Http\Controllers\Manage;

use App\Models\VisitCountmax;
use App\Http\Requests\Manage\ManageVisitCountmaxRequest;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ManageVisitCountmaxController extends ManageController
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->setActiveSection('manage');
        $this->setActivePage('manage.visit_countmax');
        $this->setLabel('Счетчики');
        $this->addBreadcrumb('Управление', route('manage.visit_countmax.index'));

        if (request()->route()->getActionMethod() != 'index') {
            $this->addBreadcrumb('Счетчики', route('manage.visit_countmax.index'));
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Управление счетчиками');

        return view('manage.visit_countmax.index', $this->withData([
            'entities' => VisitCountmax::filter()->paginate($this->itemsPerPage),
        ]));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $this->setTitle('Добавление счетчика');

        return view('manage.visit_countmax.form', $this->withData([
            'action' => route('manage.visit_countmax.store'),
            'entity' => null,
        ]));
    }

    /**
     * @param \App\Http\Requests\Manage\ManageVisitCountmaxRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ManageVisitCountmaxRequest $request): \Illuminate\Http\RedirectResponse
    {
        $attributes = $request->all();
        $attributes['password'] = bcrypt($attributes['new_password']);

        VisitCountmax::create($attributes);

        return redirect()->route('manage.visit_countmax.index')
            ->with('status-success', 'Счетчик успешно добавлен');
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id): \Illuminate\View\View
    {
        $entity = VisitCountmax::findOrFail($id);

        abort_if($entity->trashed(), 403);

        $this->setTitle('Редактирование счетчика');

        return view('manage.visit_countmax.form', $this->withData([
            'action' => route('manage.visit_countmax.update', $entity),
            'entity' => $entity,
        ]));
    }

    /**
     * @param int $id
     * @param \App\Http\Requests\Manage\ManageVisitCountmaxRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, ManageVisitCountmaxRequest $request): \Illuminate\Http\RedirectResponse
    {
        /** @var VisitCountmax $entity */
        $entity = VisitCountmax::findOrFail($id);

        abort_if($entity->trashed(), 403);

        $attributes = $request->all();

        if (isset($attributes['new_password']) && ! empty($attributes['new_password'])) {
            $attributes['password'] = bcrypt($attributes['new_password']);
        }

        $entity->update($attributes);

        return redirect()->route('manage.visit_countmax.index')
            ->with('status-success', 'Счетчик успешно изменен');
    }
}
