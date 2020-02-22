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
use UtileNfse\model\enumerated\SituacaoLoteRpsEnum;
use UtileNfse\Tests\Service\AbstractServiceTest;
use UtileNfse\UtileNfseFrontController;

class IssnetConsultaLoteServiceTest extends AbstractServiceTest
{
    public function testConsultarLoteRps(){
        try {
            $lote = new LoteDTO();
            $lote->setNumero('48');
            $lote->setProtocolo('fc3eacce-018c-4d08-8525-03a49be270c2');
            $prestador = $this->getPrestador(4104808);//Homologaçao

            $requisicao = new RequisicaoDTO();
            $requisicao->setConfiguracao($this->getConfiguracao());
            $requisicao->setLote($lote);
            $requisicao->setPrestador($prestador);

            $utileNfse = new UtileNfseFrontController($requisicao);
            $retorno = $utileNfse->consultarSituacaoLoteRps($lote->getNumero());

            if(!empty($retorno->getListaErros())){
                echo $retorno->getListaErros();
            } else if($retorno->getLote()->getSituacao() == SituacaoLoteRpsEnum::PROCESSADO_COM_SUCESSO) {
                //Prcessado com sucesso, agora consulta nfs-e(s) gerada(s)
                $retorno = $utileNfse->consultarLoteRps($lote->getNumero());
                echo "Numero NFS-e: " . $retorno->getListaNfse()[0]->getNumero();
            } else {
                echo "Algum erro aconteceu, tratar mensagem.";
            }
            //print_r($retorno);

        } catch(\Exception $ex){
            echo $ex->getMessage();
        }
    }

}