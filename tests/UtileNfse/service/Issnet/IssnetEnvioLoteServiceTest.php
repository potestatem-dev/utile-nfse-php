<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 04/08/2016
 * Time: 01:13
 */

namespace UtileNfse\Tests\Service\Issnet;

use UtileNfse\model\dto\LoteDTO;
use UtileNfse\model\dto\RequisicaoDTO;
use UtileNfse\Tests\Service\AbstractServiceTest;
use UtileNfse\UtileNfseFrontController;

class IssnetEnvioLoteServiceTest extends AbstractServiceTest
{
    public function testEnvioLote(){
        try {
            $codigoMunicipio = 999;
            $servicoDTO = $this->getServico($codigoMunicipio);
            $prestador = $this->getPrestador($codigoMunicipio);
            $tomador = $this->getTomador(4104808);

            $rps1 = $this->getRps('3', '8');
            $rps1->setServico($servicoDTO);
            $rps1->setPrestador($prestador);
            $rps1->setTomador($tomador);

            $listaRps = array();
            $listaRps[] = $rps1;

            $lote = new LoteDTO();
            $lote->setNumero('2');
            $lote->setListaRps($listaRps);

            $requisicao = new RequisicaoDTO();
            $requisicao->setConfiguracao($this->getConfiguracao());
            $requisicao->setPrestador($prestador);
            $requisicao->setLote($lote);

            $utileNfse = new UtileNfseFrontController($requisicao);
            $retorno = $utileNfse->enviarLoteRps($lote->getNumero());
            echo "Protocolo: " . $retorno->getLote()->getProtocolo() . ' - ' . $retorno->getLote()->getDataProtocolo();
        } catch (\Exception $ex){
            echo $ex->getMessage();
        }
    }
}