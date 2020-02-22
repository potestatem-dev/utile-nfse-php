<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 03/08/2016
 * Time: 23:36
 */

namespace UtileNfse\service;


use UtileNfse\conversor\IConversor;
use UtileNfse\model\dto\LoteDTO;
use UtileNfse\model\dto\NfseDTO;
use UtileNfse\model\dto\RetornoDTO;

class IssnetService extends AbstractService implements IService
{
    private $tc;

    public function __construct(IConversor $conversor)
    {
        $this->prefixo = "issnet";
        $this->xmlnstc = "http://www.issnetonline.com.br/webserviceabrasf/vsd/tipos_complexos.xsd";
        $this->tc = "tc";
        $this->conversor = $conversor;
    }

    public function enviarLoteRps($numeroLote)
    {
        try {
            $sep = DIRECTORY_SEPARATOR;
            $path = $this->conversor->getRequisicaoDTO()->getConfiguracao()->getDiretorioXML() . $sep . $this->prefixo;
            $pathRemessa = $path . $sep . "remessas";
            $pathRetorno = $path . $sep . "retornos";

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $docxml = $this->conversor->criarXMLEnvioLote();
            $docxml->save($pathRemessa . $sep . "envio_lote_" . str_pad($numeroLote, 5, '0', STR_PAD_LEFT) . ".xml");
            $this->inicarSOAP();
            $return = $this->enviarLoteRpsSOAP($docxml);

            $xmlDoc = new \DOMDocument( '1.0', 'utf-8' );
            $xmlDoc->preservWhiteSpace = false;
            $xmlDoc->formatOutput = true;
            $xmlDoc->loadXML($return->asXML());
            $xmlDoc->save($pathRetorno . $sep . "retorno_envio_lote_" . str_pad($numeroLote, 5, '0', STR_PAD_LEFT) . ".xml");

            $loteDTO = new LoteDTO();
            $loteDTO->setDataProtocolo($return->DataRecebimento);
            $loteDTO->setProtocolo($return->Protocolo);

            $retornoDTO = new RetornoDTO();
            $retornoDTO->setLote($loteDTO);
            return $retornoDTO;
        } catch(\Exception $ex){
            throw new \Exception($ex->getCode() . " - " . $ex->getMessage());
        }
    }

    public function consultarSituacaoLoteRps($numeroLote)
    {
        try {
            $sep = DIRECTORY_SEPARATOR;
            $path = $this->conversor->getRequisicaoDTO()->getConfiguracao()->getDiretorioXML() . $sep . $this->prefixo;
            $pathRemessa = $path . $sep . "remessas";
            $pathRetorno = $path . $sep . "retornos";
            $prestador = $this->conversor->getRequisicaoDTO()->getPrestador();


            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $docxml = $this->conversor->criarXMLConsultaSituacaoLote($prestador);
            $docxml->save($pathRemessa . $sep . "envio_consulta_situacao_lote_" . str_pad($numeroLote, 5, '0', STR_PAD_LEFT) . ".xml");

            $this->inicarSOAP();
            $return = $this->consultarSituacaoLoteRpsSOAP($docxml);
            //var_dump($return);die;

            $xmlDoc = new \DOMDocument( '1.0', 'UTF-8' );
            $xmlDoc->preservWhiteSpace = false;
            $xmlDoc->formatOutput = true;
            $xmlDoc->loadXML($return->asXML());
            $xmlDoc->save($pathRetorno . $sep . "retorno_consulta_situacao_lote_" . str_pad($numeroLote, 5, '0', STR_PAD_LEFT) . ".xml");

            $retornoDTO = new RetornoDTO();

            if(property_exists($return, 'ListaMensagemRetorno') && isset($return->ListaMensagemRetorno)){
                    if(isset($return->ListaMensagemRetorno->MensagemRetorno)){
                        $mensagem  = $return->ListaMensagemRetorno->MensagemRetorno->Codigo;
                        $mensagem .= " - ". $return->ListaMensagemRetorno->MensagemRetorno->Mensagem;
                        $mensagem .= " - ". $return->ListaMensagemRetorno->MensagemRetorno->Correcao;
                        $retornoDTO->setListaErros($mensagem);
                    }
            } else {
                $loteDTO = new LoteDTO();
                $loteDTO->setSituacao($return->Situacao);
                $retornoDTO->setLote($loteDTO);
            }

            return $retornoDTO;
        } catch(\Exception $ex){
            throw new \Exception($ex->getCode() . " - " . $ex->getMessage());
        }
    }

