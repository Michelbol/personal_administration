<?php

namespace App\Console\Commands;

use Exception;
use App\Models\CoverageAnalysis;
use Illuminate\Console\Command;

class CoverageAnalisys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coverage:analysis {file_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will analyse the coverage into the project';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        $coverageFile = file($this->argument('file_name'));
        $coverageClass = new CoverageAnalysis($coverageFile);
        $coverageClass->calcStatistics();
        if($coverageClass->isCoverageValid()){
            throw new Exception('Coverage is to low to continuous');
        }
        return;
    }
}
