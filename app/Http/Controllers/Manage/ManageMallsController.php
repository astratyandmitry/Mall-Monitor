<?php

namespace App\Http\Controllers\Manage;

use App\Models\Mall;
use App\Http\Requests\Manage\ManageMallRequest;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ManageMallsController extends ManageController
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->setActiveSection('manage');
        $this->setActivePage('manage.malls');
        $this->setLabel('ТРЦ');
        $this->addBreadcrumb('Управление', route('manage.malls.index'));

        if (request()->route()->getActionMethod() != 'index') {
            $this->addBreadcrumb('ТРЦ', route('manage.malls.index'));
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Управление ТРЦ');

        return view('manage.malls.index', $this->withData([
            'entities' => Mall::filter()->paginate($this->itemsPerPage),
        ]));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $this->setTitle('Добавление ТРЦ');

        return view('manage.malls.form', $this->withData([
            'action' => route('manage.malls.store'),
            'entity' => null,
        ]));
    }

    /**
     * @param \App\Http\Requests\Manage\ManageMallRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ManageMallRequest $request): \Illuminate\Http\RedirectResponse
    {
        Mall::create($request->all());

        return redirect()->route('manage.malls.index')
            ->with('status-success', 'ТРЦ успешно добавлен');
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id): \Illuminate\View\View
    {
        $entity = Mall::findOrFail($id);

        $this->setTitle('Редактирование ТРЦ');

        return view('manage.malls.form', $this->withData([
            'action' => route('manage.malls.update', $entity),
            'entity' => $entity,
        ]));
    }

    /**
     * @param int $id
     * @param \App\Http\Requests\Manage\ManageMallRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, ManageMallRequest $request): \Illuminate\Http\RedirectResponse
    {
        /** @var Mall $entity */
        $entity = Mall::findOrFail($id);
        $entity->update($request->all());

        return redirect()->route('manage.malls.index')
            ->with('status-success', 'ТРЦ успешно изменен');
    }
}
