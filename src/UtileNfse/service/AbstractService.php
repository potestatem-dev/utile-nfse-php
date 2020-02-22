<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 03/08/2016
 * Time: 23:34
 */

namespace UtileNfse\service;


abstract class AbstractService
{
    protected $conversor;
    protected $conexaoSOAP;

    protected function inicarSOAP()
    {
        //versão do SOAP
        //$soapver = SOAP_1_2;
        $soapver = SOAP_1_1;
        $wsdl = (string)$this->conversor->getRequisicaoDTO()->getConfiguracao()->getUrlWebservice();
        $wsdl .= '?WSDL';
        ///echo $wsdl;die;

        $params = array(
            'local_cert' => $this->conversor->getChave(),
            'passphrase' => $this->conversor->getSenha(),
            'connection_timeout' => 300,
            'encoding' => 'UTF-8',
            'verifypeer'    => false,
            'verifyhost'    => false,
            'soap_version'  => $soapver,
            'trace'         => true,
            'cache_wsdl' => WSDL_CACHE_NONE
        );

        try {
            $this->conexaoSOAP = new \SoapClient( $wsdl, $params );
        } catch (\SoapFault $e ) {
            echo "erro de conexão soap. Tente novamente mais tarde !<br>\n";
            error_log( 'Exception: ' . $e->getMessage() );
            echo $e->getMessage();
            return false;
        }
    }

    protected function enviarLoteRpsSOAP($xmlDoc)
    {
        $this->inicarSOAP();
        //echo $xmlDoc->saveXML();die;

        $params = array(
            'nfseCabecMsg' => '<cabecalho xmlns="http://www.abrasf.org.br/nfse.xsd"	versao="1.00"><versaoDados>1.00</versaoDados></cabecalho>',
            'nfseDadosMsg' => iconv('windows-1252', 'UTF-8', $xmlDoc->saveXML())
        );

        try {
            $result = $this->conexaoSOAP->RecepcionarLoteRps($params);
            print_r($result);
        } catch( SoapFault $e ) {
            error_log( 'Exception: ' . $e->getMessage() );
            echo "erro soap ".$e->getMessage();
            //print_r($this->conexaoSOAP);
            return false;
        }

        return new \SimpleXMLElement($result->outputXML);
    }

    protected function consultarSituacaoLoteRpsSOAP($xmlDoc)
    {
        $this->inicarSOAP();

        $params = array(
            'xml' => $xmlDoc->saveXML()
        );

        try {
            $result = $this->conexaoSOAP->ConsultarSituacaoLoteRPS($params);
        } catch( SoapFault $e ) {
            error_log( 'Exception: ' . $e->getMessage() );
            echo "erro soap ".$e->getMessage();
            print_r($this->conexaoSOAP);
            return false;
        }

        return new \SimpleXMLElement($result->ConsultarSituacaoLoteRPSResult);
    }

    protected function consultarLoteRpsSOAP($xmlDoc)
    {
        $this->inicarSOAP();

        $params = array(
            'xml' => $xmlDoc->saveXML()
        );

        try {
            $result = $this->conexaoSOAP->ConsultarLoteRps($params);
        } catch(SoapFault $e) {
            error_log( 'Exception: ' . $e->getMessage() );
            echo "erro soap ".$e->getMessage();
            print_r($this->conexaoSOAP);
            return false;
        }

        return new \SimpleXMLElement($result->ConsultarLoteRpsResult);
    }

    protected function cancelarNfseSOAP($xmlDoc)
    {
        $this->inicarSOAP();

        $params = array(
            'xml' => $xmlDoc->saveXML()
        );

        try {
            $result = $this->conexaoSOAP->CancelarNfse($params);
        } catch(SoapFault $e) {
            error_log( 'Exception: ' . $e->getMessage() );
            echo "erro soap ".$e->getMessage();
            print_r($this->conexaoSOAP);
            return false;
        }

        return new \SimpleXMLElement($result->CancelarNfseResult);
    }

    protected function consultarLinkImpressaoNfseSOAP($xmlDoc)
    {
        $this->inicarSOAP();

        $params = array(
            'xml' => $xmlDoc->saveXML()
        );

        try {
            $result = $this->conexaoSOAP->ConsultarUrlVisualizacaoNfse($params);
        } catch(SoapFault $e) {
            error_log( 'Exception: ' . $e->getMessage() );
            echo "erro soap ".$e->getMessage();
            print_r($this->conexaoSOAP);
            return false;
        }

        return new \SimpleXMLElement($result->ConsultarUrlVisualizacaoNfseResult);
    }
}