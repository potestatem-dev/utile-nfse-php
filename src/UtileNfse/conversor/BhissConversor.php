<?php
/**
 * Created by PhpStorm.
 * User: adeli
 * Date: 28/05/2018
 * Time: 16:06
 */

namespace UtileNfse\conversor;


use UtileNfse\model\dto\LoteDTO;
use UtileNfse\model\dto\PessoaDTO;
use UtileNfse\model\dto\RequisicaoDTO;
use UtileNfse\model\dto\RpsDTO;
use UtileNfse\model\dto\ServicoDTO;

class BhissConversor extends AbstractConversor implements IConversor
{
    public function __construct(RequisicaoDTO $requisicaoDTO)
    {
        $this->prefixo = "bhiss";
        $this->xmlns = "http://www.abrasf.org.br/nfse.xsd";
        $this->requisicaoDTO = $requisicaoDTO;
        $this->senhaCertificado = $this->requisicaoDTO->getConfiguracao()->getSenhaCertificado();
        $this->diretorioCertificados = $this->requisicaoDTO->getConfiguracao()->getDiretorioCertificados();
        $this->nomeCertificadoPfx = $this->requisicaoDTO->getConfiguracao()->getNomeCertificadoPfx();
        $this->carregarCertificado();
    }

    public function criarXMLLoteRps(LoteDTO $loteDTO, \DOMDocument $docxml)
    {
        $lote = $docxml->createElement("LoteRps");
        $lote->setAttribute("versao", "1.00");
        $lote->setAttribute("Id", "lote");

        $cpfCnpj = $docxml->createElement("Cnpj", $loteDTO->getListaRps()[0]->getPrestador()->getDocumento());

        $listaRps = $docxml->createElement("ListaRps");
        foreach($loteDTO->getListaRps() as $rps){
            $rpsXML = $this->criarXMLRps($rps, $docxml);
            $listaRps->appendChild($rpsXML);
        }

        $lote->appendChild($docxml->createElement("NumeroLote", $loteDTO->getNumero()));
        $lote->appendChild($cpfCnpj);
        $lote->appendChild($docxml->createElement("InscricaoMunicipal", $loteDTO->getListaRps()[0]->getPrestador()->getInscricaoMunicipal()));
        $lote->appendChild($docxml->createElement("QuantidadeRps", count($loteDTO->getListaRps())));
        $lote->appendChild($listaRps);

        return $lote;
    }

    public function criarXMLRps(RpsDTO $rpsDTO, \DOMDocument $docxml)
    {
        try {
            $id = "rps_" . $rpsDTO->getNumero();
            $identificacaoRps = $docxml->createElement("IdentificacaoRps");
            $identificacaoRps->appendChild($docxml->createElement("Numero", $rpsDTO->getNumero()));
            $identificacaoRps->appendChild($docxml->createElement("Serie", $rpsDTO->getSerie()));
            $identificacaoRps->appendChild($docxml->createElement("Tipo", $rpsDTO->getTipo()));
            $infRps = $docxml->createElement("InfRps");
            $infRps->setAttribute("Id", $id);
            $infRps->appendChild($identificacaoRps);
            $infRps->appendChild($docxml->createElement("DataEmissao", $rpsDTO->getDataEmissao() . "T00:00:00" )); //. '.300-04:00'));
            $infRps->appendChild($docxml->createElement("NaturezaOperacao", $rpsDTO->getNaturezaOperacao()));
            $infRps->appendChild($docxml->createElement("RegimeEspecialTributacao", $rpsDTO->getRegimeEspecialTributacao()));
            $infRps->appendChild($docxml->createElement("OptanteSimplesNacional", $rpsDTO->getPrestador()->getSimplesNacional()));
            $infRps->appendChild($docxml->createElement("IncentivadorCultural",  $rpsDTO->getPrestador()->getIncentivadorCultural()));
            $infRps->appendChild($docxml->createElement("Status", $rpsDTO->getStatus()));
            $infRps->appendChild($this->criarXMLServico($rpsDTO->getServico(), $docxml));
            $infRps->appendChild($this->criarXMLPrestador($rpsDTO->getPrestador(), $docxml));
            $infRps->appendChild($this->criarXMLTomador($rpsDTO->getTomador(), $docxml));
            $rps = $docxml->createElement("Rps");
            $rps->appendChild($infRps);

        } catch(\Exception $ex){
            throw new \Exception($ex->getCode() . " - " . $ex->getMessage());
        }

        return $rps;
    }

