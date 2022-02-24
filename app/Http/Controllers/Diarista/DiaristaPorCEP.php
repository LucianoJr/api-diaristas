<?php

namespace App\Http\Controllers\Diarista;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DiaristaPublicoCollection;
use App\Services\ConsultaCEP\ConsultaCEPInterface;

class DiaristaPorCEP extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ConsultaCEPInterface $consultaCEP): DiaristaPublicoCollection|JsonResponse
    {

        $dados = $consultaCEP->buscar($request->cep ?? '');

        if ($dados === false) {
            return response()->json(['erro' => 'CEP inválido'], 400);
        }

        return new DiaristaPublicoCollection(
            User::diaristaDisponivelCidade($dados->ibge),
            User::diaristaDiponivelCidadeTotal($dados->ibge)
        );
    }
}
