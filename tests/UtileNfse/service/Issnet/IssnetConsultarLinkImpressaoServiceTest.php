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
use UtileNfse\Tests\Service\AbstractServiceTest;
use UtileNfse\UtileNfseFrontController;

class IssnetConsultarLinkImpressaoServiceTest extends AbstractServiceTest
{
    public function testEnvioLote(){
        try {
            $codigoMunicipio = 999;
            $prestador = $this->getPrestador($codigoMunicipio);
            $nfse = new NfseDTO();
            $nfse->setNumero('1');

            $requisicao = new RequisicaoDTO();
            $requisicao->setConfiguracao($this->getConfiguracao());
            $requisicao->setCodigoTributacaoMunicipio('7');
            $requisicao->setPrestador($prestador);
            $requisicao->setNfse($nfse);

            $utileNfse = new UtileNfseFrontController($requisicao);
            $retorno = $utileNfse->consultarLinkImpressaoNfse();
            echo "URL: " . $retorno->getUrlImpressaoNfse();
        } catch (\Exception $ex){
            echo $ex->getMessage();
        }
    }
}