<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\services\Warehouse\MovInventarioservice;
use Illuminate\Http\Request;

class MovimientodeInventario extends Controller
{
    protected $movInv;

    public function __construct(
        MovInventarioservice $movInv
    ) {
        $this->movInv = $movInv;
    }

    public function index()
    {
        return $this->movInv->listMov();
    }
}
