<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetFinancial extends Model
{
    protected $fillable = [
        'month',
        'year',
        'isFinalized'
    ];
    protected $months = [
        1   => 'Janeiro',
        2   => 'Fevereiro',
        3   => 'Março',
        4   => 'Abril',
        5   => 'Maio',
        6   => 'Junho',
        7   => 'Julho',
        8   => 'Agosto',
        9   => 'Setembro',
        10  => 'Outubro',
        11  => 'Novembro',
        12  => 'Dezembro'
    ];

    public function month($index){
        return $this->months[$index];
    }
}
