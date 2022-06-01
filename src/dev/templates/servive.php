<?php

namespace App\Http\ServicesSUB;

#use service;use App\Http\Services\PRECTRLService;

use Illuminate\Http\Request;
use Gomee\Helpers\Arr;

use App\Repositories\REPF\REPORepository;

class NAMEService extends MASTERService
{
    protected $module = 'MODULE';

    protected $moduleName = 'TITLE';

    protected $flashMode = true;

    /**
     * repository chinh
     *
     * @var REPORepository
     */
    public $repository;
    
    /**
     * Create a new Service instance.
     *
     * @return void
     */
    public function __construct(REPORepository $repository)
    {
        $this->repository = $repository;
        $this->init();
    }

}
