<?php

namespace App\Http\Controllers\Manage;

use App\Models\User;
use App\Http\Requests\Manage\ManageUserRequest;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ManageUsersController extends ManageController
{

    /**
     * @return void
     */
    public function __construct()
    {
        $this->setActiveSection('manage');
        $this->setActivePage('manage.users');
        $this->setLabel('Пользователи');
        $this->addBreadcrumb('Управление', route('manage.users.index'));

        if (request()->route()->getActionMethod() != 'index') {
            $this->addBreadcrumb('Пользователи', route('manage.users.index'));
        }
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Управление пользователями');

        return view('manage.users.index', $this->withData([
            'entities' => User::filter()->paginate($this->itemsPerPage),
        ]));
    }


    /**
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $this->setTitle('Добавление пользователя');

        return view('manage.users.form', $this->withData([
            'action' => route('manage.users.store'),
            'entity' => null,
        ]));
    }


    /**
     * @param \App\Http\Requests\Manage\ManageUserRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ManageUserRequest $request): \Illuminate\Http\RedirectResponse
    {
        $attributes = $request->all();
        $attributes['password'] = bcrypt($attributes['new_password']);

        User::create($attributes);

        return redirect()->route('manage.users.index')
            ->with('status-success', 'Пользователь успешно добавлен');
    }


    /**
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id): \Illuminate\View\View
    {
        $entity = User::findOrFail($id);

        abort_if($entity->trashed(), 403);

        $this->setTitle('Редактирование пользователя');

        return view('manage.users.form', $this->withData([
            'action' => route('manage.users.update', $entity),
            'entity' => $entity,
        ]));
    }


    /**
     * @param int                                         $id
     * @param \App\Http\Requests\Manage\ManageUserRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, ManageUserRequest $request): \Illuminate\Http\RedirectResponse
    {
        /** @var User $entity */
        $entity = User::findOrFail($id);

        abort_if($entity->trashed(), 403);

        $attributes = $request->all();

        if (isset($attributes['new_password']) && ! empty($attributes['new_password'])) {
            $attributes['password'] = bcrypt($attributes['new_password']);
        }

        $entity->update($attributes);

        return redirect()->route('manage.users.index')
            ->with('status-success', 'Пользователь успешно изменен');
    }


    /**
     * @param \App\Models\User $entity
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function toggle(User $entity): \Illuminate\Http\RedirectResponse
    {
        if ($entity->trashed()) {
            $entity->restore();

            return redirect()->route('manage.users.index')
                ->with('status-success', 'Пользователь успешно востановлен');
        }

        $entity->delete();

        return redirect()->route('manage.users.index')
            ->with('status-danger', 'Пользователь успешно деактивирован');
    }

}
