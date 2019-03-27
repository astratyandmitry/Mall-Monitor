<?php

namespace App\Http\Controllers\Manage;

use App\Models\Store;
use App\Http\Requests\Manage\ManageStoreRequest;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ManageStoresController extends ManageController
{

    /**
     * @return void
     */
    public function __construct()
    {
        $this->setActiveSection('manage');
        $this->setActivePage('manage.stores');
        $this->setLabel('Арендаторы');
        $this->addBreadcrumb('Управление', route('manage.stores.index'));

        if (request()->route()->getActionMethod() != 'index') {
            $this->addBreadcrumb('Арендаторы', route('manage.stores.index'));
        }
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Управление арендаторами');

        return view('manage.stores.index', $this->withData([
            'entities' => Store::filter()->paginate($this->itemsPerPage),
        ]));
    }


    /**
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $this->setTitle('Добавление арендатора');

        return view('manage.stores.form', $this->withData([
            'action' => route('manage.stores.store'),
            'entity' => null,
        ]));
    }


    /**
     * @param \App\Http\Requests\Manage\ManageStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ManageStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        Store::create($request->all());

        return redirect()->route('manage.stores.index')
            ->with('status-success', 'Арендатор успешно добавлен');
    }


    /**
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id): \Illuminate\View\View
    {
        $entity = Store::findOrFail($id);

        $this->setTitle('Редактирование арендатора');

        return view('manage.stores.form', $this->withData([
            'action' => route('manage.stores.update', $entity),
            'entity' => $entity,
        ]));
    }


    /**
     * @param int                                          $id
     * @param \App\Http\Requests\Manage\ManageStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, ManageStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        /** @var Store $entity */
        $entity = Store::findOrFail($id);
        $entity->update($request->all());

        return redirect()->route('manage.stores.index')
            ->with('status-success', 'Арендатор успешно изменен');
    }


    /**
     * @param \App\Models\Store $entity
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function toggle(Store $entity): \Illuminate\Http\RedirectResponse
    {
        if ($entity->trashed()) {
            $entity->restore();

            return redirect()->route('manage.stores.index')
                ->with('status-success', 'Арендатор успешно востановлен');
        }

        $entity->delete();

        return redirect()->route('manage.stores.index')
            ->with('status-danger', 'Арендатор успешно деактивирован');
    }

}
