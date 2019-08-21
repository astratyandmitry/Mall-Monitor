<?php

namespace App\Http\Controllers\Manage;

use App\Models\Developer;
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
        $store = Store::create($request->except(['username', 'password']));

        $developerAttributes = $request->only(['username', 'password']);

        if (isset($developerAttributes['username']) && !empty($developerAttributes['username'])) {
            Developer::query()->create(array_merge($developerAttributes, [
                'mall_id' => $store->mall_id,
                'store_id' => $store->id,
            ]));
        }

        return redirect()->route('manage.stores.index')
            ->with('status-success', 'Арендатор успешно добавлен');
    }


    /**
     * @param \App\Models\Store $store
     *
     * @return \Illuminate\View\View
     */
    public function edit(Store $store): \Illuminate\View\View
    {
        $this->setTitle('Редактирование арендатора');

        return view('manage.stores.form', $this->withData([
            'action' => route('manage.stores.update', $store),
            'entity' => $store,
        ]));
    }


    /**
     * @param \App\Models\Store $store
     * @param \App\Http\Requests\Manage\ManageStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Store $store, ManageStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $store->update($request->all());

        if ($store->trashed() && $request->activate)  {
            $store->restore();
        }

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
