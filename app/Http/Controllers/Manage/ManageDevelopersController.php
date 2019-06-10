<?php

namespace App\Http\Controllers\Manage;

use App\Models\Developer;
use App\Http\Requests\Manage\ManageDeveloperRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ManageDevelopersController extends ManageController
{

    /**
     * @return void
     */
    public function __construct()
    {
        $this->setActiveSection('manage');
        $this->setActivePage('manage.developers');
        $this->setLabel('Разработчики');
        $this->addBreadcrumb('Управление', route('manage.developers.index'));

        if (request()->route()->getActionMethod() != 'index') {
            $this->addBreadcrumb('Разработчики', route('manage.developers.index'));
        }
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $this->setTitle('Управление разработчиками');

        return view('manage.developers.index', $this->withData([
            'entities' => Developer::filter()->paginate($this->itemsPerPage),
        ]));
    }


    /**
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->setTitle('Добавление разработчика');

        return view('manage.developers.form', $this->withData([
            'action' => route('manage.developers.store'),
            'entity' => null,
        ]));
    }


    /**
     * @param \App\Http\Requests\Manage\ManageDeveloperRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ManageDeveloperRequest $request): RedirectResponse
    {
        $attributes = $request->all();
        $attributes['password'] = bcrypt($attributes['new_password']);

        Developer::query()->create($attributes);

        return redirect()->route('manage.developers.index')
            ->with('status-success', 'Разработчик успешно добавлен');
    }


    /**
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id): View
    {
        /** @var \App\Models\Developer $entity */
        $entity = Developer::query()->findOrFail($id);

        abort_if($entity->trashed(), 403);

        $this->setTitle('Редактирование разработчика');

        return view('manage.developers.form', $this->withData([
            'action' => route('manage.developers.update', $entity),
            'entity' => $entity,
        ]));
    }


    /**
     * @param int                                              $id
     * @param \App\Http\Requests\Manage\ManageDeveloperRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, ManageDeveloperRequest $request): RedirectResponse
    {
        /** @var \App\Models\Developer $entity */
        $entity = Developer::query()->findOrFail($id);

        abort_if($entity->trashed(), 403);

        $attributes = $request->all();

        if (isset($attributes['new_password']) && ! empty($attributes['new_password'])) {
            $attributes['password'] = bcrypt($attributes['new_password']);
        }

        $entity->update($attributes);

        return redirect()->route('manage.developers.index')
            ->with('status-success', 'Разработчик успешно изменен');
    }


    /**
     * @param \App\Models\Developer $entity
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function toggle(Developer $entity): RedirectResponse
    {
        if ($entity->trashed()) {
            $entity->restore();

            return redirect()->route('manage.developers.index')
                ->with('status-success', 'Разработчик успешно востановлен');
        }

        $entity->delete();

        return redirect()->route('manage.developers.index')
            ->with('status-danger', 'Разработчик успешно деактивирован');
    }

}
