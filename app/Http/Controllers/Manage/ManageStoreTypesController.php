<?php

namespace App\Http\Controllers\Manage;

use App\Models\StoreType;
use App\Http\Requests\Manage\ManageStoreTypeRequest;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ManageStoreTypesController extends ManageController
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->setActiveSection('manage');
        $this->setActivePage('manage.store_types');
        $this->setLabel('Категории');
        $this->addBreadcrumb('Управление', route('manage.store_types.index'));

        if (request()->route()->getActionMethod() != 'index') {
            $this->addBreadcrumb('Категории', route('manage.store_types.index'));
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Управление категориями');

        return view('manage.store_types.index', $this->withData([
            'entities' => StoreType::filter()->paginate($this->itemsPerPage),
        ]));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $this->setTitle('Добавление категории');

        return view('manage.store_types.form', $this->withData([
            'action' => route('manage.store_types.store'),
            'entity' => null,
        ]));
    }

    /**
     * @param \App\Http\Requests\Manage\ManageStoreTypeRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ManageStoreTypeRequest $request): \Illuminate\Http\RedirectResponse
    {
        StoreType::create($request->all());

        return redirect()->route('manage.store_types.index')
            ->with('status-success', 'Категория успешно добавлена');
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id): \Illuminate\View\View
    {
        $entity = StoreType::findOrFail($id);

        $this->setTitle('Редактирование категории');

        return view('manage.store_types.form', $this->withData([
            'action' => route('manage.store_types.update', $entity),
            'entity' => $entity,
        ]));
    }

    /**
     * @param int $id
     * @param \App\Http\Requests\Manage\ManageStoreTypeRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, ManageStoreTypeRequest $request): \Illuminate\Http\RedirectResponse
    {
        /** @var StoreType $entity */
        $entity = StoreType::findOrFail($id);
        $entity->update($request->all());

        return redirect()->route('manage.store_types.index')
            ->with('status-success', 'Категория успешно изменена');
    }
}
