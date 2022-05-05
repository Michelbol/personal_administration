<?php

namespace App\Console\Commands;

use App\Models\Car;
use App\Models\FipeHistory;
use App\Services\FipeService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class MakeFipeHistories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fipe:histories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will search to all cars into system the value of fipe';
    /**
     * @var FipeService
     */
    private $fipeService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FipeService $fipeService)
    {
        parent::__construct();
        $this->fipeService = $fipeService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws GuzzleException
     */
    public function handle()
    {
        $cars = Car::whereNotNull(['model', 'brand', 'year'])->get();
        foreach ($cars as $car){
            $search = $this->fipeService->getPrice($car->brand, $car->model, $car->year);
            if(FipeHistory::where('value', formatReal($search->Valor))->exists()){
                return;
            }
            $history = new FipeHistory();
            $history->consultation_date = now();
            $history->car_id = $car->id;
            $history->value = (float) formatReal($search->Valor);
            $history->save();
        }
        return;
    }
}