    public function criarXMLPrestador(PessoaDTO $prestadorDTO, \DOMDocument $docxml)
    {
        $cnpj = $docxml->createElement("Cnpj", $prestadorDTO->getDocumento());
        $prestador = $docxml->createElement("Prestador");
        $prestador->appendChild($cnpj);
        $prestador->appendChild($docxml->createElement("InscricaoMunicipal", $prestadorDTO->getInscricaoMunicipal()));

        return $prestador;
    }

    public function criarXMLTomador(PessoaDTO $tomadorDTO, \DOMDocument $docxml)
    {
        $cpfCnpj = $docxml->createElement("CpfCnpj");
        if(strlen($tomadorDTO->getDocumento()) == 14){
            $cnpj = $docxml->createElement("Cnpj", $tomadorDTO->getDocumento());
            $cpfCnpj->appendChild($cnpj);
        } else {
            $cpf = $docxml->createElement("Cpf", $tomadorDTO->getDocumento());
            $cpfCnpj->appendChild($cpf);
        }

        $tomador = $docxml->createElement("Tomador");
        $identificacaoTomador = $docxml->createElement("IdentificacaoTomador");
        $identificacaoTomador->appendChild($cpfCnpj);
        $tomador->appendChild($identificacaoTomador);
        $tomador->appendChild($docxml->createElement("RazaoSocial", $tomadorDTO->getRazaoSocial()));

        $endereco = $docxml->createElement("Endereco");
        $endereco->appendChild($docxml->createElement("Endereco", $tomadorDTO->getEndereco()));
        $endereco->appendChild($docxml->createElement("Numero", $tomadorDTO->getNumero()));
        $endereco->appendChild($docxml->createElement("Complemento", $tomadorDTO->getComplemento()));
        $endereco->appendChild($docxml->createElement("Bairro", $tomadorDTO->getBairro()));
        $cidade = $tomadorDTO->getCodigoMunicipio();
        $endereco->appendChild($docxml->createElement("CodigoMunicipio", $cidade));
        $endereco->appendChild($docxml->createElement("Uf", $tomadorDTO->getUf()));
        $endereco->appendChild($docxml->createElement("Cep", $tomadorDTO->getCep()));

        $tomador->appendChild($endereco);

        return $tomador;
    }

    public function criarXMLServico(ServicoDTO $servicoDTO, \DOMDocument $docxml)
    {
        $servico = $docxml->createElement("Servico");
        $valores = $docxml->createElement("Valores");
        $valores->appendChild($docxml->createElement("ValorServicos", $servicoDTO->getValor()));
        $valores->appendChild($docxml->createElement("ValorDeducoes", "0.00"));
        $valores->appendChild($docxml->createElement("ValorPis", $servicoDTO->getValorPis()));
        $valores->appendChild($docxml->createElement("ValorCofins", $servicoDTO->getValorCofins()));
        $valores->appendChild($docxml->createElement("ValorInss", $servicoDTO->getValorInss()));
        $valores->appendChild($docxml->createElement("ValorIr", $servicoDTO->getValorIr()));
        $valores->appendChild($docxml->createElement("ValorCsll", $servicoDTO->getValorCsll()));
        $valores->appendChild($docxml->createElement("IssRetido", $servicoDTO->getIssRetido()));
        $valores->appendChild($docxml->createElement("ValorIss", $servicoDTO->getValorIss()));
        $valores->appendChild($docxml->createElement("OutrasRetencoes", "0.00"));
        $valores->appendChild($docxml->createElement("Aliquota", $servicoDTO->getAliquota()));
        $valores->appendChild($docxml->createElement("DescontoIncondicionado", $servicoDTO->getValorDescontoIncondicionado()));
        $valores->appendChild($docxml->createElement("DescontoCondicionado", $servicoDTO->getValorDescontoCondicionado()));
        $servico->appendChild($valores);
        $servico->appendChild($docxml->createElement("ItemListaServico", $servicoDTO->getItemListaServico()));
        $servico->appendChild($docxml->createElement("CodigoTributacaoMunicipio", $servicoDTO->getCodigoTributacaoMunicipio()));
        $servico->appendChild($docxml->createElement("Discriminacao", $servicoDTO->getDiscriminacao()));
        $servico->appendChild($docxml->createElement("CodigoMunicipio", $servicoDTO->getCodigoMunicipioPrestacaoServico()));

        return $servico;
    }

