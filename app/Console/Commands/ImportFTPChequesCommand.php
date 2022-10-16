<?php

namespace App\Console\Commands;

use App\Models\Cheque;
use App\Models\StoreIntegration;
use App\Models\StoreIntegrationLog;
use App\Models\StoreIntegrationType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Integration\Store\ExcelChequeTransformer;
use App\Integration\Store\XMLChequeTransformer;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ImportFTPChequesCommand extends Command
{
    use ValidatesRequests;

    /**
     * @var string
     */
    protected $signature = 'keruenmonitor:import-ftp-cheques';

    /**
     * @var string
     */
    protected $description = 'Import Cheques from FTP';

    /**
     * @return void
     */
    public function handle(): void
    {
        /** @var \Illuminate\Support\Collection|StoreIntegration[] $ftpIntegrations */
        $ftpIntegrations = StoreIntegration::query()->whereNotNull('ftp_username')->get();

        if ($ftpIntegrations->isEmpty()) {
            $this->warn("No available FTP integrations");

            return;
        }

        foreach ($ftpIntegrations as $ftpIntegration) {
            $user = $ftpIntegration->ftp_username;

            $this->info("Working with FTP-user `{$user}`");

            $filesDir = storage_path("import/{$user}/files");

            if (! File::isDirectory($filesDir)) {
                $this->error("Directory not exists `{$filesDir}`");
                continue;
            }

            $files = File::files($filesDir);

            if (! count($files)) {
                $this->warn("No files was uploaded to`{$filesDir}`");
            }

            foreach ($files as $file) {
                $this->info(" — Importing `{$file->getFilename()}`");

                if ($ftpIntegration->type_id === StoreIntegrationType::XML) {
                    $output = $this->importXML($ftpIntegration, $file->getPathname());
                } else {
                    $output = $this->importExcel($ftpIntegration, $file->getPathname());
                }

                $this->table([
                    'Success',
                    'Skip',
                    'Error',
                ], [
                    [
                        count($output['success']),
                        count($output['skip']),
                        count($output['error']),
                    ],
                ]);

                File::delete($file->getPathname());
            }
        }
    }

    /**
     * @param \App\Models\StoreIntegration $integration
     * @param string $file
     * @return array
     */
    protected function importXML(StoreIntegration $integration, string $file): array
    {
        $output = [
            'error' => [],
            'skip' => [],
            'success' => [],
        ];

        $items = simplexml_load_file($file);

        if (count($items)) {
            $transformer = new XMLChequeTransformer($integration);

            foreach ($items as $item) {
                $attributes = $transformer->setItem($item)->onlyRequired();

                /** @var \Illuminate\Validation\Validator $validator */
                $validator = $this->getValidationFactory()->make($attributes, [
                    'code' => 'required|max:200',
                    'number' => 'required|max:200',
                    'amount' => 'required|numeric',
                    'created_at' => 'required',
                ]);

                if ($validator->fails()) {
                    $output['error'][] = [
                        'data' => $attributes,
                        'validation' => $validator->errors(),
                    ];
                } else {
                    if (Cheque::uniqueAttrs($transformer->integration->store_id, $attributes)->exists()) {
                        $output['skip'][] = [
                            'data' => $attributes,
                        ];
                    } else {
                        /** @var \App\Models\Cheque $cheque */
                        $cheque = Cheque::query()->create($transformer->toAttributes());

                        $output['success'][] = [
                            'data' => $cheque->toArray(),
                        ];
                    }
                }
            }
        }

        StoreIntegrationLog::store(StoreIntegrationType::XML, $integration->store, $output);

        return $output;
    }

    /**
     * @param \App\Models\StoreIntegration $integration
     * @param string $file
     * @return array
     */
    protected function importExcel(StoreIntegration $integration, string $file): array
    {
        $output = [
            'error' => [],
            'skip' => [],
            'success' => [],
        ];

        Excel::load($file, function ($reader) use (&$output, $integration) {
            $items = $reader->get()->toArray();

            if (count($items)) {
                $transformer = new ExcelChequeTransformer($integration);

                foreach ($items as $item) {
                    $attributes = $transformer->setItem($item)->onlyRequired();

                    /** @var \Illuminate\Validation\Validator $validator */
                    $validator = $this->getValidationFactory()->make($attributes, [
                        'code' => 'required|max:200',
                        'number' => 'required|max:200',
                        'amount' => 'required|numeric',
                        'created_at' => 'required',
                    ]);

                    if ($validator->fails()) {
                        $output['error'][] = [
                            'data' => $attributes,
                            'validation' => $validator->errors(),
                        ];
                    } else {
                        if (Cheque::uniqueAttrs($transformer->integration->store_id, $attributes)->first()) {
                            $output['skip'][] = [
                                'data' => $attributes,
                            ];
                        } else {
                            /** @var \App\Models\Cheque $cheque */
                            $cheque = Cheque::query()->create($transformer->toAttributes());

                            $output['success'][] = [
                                'data' => $cheque->toArray(),
                            ];
                        }
                    }
                }
            }
        });

        StoreIntegrationLog::store(StoreIntegrationType::XML, $integration->store, $output);

        return $output;
    }
}
