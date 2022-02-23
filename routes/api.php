<?php

use App\Http\Controllers\Diarista\DiaristaPorCEP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/diaristas/localidades', DiaristaPorCEP::class)->name('diaristas.busca_por_cep');
