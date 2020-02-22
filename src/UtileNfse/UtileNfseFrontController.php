<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 23/08/2016
 * Time: 23:16
 */

namespace UtileNfse;


use UtileNfse\conversor\IConversor;
use UtileNfse\model\dto\RequisicaoDTO;
use UtileNfse\model\enumerated\AmbienteEnum;
use UtileNfse\service\IService;

class UtileNfseFrontController
{
    private $requisicao;
    private $service;
    private $conversor;

    public function __construct(RequisicaoDTO $requisicaoDTO)
    {
        $codigoMunicipio = $requisicaoDTO->getPrestador()->getCodigoMunicipio();

        if(empty($codigoMunicipio) && isNull($codigoMunicipio)){
            throw new \Exception("Código do Muníciopio não informado no prestador.");
        }

        $cidade = $this->getCidade($codigoMunicipio);

        if($cidade == null){
            throw new \Exception("Cidade com código: $codigoMunicipio, não implementada.");
        }

        $configuracao = $requisicaoDTO->getConfiguracao();

        if($configuracao->getAmbiente() == AmbienteEnum::HOMOLOGACAO){
            $configuracao->setUrlWebservice($cidade->urlHomologacao);
        } else {
            $configuracao->setUrlWebservice($cidade->urlProducao);
        }

        $requisicaoDTO->setConfiguracao($configuracao);
        $this->requisicao = $requisicaoDTO;

        $conversor = new \ReflectionClass("UtileNfse\\conversor\\" . ucfirst($cidade->provedor) . "Conversor");
        $this->setConversor($conversor->newInstanceArgs(array($this->requisicao)));

        $service = new \ReflectionClass("UtileNfse\\service\\" . ucfirst($cidade->provedor) . "Service");
        $this->setService($service->newInstanceArgs(array($this->conversor)));
    }

    private function getCidade($codigoMunicipio){
        $pathXml = __DIR__ . DIRECTORY_SEPARATOR . "../../resources/config/cidades_atendidas.xml";
        $xmlCidades = simplexml_load_file($pathXml);
        $cidadeReturn = null;
        foreach ($xmlCidades->cidade as $cidade) {
            if($cidade->codigo == $codigoMunicipio){
                $cidadeReturn = $cidade;
            }
        }
        return $cidadeReturn;
    }

    public function enviarLoteRps($numeroLote){
        return $this->getService()->enviarLoteRps($numeroLote);
    }

    public function consultarSituacaoLoteRps($numeroLote){
        return $this->getService()->consultarSituacaoLoteRps($numeroLote);
    }

    public function consultarLoteRps($numeroLote){
        return $this->getService()->consultarLoteRps($numeroLote);
    }

    public function cancelarNfse(){
        return $this->getService()->cancelarNfse();
    }

    public function consultarLinkImpressaoNfse(){
        return $this->getService()->consultarLinkImpressaoNfse();
    }

    private function setService(IService $service){
        $this->service = $service;
    }

    private function setConversor(IConversor $conversor){
        $this->conversor = $conversor;
    }

    private function getService(){
        return $this->service;
    }

    private function getConversor(){
        return $this->conversor;
    }
}