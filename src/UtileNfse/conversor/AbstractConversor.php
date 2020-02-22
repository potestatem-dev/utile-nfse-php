<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 09/09/2016
 * Time: 10:58
 */

namespace UtileNfse\conversor;


use NFePHP\Common\Certificate;
use NFePHP\Common\Signer;

abstract class AbstractConversor
{
    protected $chavePublica;
    protected $chavePrivada;
    protected $chave;
    protected $nomeCertificadoPfx;
    protected $senhaCertificado;
    protected $diretorioCertificados;
    protected $requisicaoDTO;
    protected $X509Certificate;
    protected $urlDsig = 'http://www.w3.org/2000/09/xmldsig#';
    protected $urlCanonMeth = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    protected $urlSigMeth = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    protected $urlTransfMeth_1 = 'http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    protected $urlTransfMeth_2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    protected $urlDigestMeth = 'http://www.w3.org/2000/09/xmldsig#sha1';
    protected $certDaysToExpire;
    protected $xmlns;
    protected $algorithm = OPENSSL_ALGO_SHA1;
    protected $canonical = [true,false,null,null];

    protected function carregarCertificado()
    {
        $x509CertData = array();
        $this->chavePrivada = $this->diretorioCertificados . DIRECTORY_SEPARATOR . str_replace('.pfx', '_priKEY.pem', $this->nomeCertificadoPfx);
        $this->chavePublica = $this->diretorioCertificados . DIRECTORY_SEPARATOR . str_replace('.pfx', '_pubKEY.pem', $this->nomeCertificadoPfx);
        $this->chave = $this->diretorioCertificados . DIRECTORY_SEPARATOR . str_replace('.pfx', '.pem', $this->nomeCertificadoPfx);


        if (!openssl_pkcs12_read(file_get_contents($this->diretorioCertificados . DIRECTORY_SEPARATOR . $this->nomeCertificadoPfx ), $x509CertData, $this->senhaCertificado)) {
            throw new \Exception('Certificado não pode ser lido. O arquivo esta corrompido ou em formato invalido.');
        }

        $this->X509Certificate = $this->X509Certificate = preg_replace("/[\n]/", '', preg_replace( '/\-\-\-\-\-[A-Z]+ CERTIFICATE\-\-\-\-\-/', '', $x509CertData['cert']));

        if (!$this->validarCertificado($x509CertData['cert'])) {
            return false;
        }

        if (!is_dir( $this->diretorioCertificados)) {
            if (!mkdir( $this->diretorioCertificados, 0777)) {
                throw new \Exception('Falha ao criar o diretorio ' . $this->diretorioCertificados);
            }
        }

        if (!file_exists($this->chavePrivada)) {
            if (!file_put_contents($this->chavePrivada, $x509CertData['pkey'])) {
                throw new \Exception('Falha ao criar o arquivo ' . $this->chavePrivada);
            }
        }

        if (!file_exists($this->chavePublica)) {
            if (!file_put_contents($this->chavePublica, $x509CertData['cert'])) {
                throw new \Exception('Falha ao criar o arquivo ' . $this->chavePublica);
            }
        }

        if (!file_exists($this->chave)) {
            if (!file_put_contents($this->chave, $x509CertData['cert'] . $x509CertData['pkey'])) {
                throw new \Exception('Falha ao criar o arquivo ' . $this->chave);
            }
        }

        return true;
    }

    public function getChave(){
        return $this->chave;
    }

    public function getSenha(){
        return $this->senhaCertificado;
    }

    protected function validarCertificado($cert)
    {
        $data = openssl_x509_read($cert);
        $certData = openssl_x509_parse($data);
        $certValidDate = gmmktime(0, 0, 0, substr($certData['validTo'], 2, 2), substr($certData['validTo'], 4, 2), substr( $certData['validTo'], 0, 2));

        // obtem o timestamp da data de hoje
        $dHoje = gmmktime(0,0,0,date("m"),date("d"),date("Y"));

        if ($certValidDate < time()){
            error_log( __METHOD__ . ': Certificado expirado em ' . date('Y-m-d', $certValidDate));
            return false;
        }

        //diferença em segundos entre os timestamp
        $diferenca = $certValidDate - $dHoje;

        // convertendo para dias
        $diferenca = round($diferenca /(60*60*24),0);
        //carregando a propriedade
        $this->certDaysToExpire = $diferenca;

        return true;
    }

