<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 23/08/2016
 * Time: 16:52
 */

namespace UtileNfse\conversor;


use UtileNfse\model\dto\LoteDTO;
use UtileNfse\model\dto\PessoaDTO;
use UtileNfse\model\dto\RequisicaoDTO;
use UtileNfse\model\dto\RpsDTO;
use UtileNfse\model\dto\ServicoDTO;

class IssnetConversor extends AbstractConversor implements IConversor
{
    private $tc;
    private $xmlns;
    private $xmlnstc;
    private $xmlnsts;

    public function __construct(RequisicaoDTO $requisicaoDTO)
    {
        $this->prefixo = "issnet";
        $this->xmlnstc = "http://www.issnetonline.com.br/webserviceabrasf/vsd/tipos_complexos.xsd";
        $this->xmlnsts = "http://www.issnetonline.com.br/webserviceabrasf/vsd/tipos_simples.xsd";
        $this->tc = "tc";
        $this->requisicaoDTO = $requisicaoDTO;
        $this->senhaCertificado = $this->requisicaoDTO->getConfiguracao()->getSenhaCertificado();
        $this->diretorioCertificados = $this->requisicaoDTO->getConfiguracao()->getDiretorioCertificados();
        $this->nomeCertificadoPfx = $this->requisicaoDTO->getConfiguracao()->getNomeCertificadoPfx();
    }

    public function criarXMLLoteRps(LoteDTO $loteDTO, \DOMDocument $docxml)
    {
        $lote = $docxml->createElement("LoteRps");

        $cpfCnpj = $docxml->createElement("$this->tc:CpfCnpj");
        $cnpj = $docxml->createElement("$this->tc:Cnpj", $loteDTO->getListaRps()[0]->getPrestador()->getDocumento());
        $cpfCnpj->appendChild($cnpj);

        $listaRps = $docxml->createElement("$this->tc:ListaRps");
        foreach($loteDTO->getListaRps() as $rps){
            $rpsXML = $this->criarXMLRps($rps, $docxml);
            $listaRps->appendChild($rpsXML);
        }

        $lote->appendChild($docxml->createElement("$this->tc:NumeroLote", $loteDTO->getNumero()));
        $lote->appendChild($cpfCnpj);
        $lote->appendChild($docxml->createElement("$this->tc:InscricaoMunicipal", $loteDTO->getListaRps()[0]->getPrestador()->getInscricaoMunicipal()));
        $lote->appendChild($docxml->createElement("$this->tc:QuantidadeRps", count($loteDTO->getListaRps())));
        $lote->appendChild($listaRps);

        return $lote;
    }

    public function criarXMLRps(RpsDTO $rpsDTO, \DOMDocument $docxml)
    {
        try{
            $identificacaoRps = $docxml->createElement("$this->tc:IdentificacaoRps");
            $identificacaoRps->appendChild($docxml->createElement("$this->tc:Numero", $rpsDTO->getNumero()));
            $identificacaoRps->appendChild($docxml->createElement("$this->tc:Serie", $rpsDTO->getSerie()));
            $identificacaoRps->appendChild($docxml->createElement("$this->tc:Tipo", $rpsDTO->getTipo()));
            $infRps = $docxml->createElement("$this->tc:InfRps");
            $infRps->appendChild($identificacaoRps);
            $infRps->appendChild($docxml->createElement("$this->tc:DataEmissao", $rpsDTO->getDataEmissao()));
            $infRps->appendChild($docxml->createElement("$this->tc:NaturezaOperacao", $rpsDTO->getNaturezaOperacao()));
            $infRps->appendChild($docxml->createElement("$this->tc:OptanteSimplesNacional", $rpsDTO->getPrestador()->getSimplesNacional()));
            $infRps->appendChild($docxml->createElement("$this->tc:IncentivadorCultural",  $rpsDTO->getPrestador()->getIncentivadorCultural()));
            $infRps->appendChild($docxml->createElement("$this->tc:Status", $rpsDTO->getStatus()));
            $infRps->appendChild($docxml->createElement("$this->tc:RegimeEspecialTributacao", $rpsDTO->getRegimeEspecialTributacao()));
            $infRps->appendChild($this->criarXMLServico($rpsDTO->getServico(), $docxml));
            $infRps->appendChild($this->criarXMLPrestador($rpsDTO->getPrestador(), $docxml));
            $infRps->appendChild($this->criarXMLTomador($rpsDTO->getTomador(), $docxml));

            $rps = $docxml->createElement("$this->tc:Rps");
            $rps->appendChild($infRps);

        } catch(\Exception $ex){
            throw new \Exception($ex->getCode() . " - " . $ex->getMessage());
        }

        return $rps;
    }

