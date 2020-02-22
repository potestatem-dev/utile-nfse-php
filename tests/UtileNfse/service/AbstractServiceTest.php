<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 15/09/2016
 * Time: 14:57
 */

namespace UtileNfse\Tests\Service;

use UtileNfse\model\dto\ConfiguracaoDTO;
use UtileNfse\model\dto\PessoaDTO;
use UtileNfse\model\dto\RpsDTO;
use UtileNfse\model\dto\ServicoDTO;
use UtileNfse\model\enumerated\AmbienteEnum;
use UtileNfse\model\enumerated\NaturezaOperacaoEnum;
use UtileNfse\model\enumerated\RegimeEspecialTributacaoEnum;
use UtileNfse\model\enumerated\SimNaoEnum;
use UtileNfse\model\enumerated\StatusRpsEnum;
use UtileNfse\model\enumerated\TipoRpsEnum;

abstract class AbstractServiceTest extends \PHPUnit_Framework_TestCase
{
    protected function getConfiguracao(){
        $sep = DIRECTORY_SEPARATOR;
        $configuracao = new ConfiguracaoDTO();
        $configuracao->setNomeCertificadoPfx('certificado.pfx');
        $configuracao->setSenhaCertificado('11111111');
        $pathCertificados = __DIR__ . "../../../../resources" . $sep . "certificados";
        $configuracao->setDiretorioCertificados($pathCertificados);
        $pathXml = __DIR__ . "/../../../resources" . $sep . 'xml';
        $configuracao->setDiretorioXML($pathXml);
        $configuracao->setAmbiente(AmbienteEnum::HOMOLOGACAO);
        return $configuracao;
    }

    protected function getServico($codigoMunicipio){
        $servicoDTO = new ServicoDTO();
        $servicoDTO->setValor('1.00');
        $servicoDTO->setValorPis('0.00');
        $servicoDTO->setValorCofins('0.00');
        $servicoDTO->setValorInss('0.00');
        $servicoDTO->setValorIr('0.00');
        $servicoDTO->setValorCsll('0.00');
        $servicoDTO->setIssRetido(SimNaoEnum::NAO);
        $servicoDTO->setAliquota('3.00');
        $servicoDTO->setValorIss('0.03');
        $servicoDTO->setValorLiquidoNfse('1.00');
        $servicoDTO->setValorDescontoIncondicionado('0.00');
        $servicoDTO->setValorDescontoCondicionado('0.00');
        $servicoDTO->setCodigoCnae('04751201');
        $servicoDTO->setCodigoTributacaoMunicipio('80200188');
        $servicoDTO->setItemListaServico('8.02');
        $servicoDTO->setDiscriminacao('Manutencao de Software');
        $servicoDTO->setCodigoMunicipioPrestacaoServico($codigoMunicipio);
        return $servicoDTO;
    }

    protected function getPrestador($codigoMunicipio){
        $prestador = new PessoaDTO();
        $prestador->setDocumento("99999999999999");
        $prestador->setInscricaoMunicipal("99999999999");
        $prestador->setSimplesNacional(SimNaoEnum::SIM);
        $prestador->setIncentivadorCultural(SimNaoEnum::NAO);
        $prestador->setCodigoMunicipio($codigoMunicipio);
        return $prestador;
    }

    protected function getTomador($codigoMunicipio){
        $tomador = new PessoaDTO();
        $tomador->setDocumento("21775396843");
        $tomador->setRazaoSocial("Cliente de Teste");
        $tomador->setEndereco("Rua de Teste");
        $tomador->setNumero("99");
        $tomador->setComplemento("Apto 1");
        $tomador->setBairro("Centro");
        $tomador->setCodigoMunicipio($codigoMunicipio);
        $tomador->setUf("MG");
        $tomador->setCep("31340090");
        return $tomador;
    }

    protected function getRps($numero, $serie){
        $rps = new RpsDTO();
        $rps->setNumero($numero);
        $rps->setSerie($serie);
        $rps->setTipo(TipoRpsEnum::RPS);
        $rps->setDataEmissao($this->getDatetimeNow());//date("H:m:s"));
        $rps->setStatus(StatusRpsEnum::NORMAL);
        $rps->setNaturezaOperacao(NaturezaOperacaoEnum::TRIBUTACAO_NO_MUNICIPIO);
        $rps->setRegimeEspecialTributacao(RegimeEspecialTributacaoEnum::ME_EPP_SIMPLES_NACIONAL);
        return $rps;
    }

    function getDatetimeNow() {
        $tz_object = new \DateTimeZone('Brazil/East');
        $datetime = new \DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\Th:i:s');
    }
}