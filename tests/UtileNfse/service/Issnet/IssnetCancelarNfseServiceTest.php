<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 04/08/2016
 * Time: 01:13
 */

namespace UtileNfse\Tests\Service\Issnet;


use UtileNfse\model\dto\NfseDTO;
use UtileNfse\model\dto\RequisicaoDTO;
use UtileNfse\model\enumerated\CodigoCancelamentoEnum;
use UtileNfse\Tests\Service\AbstractServiceTest;
use UtileNfse\UtileNfseFrontController;

class IssnetCancelarNfseServiceTest extends AbstractServiceTest
{
    public function testCancelarNfse(){
        try {
            $nfse = new NfseDTO();
            $nfse->setNumero('1');
            $nfse->setCodigoCancelamento(CodigoCancelamentoEnum::ERRO_NA_EMISSAO);
            $prestador = $this->getPrestador(999);

            $requisicao = new RequisicaoDTO();
            $requisicao->setConfiguracao($this->getConfiguracao());
            $requisicao->setNfse($nfse);
            $requisicao->setPrestador($prestador);

            $utileNfse = new UtileNfseFrontController($requisicao);
            $retorno = $utileNfse->cancelarNfse();

            if(!empty($retorno->getListaErros())){
                echo $retorno->getListaErros();
            } else {
                //print_r($retorno);
            }
        } catch(\Exception $ex){
            echo $ex->getMessage();
        }
    }

}