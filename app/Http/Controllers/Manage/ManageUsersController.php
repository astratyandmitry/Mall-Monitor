<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\ManageUserRequest;
use App\Models\User;

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
        $this->addBreadcrumb('Управление', route('manage.users.index'));
        $this->addBreadcrumb('Пользователи', route('manage.users.index'));
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
        $this->addBreadcrumb('Добавление пользователя', null);

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

        $this->setTitle('Редактирование пользователя');
        $this->addBreadcrumb('Редактирование пользователя', null);

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
        /** @var User $user */
        $user = User::findOrFail($id);

        $attributes = $request->all();

        if (isset($attributes['new_password']) && ! empty($attributes['new_password'])) {
            $attributes['password'] = bcrypt($attributes['new_password']);
        }

        $user->update($attributes);

        return redirect()->route('manage.users.index')
            ->with('status-success', 'Пользователь успешно изменен');
    }


    /**
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function toggle(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->trashed()) {
            $user->restore();

            return redirect()->route('manage.users.index')
                ->with('status-success', 'Пользователь успешно востановлен');
        }

        $user->delete();

        return redirect()->route('manage.users.index')
            ->with('status-danger', 'Пользователь успешно удален');
    }

}
