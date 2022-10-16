<?php

namespace App\Http\Controllers\Manage;

use App\Models\Cashbox;
use App\Http\Requests\Manage\ManageCashboxRequest;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ManageCashboxesController extends ManageController
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->setActiveSection('manage');
        $this->setActivePage('manage.cashboxes');
        $this->setLabel('Кассы');
        $this->addBreadcrumb('Управление', route('manage.cashboxes.index'));

        if (request()->route()->getActionMethod() != 'index') {
            $this->addBreadcrumb('Кассы', route('manage.cashboxes.index'));
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Управление кассами');

        return view('manage.cashboxes.index', $this->withData([
            'entities' => Cashbox::filter()->paginate($this->itemsPerPage),
        ]));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $this->setTitle('Добавление кассы');

        return view('manage.cashboxes.form', $this->withData([
            'action' => route('manage.cashboxes.store'),
            'entity' => null,
        ]));
    }

    /**
     * @param \App\Http\Requests\Manage\ManageCashboxRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ManageCashboxRequest $request): \Illuminate\Http\RedirectResponse
    {
        Cashbox::create($request->all());

        return redirect()->route('manage.cashboxes.index')
            ->with('status-success', 'Касса успешно добавлена');
    }

    /**
     * @param \App\Models\Cashbox $cashbox
     *
     * @return \Illuminate\View\View
     */
    public function edit(Cashbox $cashbox): \Illuminate\View\View
    {
        $this->setTitle('Редактирование кассы');

        return view('manage.cashboxes.form', $this->withData([
            'action' => route('manage.cashboxes.update', $cashbox),
            'entity' => $cashbox,
        ]));
    }

    /**
     * @param \App\Models\Cashbox $cashbox
     * @param \App\Http\Requests\Manage\ManageCashboxRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Cashbox $cashbox, ManageCashboxRequest $request): \Illuminate\Http\RedirectResponse
    {
        $cashbox->update($request->all());

        if ($cashbox->trashed() && $request->activate) {
            $cashbox->restore();
        }

        return redirect()->route('manage.cashboxes.index')
            ->with('status-success', 'Касса успешно изменена');
    }

    /**
     * @param \App\Models\Cashbox $entity
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function toggle(Cashbox $entity): \Illuminate\Http\RedirectResponse
    {
        if ($entity->trashed()) {
            $entity->restore();

            return redirect()->route('manage.cashboxes.index')
                ->with('status-success', 'Касса успешно востановлена');
        }

        $entity->delete();

        return redirect()->route('manage.cashboxes.index')
            ->with('status-danger', 'Касса успешно деактивирована');
    }
}