    public function consultarLoteRps($numeroLote)
    {
        try {
            $sep = DIRECTORY_SEPARATOR;
            $path = $this->conversor->getRequisicaoDTO()->getConfiguracao()->getDiretorioXML() . $sep . $this->prefixo;
            $pathRemessa = $path . $sep . "remessas";
            $pathRetorno = $path . $sep . "retornos";
            $pathNfse = $path . $sep . "nfse";
            $prestador = $this->conversor->getRequisicaoDTO()->getPrestador();

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            if (!file_exists($pathNfse)) {
                mkdir($pathNfse, 0777, true);
            }

            $docxml = $this->conversor->criarXMLConsultaLote($prestador);
            $docxml->save($pathRemessa . $sep . "envio_consulta_lote_" . str_pad($numeroLote, 5, '0', STR_PAD_LEFT) . ".xml");

            $this->inicarSOAP();
            $return = $this->consultarLoteRpsSOAP($docxml);
            $nomeArquivo = $pathRetorno . $sep . "retorno_consulta_lote_" . str_pad($numeroLote, 5, '0', STR_PAD_LEFT) . ".xml";

            $retornoDTO = new RetornoDTO();

            if(property_exists($return, 'ListaMensagemRetorno') && isset($return->ListaMensagemRetorno)){
                if(isset($return->ListaMensagemRetorno->MensagemRetorno)){
                    $mensagem  = $return->ListaMensagemRetorno->MensagemRetorno->Codigo;
                    $mensagem .= " - ". $return->ListaMensagemRetorno->MensagemRetorno->Mensagem;
                    $mensagem .= " - ". $return->ListaMensagemRetorno->MensagemRetorno->Correcao;
                    $retornoDTO->setListaErros($mensagem);
                }
            } else if(property_exists($return, 'ListaNfse') && isset($return->ListaNfse)){
                $tagNfse = $return->ListaNfse[0]->CompNfse[0]->xpath("tc:Nfse")[0];
                $numeroNfse = $tagNfse->xpath("tc:InfNfse")[0]->xpath("tc:Numero")[0];
                $codigoVerificacao = $tagNfse->xpath("tc:InfNfse")[0]->xpath("tc:CodigoVerificacao")[0];
                $dataEmissao = $tagNfse->xpath("tc:InfNfse")[0]->xpath("tc:DataEmissao")[0];

                $notaDTO = new NfseDTO();
                $notaDTO->setNumero($numeroNfse);
                $notaDTO->setCodigoVerificacao($codigoVerificacao);
                $notaDTO->setDataEmissao($dataEmissao);
                $listaNota = array();
                $listaNota[] = $notaDTO;
                $retornoDTO->setListaNfse($listaNota);

                $numeroNfse = str_pad($numeroNfse, 10, '0', STR_PAD_LEFT);
                $nomeArquivo = $pathNfse . $sep . $numeroNfse . "-nfse.xml";
            }

            $xmlDoc = new \DOMDocument( '1.0', 'UTF-8' );
            $xmlDoc->preservWhiteSpace = false;
            $xmlDoc->formatOutput = true;
            $xmlDoc->loadXML($return->asXML());
            $xmlDoc->save($nomeArquivo);

            return $retornoDTO;
        } catch(\Exception $ex){
            throw new \Exception($ex->getCode() . " - " . $ex->getMessage());
        }
    }