    public function criarXMLPrestador(PessoaDTO $prestadorDTO, \DOMDocument $docxml)
    {
        $cpfCnpj = $docxml->createElement("$this->tc:CpfCnpj");
        $cnpj = $docxml->createElement("$this->tc:Cnpj", $prestadorDTO->getDocumento());
        $cpfCnpj->appendChild($cnpj);

        $prestador = $docxml->createElement("$this->tc:Prestador");
        $prestador->appendChild($cpfCnpj);
        $prestador->appendChild($docxml->createElement("$this->tc:InscricaoMunicipal", $prestadorDTO->getInscricaoMunicipal()));

        return $prestador;
    }

    public function criarXMLConsultaSituacaoLote(PessoaDTO $prestadorDTO){
        $this->xmlns = "http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_consultar_situacao_lote_rps_envio.xsd";
        $docxml = new \DOMDocument('1.0', 'UTF-8');
        $docxml->formatOutput = true;
        $consultarLoteRpsEnvio = $docxml->createElement("ConsultarSituacaoLoteRpsEnvio");
        $consultarLoteRpsEnvio->setAttribute("xmlns", $this->xmlns);
        $consultarLoteRpsEnvio->setAttribute("xmlns:tc", $this->xmlnstc);
        $consultarLoteRpsEnvio->appendChild($this->criarXMLPrestadorConsulta($prestadorDTO, $docxml));
        $consultarLoteRpsEnvio->appendChild($docxml->createElement("Protocolo", $this->requisicaoDTO->getLote()->getProtocolo()));
        $docxml->appendChild($consultarLoteRpsEnvio);

        return $docxml;
    }

    public function criarXMLConsultaLote(PessoaDTO $prestadorDTO){
        $this->xmlns = "http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_consultar_lote_rps_envio.xsd";
        $docxml = new \DOMDocument('1.0', 'UTF-8');
        $docxml->formatOutput = true;
        $consultarLoteRpsEnvio = $docxml->createElement("ConsultarLoteRpsEnvio");
        $consultarLoteRpsEnvio->setAttribute("xmlns", $this->xmlns);
        $consultarLoteRpsEnvio->setAttribute("xmlns:tc", $this->xmlnstc);
        $consultarLoteRpsEnvio->appendChild($this->criarXMLPrestadorConsulta($prestadorDTO, $docxml));
        $consultarLoteRpsEnvio->appendChild($docxml->createElement("Protocolo", $this->requisicaoDTO->getLote()->getProtocolo()));
        $docxml->appendChild($consultarLoteRpsEnvio);

        return $docxml;
    }

    public function criarXMLNfse(\SimpleXMLElement $paramNfse){
        $this->xmlns = "http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_consultar_lote_rps_envio.xsd";
        $docxml = new \DOMDocument('1.0', 'UTF-8');
        $docxml->formatOutput = true;
        //$docxml->setAttribute("xmlns", $this->xmlns);
        //$docxml->setAttribute("xmlns:tc", $this->xmlnstc);
        //$docxml->loadXML($paramNfse->, LIBXML_NOERROR);
        $compNfse = $docxml->createElement("CompNfse");
        $compNfse->setAttribute("xmlns", $this->xmlns);
        //$compNfse->setAttribute("xmlns:tc", $this->xmlnstc);
        $nfseImport = dom_import_simplexml($paramNfse);
        $nfse = $docxml->importNode($nfseImport);
        $compNfse->appendChild($nfse);
        $docxml->appendChild($compNfse);
        return $docxml;
    }

    public function criarXMLEnvioLote(){
        $this->xmlns = "http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_enviar_lote_rps_envio.xsd";

        $docxml = new \DOMDocument('1.0', 'UTF-8');
        $docxml->formatOutput = true;
        $enviarLoteRpsEnvio = $docxml->createElement("EnviarLoteRpsEnvio");
        $enviarLoteRpsEnvio->setAttribute("xmlns", $this->xmlns);
        $enviarLoteRpsEnvio->setAttribute("xmlns:tc", $this->xmlnstc);
        $enviarLoteRpsEnvio->appendChild($this->criarXMLLoteRps($this->requisicaoDTO->getLote(), $docxml));
        $docxml->appendChild($enviarLoteRpsEnvio);
        $this->assinarArquivo($docxml);

        return $docxml;
    }