    public function criarXMLEnvioLote()
    {
        $docxml = new \DOMDocument('1.0', 'UTF-8');
        $docxml->formatOutput = true;
        $enviarLoteRpsEnvio = $docxml->createElement("EnviarLoteRpsEnvio");
        $enviarLoteRpsEnvio->setAttribute("xmlns", $this->xmlns);
        $enviarLoteRpsEnvio->setAttribute("Id", "lote0");
        $enviarLoteRpsEnvio->appendChild($this->criarXMLLoteRps($this->requisicaoDTO->getLote(), $docxml));
        $docxml->appendChild($enviarLoteRpsEnvio);
        $xmlRpsAssinado = $this->assinarXMLLib($docxml->saveXML(), 'InfRps', 'Rps');
        $xmlLoteAssinado = $this->assinarXMLLib($xmlRpsAssinado, 'LoteRps', 'EnviarLoteRpsEnvio', true);
        $docxml->loadXML($xmlLoteAssinado);

        return $docxml;
    }

    public function criarXMLConsultaSituacaoLote(PessoaDTO $prestadorDTO)
    {
        $this->xmlns = "http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_consultar_situacao_lote_rps_envio.xsd";
        $docxml = new \DOMDocument('1.0', 'UTF-8');
        $docxml->formatOutput = true;
        $consultarLoteRpsEnvio = $docxml->createElement("ConsultarSituacaoLoteRpsEnvio");
        $consultarLoteRpsEnvio->appendChild($this->criarXMLPrestadorConsulta($prestadorDTO, $docxml));
        $consultarLoteRpsEnvio->appendChild($docxml->createElement("Protocolo", $this->requisicaoDTO->getLote()->getProtocolo()));
        $docxml->appendChild($consultarLoteRpsEnvio);

        return $docxml;
    }

    public function criarXMLPrestadorConsulta(PessoaDTO $prestadorDTO, \DOMDocument $docxml)
    {
        $cnpj = $docxml->createElement("Cnpj", $prestadorDTO->getDocumento());

        $prestador = $docxml->createElement("Prestador");
        $prestador->appendChild($cnpj);
        $prestador->appendChild($docxml->createElement("InscricaoMunicipal", $prestadorDTO->getInscricaoMunicipal()));

        return $prestador;
    }

    public function criarXMLConsultaLote(PessoaDTO $prestadorDTO)
    {
        $docxml = new \DOMDocument('1.0', 'UTF-8');
        $docxml->formatOutput = true;
        $consultarLoteRpsEnvio = $docxml->createElement("ConsultarLoteRpsEnvio");
        $consultarLoteRpsEnvio->setAttribute("xmlns", $this->xmlns);
        $consultarLoteRpsEnvio->appendChild($this->criarXMLPrestadorConsulta($prestadorDTO, $docxml));
        $consultarLoteRpsEnvio->appendChild($docxml->createElement("Protocolo", $this->requisicaoDTO->getLote()->getProtocolo()));
        $docxml->appendChild($consultarLoteRpsEnvio);

        return $docxml;
    }

    public function assinarArquivo(\DOMDocument $docxml, $nodeId = false)
    {
        $this->carregarCertificado();
        $this->assinarXML($docxml, $nodeId);
    }

    public function criarXMLNfse(\SimpleXMLElement $paramNfse)
    {
        $this->xmlns = "http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_consultar_lote_rps_envio.xsd";
        $docxml = new \DOMDocument('1.0', 'UTF-8');
        $docxml->formatOutput = true;
        //$docxml->setAttribute("xmlns", $this->xmlns);
        //$docxml->setAttribute("xmlns:tc", $this->xmlnstc);
        //$docxml->loadXML($paramNfse->, LIBXML_NOERROR);
        $compNfse = $docxml->createElement("CompNfse");
        $compNfse->setAttribute("xmlns", $this->xmlns);
        //$compNfse->setAttribute("xmlns:sg", $this->urlDsig);
        $nfseImport = dom_import_simplexml($paramNfse);
        $nfse = $docxml->importNode($nfseImport);
        $compNfse->appendChild($nfse);
        $docxml->appendChild($compNfse);
        return $docxml;
    }
}