<?php

namespace App\Services\ConsultaCEP\Providers;

use Illuminate\Support\Facades\Http;
use App\Services\ConsultaCEP\EnderecoResponse;
use App\Services\ConsultaCEP\ConsultaCEPInterface;

class ViaCEP implements ConsultaCEPInterface
{

    /**
     * Método para buscar cep utilizando a api via cep
     *
     * @param string $cep
     * @return false|EnderecoResponse
     */
    public function buscar(string $cep): false|EnderecoResponse
    {
        $resposta = Http::get("https://viacep.com.br/ws/$cep/json/");

        if ($resposta->failed()) {
            return false;
        }

        $dados = $resposta->json();

        if (isset($dados['erro']) && $dados['erro'] === true) {
            return false;
        }

        return $this->populaEnderecoResponse($dados);
        
    }

    /**
     * Método para formatar a saída para Endereço response
     */
    private function populaEnderecoResponse(array $dados): EnderecoResponse
    {
        return new EnderecoResponse(
            cep: $dados['cep'],
            logradouro: $dados['logradouro'],
            complemento: $dados['complemento'],
            bairro: $dados['bairro'],
            localidade: $dados['localidade'],
            uf: $dados['uf'],
            ibge: $dados['ibge'],
        );
    }
}