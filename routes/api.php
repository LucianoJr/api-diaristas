<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Diarista\DiaristaPorCEP;

Route::get('/diaristas/localidades', DiaristaPorCEP::class)->name('diaristas.busca_por_cep');