    protected function assinarXMLLib($xmlString, $tagName = false, $rootName = '', $secondSignature = false) {
        $certificado = Certificate::readPfx(file_get_contents($this->diretorioCertificados . DIRECTORY_SEPARATOR . $this->nomeCertificadoPfx) , $this->senhaCertificado);
        $xmlAssinado =
                Signer::sign(
                    $certificado,
                    $xmlString,
                    $tagName,
                    'Id',
                    $this->algorithm,
                    $this->canonical,
                    $rootName,
                    $secondSignature
                );
        return $xmlAssinado;
    }

    protected function assinarXML(\DOMDocument &$xmlDoc, $nodeId = false, $tagName = false, $cancelamento = false)
    {
        $docOriginal = $xmlDoc;
        $root = $xmlDoc->documentElement;
        if($nodeId && $cancelamento) {
            $nodes = $xmlDoc->getElementsByTagName('tc:InfPedidoCancelamento');
            //print_r($nodes);die;
            $root = $nodes[0];
        }

        if($tagName) {
            $root = $xmlDoc->getElementsByTagName($tagName)->item(0);
        }

        $signature = $xmlDoc->createElement('Signature');
        $signature->setAttribute("Id", "ass_lote");
        $signature->setAttribute("xmlns", $this->urlDsig);
        $root->appendChild($signature);

        // DigestValue is a base64 sha1 hash with root tag content without Signature tag
        $digestValue = base64_encode(sha1($root->C14N( true, false, null, null), true));


        $signedInfo = $xmlDoc->createElement('SignedInfo');
        $signature->appendChild($signedInfo);
        $newNode = $xmlDoc->createElement( 'CanonicalizationMethod' );
        $signedInfo->appendChild( $newNode );
        $newNode->setAttribute('Algorithm', $this->urlCanonMeth);
        $newNode = $xmlDoc->createElement('SignatureMethod');
        $signedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->urlSigMeth);
        $reference = $xmlDoc->createElement('Reference');
        $signedInfo->appendChild($reference);
        $reference->setAttribute('URI', '#lote0');
        $transforms = $xmlDoc->createElement('Transforms');
        $reference->appendChild($transforms);
        $newNode = $xmlDoc->createElement('Transform');
        $transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->urlTransfMeth_1);
        $newNode = $xmlDoc->createElement('Transform');
        $transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->urlTransfMeth_2);
        $newNode = $xmlDoc->createElement('DigestMethod');
        $reference->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->urlDigestMeth);
        $newNode = $xmlDoc->createElement('DigestValue', $digestValue);
        $reference->appendChild($newNode);
        // SignedInfo Canonicalization (Canonical XML)
        $signedInfoC14n = $signedInfo->C14N(true, false, null, null);



        // SignatureValue is a base64 SignedInfo tag content
        $signatureValue = '';
        $pkeyId = openssl_get_privatekey(file_get_contents($this->chavePrivada));
        $pubkeyid = openssl_pkey_get_public(file_get_contents($this->chavePublica));
        openssl_sign($signedInfoC14n, $signatureValue, $pkeyId, OPENSSL_ALGO_SHA1);

        $assinaturaValida = openssl_verify($signedInfoC14n, $signatureValue, $pubkeyid, OPENSSL_ALGO_SHA1);
        if (!$assinaturaValida) {
            echo "Erro na assinatura digital. " . openssl_error_string();
            //die;
        }

        /*var_dump($assinaturaValida);
        echo "Erro na assinatura digital. " . openssl_error_string() ;
        die;*/

        $newNode = $xmlDoc->createElement('SignatureValue', base64_encode($signatureValue));
        $signature->appendChild($newNode);
        $keyInfo = $xmlDoc->createElement('KeyInfo');
        $signature->appendChild($keyInfo);
        $x509Data = $xmlDoc->createElement('X509Data');
        $keyInfo->appendChild($x509Data);
        $newNode = $xmlDoc->createElement('X509Certificate', $this->X509Certificate);
        $x509Data->appendChild($newNode);

        openssl_free_key( $pkeyId );
        openssl_free_key($pubkeyid);
    }

    public function getRequisicaoDTO(){
        return $this->requisicaoDTO;
    }
}