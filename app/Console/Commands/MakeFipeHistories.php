<?php

namespace App\Console\Commands;

use App\Models\Car;
use App\Models\FipeHistory;
use App\Services\FipeService;
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
     */
    public function handle()
    {
        $cars = Car::whereNotNull(['model', 'brand', 'year'])->get();
        $service = new FipeService();
        foreach ($cars as $car){
            $search = $service->getPrice($car->brand, $car->model, $car->year);
            $history = new FipeHistory();
            $history->consultation_date = now();
            $history->car_id = $car->id;
            $history->value = (float) formatReal($search->preco);
            $history->save();
        }
        return;
    }
}
