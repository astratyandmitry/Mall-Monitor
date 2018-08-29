<?php

namespace App\Console\Commands\Import;

use App\Models\Cheque;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ImportKeruenCityAstanaCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'mallmonitor:import-keruen-city-astana';

    /**
     * @var string
     */
    protected $description = 'Import Keruen City Astana';

    /**
     * @var string
     */
    protected $basePath = 'keruen-city-astana';

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;


    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        parent::__construct();
    }


    /**
     * @return mixed
     */
    public function handle()
    {
        $files = $this->filesystem->files(storage_path("/import/{$this->basePath}"));

        if (count($files)) {
            /** @var \SplFileInfo $file */
            foreach ($files as $file) {
                $this->info("Importing {$file->getBasename()}");

                $data = simplexml_load_file($file->getRealPath());

                foreach ($data as $doc) {
                    if ( ! $doc->PLU_AMNTN) continue;

                    Cheque::create([
                        'mall_id' => 1,
                        'store_id' => 1,
                        'code' => $doc->DOC_ID,
                        'amount' => $doc->PLU_AMNTN,
                        'created_at' => $doc->DOC_DATE,
                        'data' => [
                            'DOC_NUMBER' => $doc->DOC_NUMBER,
                            'PLU_NUMBER' => $doc->PLU_NUMBER,
                            'PLU_NAME' => $doc->PLU_NAME,
                            'PLU_QTTY' => $doc->PLU_QTTY,
                        ],
                    ]);
                }

                $this->filesystem->delete($file->getRealPath());
            }
        } else {
            $this->info('There are no available files for import');
        }
    }

}
