<?php

namespace App\Enums;

enum TipoEntrada: string
{
    case ENTRADA = 'ENTRADA';
    case SALIDA = 'SALIDA';
    case VENTA = 'VENTA';
    case REPOSICION = 'REPOSICION';
    case AJUSTE = 'AJUSTE';
}
