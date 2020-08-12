<?php


namespace App\Repositories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;

class SupplierRepository
{
    /**
     * @return Supplier[]|Collection
     */
    public function getAll()
    {
        return Supplier::all();
    }
}
