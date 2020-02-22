<?php
/**
 * Created by PhpStorm.
 * User: adeli
 * Date: 28/05/2018
 * Time: 15:21
 */
namespace UtileNfse\Tests\Service\Bhiss;

use UtileNfse\model\dto\LoteDTO;
use UtileNfse\model\dto\RequisicaoDTO;
use UtileNfse\Tests\Service\AbstractServiceTest;
use UtileNfse\UtileNfseFrontController;

class BhissEnvioLoteServiceTest extends AbstractServiceTest
{
    public function testEnvioLote(){
        try {
            $codigoMunicipio = 3106200;
            $servicoDTO = $this->getServico($codigoMunicipio);
            $prestador = $this->getPrestador($codigoMunicipio);
            $tomador = $this->getTomador($codigoMunicipio);

            $rps1 = $this->getRps('5', '2');
            $rps1->setServico($servicoDTO);
            $rps1->setPrestador($prestador);
            $rps1->setTomador($tomador);

            $listaRps = array();
            $listaRps[] = $rps1;

            $lote = new LoteDTO();
            $lote->setNumero('11');
            $lote->setListaRps($listaRps);

            $requisicao = new RequisicaoDTO();
            $requisicao->setConfiguracao($this->getConfiguracao());
            $requisicao->setPrestador($prestador);
            $requisicao->setLote($lote);

            $utileNfse = new UtileNfseFrontController($requisicao);
            $retorno = $utileNfse->enviarLoteRps($lote->getNumero());
            echo "Protocolo: " . $retorno->getLote()->getProtocolo() . ' - ' . $retorno->getLote()->getDataProtocolo();
        } catch (\Exception $ex){
            echo "Erro: " . $ex->getMessage();
        }
    }
}