    public function criarXMLPrestadorConsulta(PessoaDTO $prestadorDTO, \DOMDocument $docxml)
    {
        $cpfCnpj = $docxml->createElement("$this->tc:CpfCnpj");
        $cnpj = $docxml->createElement("$this->tc:Cnpj", $prestadorDTO->getDocumento());
        $cpfCnpj->appendChild($cnpj);

        $prestador = $docxml->createElement("Prestador");
        $prestador->appendChild($cpfCnpj);
        $prestador->appendChild($docxml->createElement("$this->tc:InscricaoMunicipal", $prestadorDTO->getInscricaoMunicipal()));

        return $prestador;
    }

    public function criarXMLTomador(PessoaDTO $tomadorDTO, \DOMDocument $docxml)
    {
        $cpfCnpj = $docxml->createElement("$this->tc:CpfCnpj");
        if(strlen($tomadorDTO->getDocumento()) == 14){
            $cnpj = $docxml->createElement("$this->tc:Cnpj", $tomadorDTO->getDocumento());
            $cpfCnpj->appendChild($cnpj);
        } else {
            $cpf = $docxml->createElement("$this->tc:Cpf", $tomadorDTO->getDocumento());
            $cpfCnpj->appendChild($cpf);
        }

        $tomador = $docxml->createElement("$this->tc:Tomador");
        $identificacaoTomador = $docxml->createElement("$this->tc:IdentificacaoTomador");
        $identificacaoTomador->appendChild($cpfCnpj);
        $tomador->appendChild($identificacaoTomador);
        $tomador->appendChild($docxml->createElement("$this->tc:RazaoSocial", $tomadorDTO->getRazaoSocial()));

        $endereco = $docxml->createElement("$this->tc:Endereco");
        $endereco->appendChild($docxml->createElement("$this->tc:Endereco", $tomadorDTO->getEndereco()));
        $endereco->appendChild($docxml->createElement("$this->tc:Numero", $tomadorDTO->getNumero()));
        $endereco->appendChild($docxml->createElement("$this->tc:Complemento", $tomadorDTO->getComplemento()));
        $endereco->appendChild($docxml->createElement("$this->tc:Bairro", $tomadorDTO->getBairro()));
        $cidade = $tomadorDTO->getCodigoMunicipio();
        $endereco->appendChild($docxml->createElement("$this->tc:Cidade", $cidade));
        $endereco->appendChild($docxml->createElement("$this->tc:Estado", $tomadorDTO->getUf()));
        $endereco->appendChild($docxml->createElement("$this->tc:Cep", $tomadorDTO->getCep()));

        $tomador->appendChild($endereco);

        return $tomador;
    }

    public function criarXMLServico(ServicoDTO $servicoDTO, \DOMDocument $docxml)
    {
        $servico = $docxml->createElement("$this->tc:Servico");
        $valores = $docxml->createElement("$this->tc:Valores");
        $valores->appendChild($docxml->createElement("$this->tc:ValorServicos", $servicoDTO->getValor()));
        $valores->appendChild($docxml->createElement("$this->tc:ValorPis", $servicoDTO->getValorPis()));
        $valores->appendChild($docxml->createElement("$this->tc:ValorCofins", $servicoDTO->getValorCofins()));
        $valores->appendChild($docxml->createElement("$this->tc:ValorInss", $servicoDTO->getValorInss()));
        $valores->appendChild($docxml->createElement("$this->tc:ValorIr", $servicoDTO->getValorIr()));
        $valores->appendChild($docxml->createElement("$this->tc:ValorCsll", $servicoDTO->getValorCsll()));
        $valores->appendChild($docxml->createElement("$this->tc:IssRetido", $servicoDTO->getIssRetido()));
        $valores->appendChild($docxml->createElement("$this->tc:ValorIss", $servicoDTO->getValorIss()));
        $valores->appendChild($docxml->createElement("$this->tc:BaseCalculo", $servicoDTO->getValor()));
        $valores->appendChild($docxml->createElement("$this->tc:Aliquota", $servicoDTO->getAliquota()));
        $valores->appendChild($docxml->createElement("$this->tc:ValorLiquidoNfse", $servicoDTO->getValorLiquidoNfse()));
        $valores->appendChild($docxml->createElement("$this->tc:DescontoIncondicionado", $servicoDTO->getValorDescontoIncondicionado()));
        $valores->appendChild($docxml->createElement("$this->tc:DescontoCondicionado", $servicoDTO->getValorDescontoCondicionado()));

        $servico->appendChild($valores);
        $servico->appendChild($docxml->createElement("$this->tc:ItemListaServico", $servicoDTO->getItemListaServico()));
        //$cnaeFmt = str_pad($servicoDTO->getCodigoCnae(), 8, '0', STR_PAD_LEFT);
        $servico->appendChild($docxml->createElement("$this->tc:CodigoCnae", $servicoDTO->getCodigoCnae()));
        $servico->appendChild($docxml->createElement("$this->tc:CodigoTributacaoMunicipio", $servicoDTO->getCodigoTributacaoMunicipio()));
        $servico->appendChild($docxml->createElement("$this->tc:Discriminacao", $servicoDTO->getDiscriminacao()));
        $servico->appendChild($docxml->createElement("$this->tc:MunicipioPrestacaoServico", $servicoDTO->getCodigoMunicipioPrestacaoServico()));

        return $servico;
    }

