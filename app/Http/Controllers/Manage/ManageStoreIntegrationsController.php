<?php

namespace App\Http\Controllers\Manage;

use App\Models\StoreIntegrationType;
use Illuminate\View\View;
use App\Models\StoreIntegration;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Manage\ManageStoreIntegrationRequest;
use App\Http\Requests\Manage\ManageStoreIntegrationConfigRequest;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Readers\LaravelExcelReader;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ManageStoreIntegrationsController extends ManageController
{

    /**
     * @return void
     */
    public function __construct()
    {
        $this->setActiveSection('manage');
        $this->setActivePage('manage.store_integrations');
        $this->setLabel('Конфигурации');
        $this->addBreadcrumb('Управление', route('manage.store_integrations.index'));

        if (request()->route()->getActionMethod() != 'index') {
            $this->addBreadcrumb('Конфигурации', route('manage.store_integrations.index'));
        }
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $this->setTitle('Управление конфигурациями');

        return view('manage.store_integrations.index', $this->withData([
            'entities' => StoreIntegration::filter()->paginate($this->itemsPerPage),
        ]));
    }


    /**
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->setTitle('Добавление конфигурации');

        return view('manage.store_integrations.form', $this->withData([
            'action' => route('manage.store_integrations.store'),
            'entity' => null,
        ]));
    }


    /**
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function configure(int $id): View
    {
        $this->setTitle('Настройка конфигурации');

        return view('manage.store_integrations.configure', $this->withData([
            'action' => route('manage.store_integrations.config', $id),
            'entity' => StoreIntegration::query()->findOrFail($id),
        ]));
    }


    /**
     * @param int                                                           $id
     * @param \App\Http\Requests\Manage\ManageStoreIntegrationConfigRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function config(int $id, ManageStoreIntegrationConfigRequest $request): RedirectResponse
    {
        /** @var \App\Models\StoreIntegration $entity */
        $entity = StoreIntegration::query()->findOrFail($id);
        $entity->update($request->all());

        return redirect()->route('manage.store_integrations.index')
            ->with('status-success', 'Конфигурация успешно обновлена');
    }


    /**
     * @param \App\Http\Requests\Manage\ManageStoreIntegrationRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ManageStoreIntegrationRequest $request): RedirectResponse
    {
        if ($request->get('type_id') == StoreIntegrationType::XML) {
            $attrs = $this->getAttrsFromFileXML($request->file('file'));
        } else {
            $attrs = $this->getAttrsFromFileExcel($request->file('file'));
        }

        if ( ! count($attrs)) {
            return redirect()->back();
        }

        /** @var \App\Models\StoreIntegration $entity */
        $entity = StoreIntegration::query()->create(array_merge($request->all(), [
            'columns' => $attrs,
            'config' => [],
            'types' => [],
            'payments' => [],
        ]));

        return redirect()->route('manage.store_integrations.configure', $entity->id)
            ->with('status-success', 'Конфигурация успешно добавлена');
    }


    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return array
     */
    protected function getAttrsFromFileExcel(UploadedFile $file): array
    {
        $headings = [];

        Excel::load($file, function (LaravelExcelReader $reader) use (&$headings) {
            $headings = $reader->first()->keys()->toArray();
        });

        $attrs = [];

        foreach ($headings as $heading) {
            $attrs[$heading] = $heading;
        }

        return $attrs;
    }


    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return array
     */
    protected function getAttrsFromFileXML(UploadedFile $file): array
    {
        $xml = simplexml_load_file($file->getRealPath(), "SimpleXMLElement", LIBXML_NOCDATA);
        $array = json_decode(json_encode($xml), true);

        if ( ! is_array($array) || ! count($array)) {
            return [];
        }

        $keys = array_keys($array);
        $selectedKey = null;

        if (count($keys) > 1) {
            foreach ($keys as $key) {
                if (substr($key, 0, 1) == '@' || $key == 'comment') {
                    unset($array[$key]);
                }
            }

            $keys = array_keys($array);

            if (count($keys) > 1) {
                foreach ($keys as $key) {
                    if (is_null($selectedKey)) {
                        $selectedKey = $key;
                    } else {
                        if (count($array[$key]) > count($array[$selectedKey])) {
                            $selectedKey = $key;
                        }
                    }
                }
            }
        }

        if (is_null($selectedKey)) {
            $selectedKey = $keys[0];
        }

        $keys = array_keys($array[$selectedKey][0]);
        $attrs = [];

        foreach ($keys as $key) {
            $attrs[$key] = $key;
        }

        return $attrs;
    }


    /**
     * @param integer $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(int $id): RedirectResponse
    {
        $entity = StoreIntegration::query()->findOrFail($id);
        $entity->delete();

        return redirect()->route('manage.store_integrations.index')
            ->with('status-danger', 'Конфигурация успешно удалена');
    }

}
