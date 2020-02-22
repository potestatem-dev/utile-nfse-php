<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 04/08/2016
 * Time: 01:13
 */

namespace UtileNfse\Tests\Service\Bhiss;


use UtileNfse\model\dto\LoteDTO;
use UtileNfse\model\dto\RequisicaoDTO;
use UtileNfse\Tests\Service\AbstractServiceTest;
use UtileNfse\UtileNfseFrontController;

class BhissConsultaLoteServiceTest extends AbstractServiceTest
{
    public function testConsultarLoteRps(){
        try {
            $lote = new LoteDTO();
            $lote->setNumero('11');
            $lote->setProtocolo('qqqqqqqqqqqq');

            $prestador = $this->getPrestador(3106200);//Homologaçao

            $requisicao = new RequisicaoDTO();
            $requisicao->setConfiguracao($this->getConfiguracao());
            $requisicao->setLote($lote);
            $requisicao->setPrestador($prestador);

            $utileNfse = new UtileNfseFrontController($requisicao);
            //Prcessado com sucesso, agora consulta nfs-e(s) gerada(s)
            $retorno = $utileNfse->consultarLoteRps($lote->getNumero());
            echo "Numero NFS-e: " . $retorno->getListaNfse()[0]->getNumero();
            //print_r($retorno);

        } catch(\Exception $ex){
            echo $ex->getMessage();
        }
    }

}