    public function cancelarNfse()
    {
        $sep = DIRECTORY_SEPARATOR;
        $path = $this->conversor->getRequisicaoDTO()->getConfiguracao()->getDiretorioXML() . $sep . $this->prefixo;
        $pathRemessa = $path . $sep . "remessas";
        $pathRetorno = $path . $sep . "retornos";

        $docxml = $this->conversor->criarXMLCancelamento();
        $numeroNfse = str_pad($this->conversor->getRequisicaoDTO()->getNfse()->getNumero(), 10, '0', STR_PAD_LEFT);
        $docxml->save($pathRemessa . $sep . "cancelar_nfse_envio_" . $numeroNfse . ".xml");
        $this->inicarSOAP();
        $return = $this->cancelarNfseSOAP($docxml);

        $xmlDoc = new \DOMDocument( '1.0', 'UTF-8' );
        $xmlDoc->preservWhiteSpace = false;
        $xmlDoc->formatOutput = true;
        $xmlDoc->loadXML($return->asXML());
        $xmlDoc->save($pathRetorno . $sep . "cancelar_nfse_retorno_" . $numeroNfse . ".xml");

        $retornoDTO = new RetornoDTO();

        if(property_exists($return, 'ListaMensagemRetorno') && isset($return->ListaMensagemRetorno)){
            if(isset($return->ListaMensagemRetorno->MensagemRetorno)){
                $mensagem  = $return->ListaMensagemRetorno->MensagemRetorno->Codigo;
                $mensagem .= " - ". $return->ListaMensagemRetorno->MensagemRetorno->Mensagem;
                $mensagem .= " - ". $return->ListaMensagemRetorno->MensagemRetorno->Correcao;
                $retornoDTO->setListaErros($mensagem);
            }
        } else {
            print_r($return);die;
        }

        return $retornoDTO;
    }

    public function consultarLinkImpressaoNfse()
    {
        $sep = DIRECTORY_SEPARATOR;
        $path = $this->conversor->getRequisicaoDTO()->getConfiguracao()->getDiretorioXML() . $sep . $this->prefixo;
        $pathRemessa = $path . $sep . "remessas";
        $pathRetorno = $path . $sep . "retornos";

        $docxml = $this->conversor->criarXMLConsultaLinkImpressaoNfse();
        $numeroNfse = str_pad($this->conversor->getRequisicaoDTO()->getNfse()->getNumero(), 10, '0', STR_PAD_LEFT);
        $docxml->save($pathRemessa . $sep . "consultar_link_impressao_nfse_" . $numeroNfse . ".xml");
        $this->inicarSOAP();
        $return = $this->consultarLinkImpressaoNfseSOAP($docxml);

        $xmlDoc = new \DOMDocument( '1.0', 'UTF-8' );
        $xmlDoc->preservWhiteSpace = false;
        $xmlDoc->formatOutput = true;
        $xmlDoc->loadXML($return->asXML());
        $xmlDoc->save($pathRetorno . $sep . "consultar_link_impressao_nfse__retorno_" . $numeroNfse . ".xml");
        //print_r($return);die;

        $retornoDTO = new RetornoDTO();

        if(property_exists($return, 'ListaMensagemRetorno') && isset($return->ListaMensagemRetorno)){
            if(isset($return->ListaMensagemRetorno->MensagemRetorno)){
                $mensagem  = $return->ListaMensagemRetorno->MensagemRetorno->Codigo;
                $mensagem .= " - ". $return->ListaMensagemRetorno->MensagemRetorno->Mensagem;
                $mensagem .= " - ". $return->ListaMensagemRetorno->MensagemRetorno->Correcao;
                $retornoDTO->setListaErros($mensagem);
            }
        } else {
            $retornoDTO->setUrlImpressaoNfse($return->UrlVisualizacao);
        }

        return $retornoDTO;
    }
}