    public function assinarArquivo(\DOMDocument $docxml, $nodeId = false)
    {
        $this->carregarCertificado();
        $this->assinarXML($docxml, $nodeId);
    }

    public function criarXMLCancelamento(){
        $this->xmlns = "http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_cancelar_nfse_envio.xsd";
        $docxml = new \DOMDocument('1.0', 'UTF-8');
        $docxml->formatOutput = true;
        $cancelarNfse = $docxml->createElement("p1:CancelarNfseEnvio");
        $cancelarNfse->setAttribute("xmlns:p1", $this->xmlns);
        $cancelarNfse->setAttribute("xmlns:tc", $this->xmlnstc);
        $cancelarNfse->setAttribute("xmlns:ts", $this->xmlnsts);

        $identificacaoNfse = $docxml->createElement("tc:IdentificacaoNfse");
        $identificacaoNfse->appendChild($docxml->createElement("tc:Numero", $this->requisicaoDTO->getNfse()->getNumero()));
        $identificacaoNfse->appendChild($docxml->createElement("tc:Cnpj", $this->requisicaoDTO->getPrestador()->getDocumento()));
        $identificacaoNfse->appendChild($docxml->createElement("tc:InscricaoMunicipal", $this->requisicaoDTO->getPrestador()->getInscricaoMunicipal()));
        $identificacaoNfse->appendChild($docxml->createElement("tc:CodigoMunicipio", $this->requisicaoDTO->getPrestador()->getCodigoMunicipio()));

        $infPedido = $docxml->createElement("tc:InfPedidoCancelamento");
        $infPedido->appendChild($identificacaoNfse);
        $infPedido->appendChild($docxml->createElement("tc:CodigoCancelamento", $this->requisicaoDTO->getNfse()->getCodigoCancelamento()));

        $pedido = $docxml->createElement("Pedido");
        $nodeId = "pedidoCancelamento";

        $pedido->appendChild($infPedido);
        //$pedido->setAttribute("id", $nodeId);
        //$pedido->setIdAttribute("id", true);


        $cancelarNfse->appendChild($pedido);
        $docxml->appendChild($cancelarNfse);

        $this->assinarArquivo($docxml, $nodeId);

        return $docxml;
    }

    public function criarXMLConsultaLinkImpressaoNfse()
    {
        $this->xmlns = "http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_consultar_url_visualizacao_nfse_envio.xsd";
        $docxml = new \DOMDocument('1.0', 'UTF-8');
        $docxml->formatOutput = true;
        $consultar = $docxml->createElement("ConsultarUrlVisualizacaoNfseEnvio");
        $consultar->setAttribute("xmlns", $this->xmlns);
        $consultar->setAttribute("xmlns:tc", $this->xmlnstc);
        $consultar->setAttribute("xmlns:ts", $this->xmlnsts);
        $prestador = $this->criarXMLPrestadorConsulta($this->requisicaoDTO->getPrestador(), $docxml);
        $consultar->appendChild($prestador);
        $consultar->appendChild($docxml->createElement("Numero", $this->requisicaoDTO->getNfse()->getNumero()));
        $consultar->appendChild($docxml->createElement("CodigoTributacaoMunicipio", $this->requisicaoDTO->getCodigoTributacaoMunicipio()));
        $docxml->appendChild($consultar);
        return $docxml;
    }

}