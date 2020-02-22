<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 03/08/2016
 * Time: 23:57
 */

namespace UtileNfse\model\dto;


class ServicoDTO
{
    private $discriminacao;
    private $itemListaServico;//Código do Serviço conforme LC 116/2003
    private $valor;
    private $aliquota;
    private $baseCalculo;
    private $valorIss;
    private $issRetido;
    private $codigoMunicipioPrestacaoServico;
    private $codigoTributacaoMunicipio;
    private $valorIssRetido;
    private $valorLiquidoNfse;
    private $valorPis;
    private $valorCofins;
    private $valorInss;
    private $valorIr;
    private $valorCsll;
    private $valorDescontoCondicionado;
    private $valorDescontoIncondicionado;
    private $codigoCnae;

    /**
     * @return mixed
     */
    public function getDiscriminacao()
    {
        return $this->discriminacao;
    }

    /**
     * @param mixed $discriminacao
     */
    public function setDiscriminacao($discriminacao)
    {
        $this->discriminacao = $discriminacao;
    }

    /**
     * @return mixed
     */
    public function getItemListaServico()
    {
        return $this->itemListaServico;
    }

    /**
     * @param mixed $itemListaServico
     */
    public function setItemListaServico($itemListaServico)
    {
        $this->itemListaServico = $itemListaServico;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return mixed
     */
    public function getAliquota()
    {
        return $this->aliquota;
    }

    /**
     * @param mixed $aliquota
     */
    public function setAliquota($aliquota)
    {
        $this->aliquota = $aliquota;
    }

    /**
     * @return mixed
     */
    public function getBaseCalculo()
    {
        return $this->baseCalculo;
    }

    /**
     * @param mixed $baseCalculo
     */
    public function setBaseCalculo($baseCalculo)
    {
        $this->baseCalculo = $baseCalculo;
    }

    /**
     * @return mixed
     */
    public function getValorIss()
    {
        return $this->valorIss;
    }

    /**
     * @param mixed $valorIss
     */
    public function setValorIss($valorIss)
    {
        $this->valorIss = $valorIss;
    }

    /**
     * @return mixed
     */
    public function getIssRetido()
    {
        return $this->issRetido;
    }

    /**
     * @param mixed $issRetido
     */
    public function setIssRetido($issRetido)
    {
        $this->issRetido = $issRetido;
    }

    /**
     * @return mixed
     */
    public function getCodigoMunicipioPrestacaoServico()
    {
        return $this->codigoMunicipioPrestacaoServico;
    }

    /**
     * @param mixed $codigoMunicipioPrestacaoServico
     */
    public function setCodigoMunicipioPrestacaoServico($codigoMunicipioPrestacaoServico)
    {
        $this->codigoMunicipioPrestacaoServico = $codigoMunicipioPrestacaoServico;
    }

    /**
     * @return mixed
     */
    public function getCodigoTributacaoMunicipio()
    {
        return $this->codigoTributacaoMunicipio;
    }

    /**
     * @param mixed $codigoTributacaoMunicipio
     */
    public function setCodigoTributacaoMunicipio($codigoTributacaoMunicipio)
    {
        $this->codigoTributacaoMunicipio = $codigoTributacaoMunicipio;
    }

    /**
     * @return mixed
     */
    public function getValorIssRetido()
    {
        return $this->valorIssRetido;
    }

    /**
     * @param mixed $valorIssRetido
     */
    public function setValorIssRetido($valorIssRetido)
    {
        $this->valorIssRetido = $valorIssRetido;
    }

    /**
     * @return mixed
     */
    public function getValorLiquidoNfse()
    {
        return $this->valorLiquidoNfse;
    }

    /**
     * @param mixed $valorLiquidoNfse
     */
    public function setValorLiquidoNfse($valorLiquidoNfse)
    {
        $this->valorLiquidoNfse = $valorLiquidoNfse;
    }

    /**
     * @return mixed
     */
    public function getValorPis()
    {
        return $this->valorPis;
    }

    /**
     * @param mixed $valorPis
     */
    public function setValorPis($valorPis)
    {
        $this->valorPis = $valorPis;
    }

    /**
     * @return mixed
     */
    public function getValorCofins()
    {
        return $this->valorCofins;
    }

    /**
     * @param mixed $valorCofins
     */
    public function setValorCofins($valorCofins)
    {
        $this->valorCofins = $valorCofins;
    }

    /**
     * @return mixed
     */
    public function getValorInss()
    {
        return $this->valorInss;
    }

    /**
     * @param mixed $valorInss
     */
    public function setValorInss($valorInss)
    {
        $this->valorInss = $valorInss;
    }

    /**
     * @return mixed
     */
    public function getValorIr()
    {
        return $this->valorIr;
    }

    /**
     * @param mixed $valorIr
     */
    public function setValorIr($valorIr)
    {
        $this->valorIr = $valorIr;
    }

    /**
     * @return mixed
     */
    public function getValorCsll()
    {
        return $this->valorCsll;
    }

    /**
     * @param mixed $valorCsll
     */
    public function setValorCsll($valorCsll)
    {
        $this->valorCsll = $valorCsll;
    }

    /**
     * @return mixed
     */
    public function getValorDescontoCondicionado()
    {
        return $this->valorDescontoCondicionado;
    }

    /**
     * @param mixed $valorDescontoCondicionado
     */
    public function setValorDescontoCondicionado($valorDescontoCondicionado)
    {
        $this->valorDescontoCondicionado = $valorDescontoCondicionado;
    }

    /**
     * @return mixed
     */
    public function getValorDescontoIncondicionado()
    {
        return $this->valorDescontoIncondicionado;
    }

    /**
     * @param mixed $valorDescontoIncondicionado
     */
    public function setValorDescontoIncondicionado($valorDescontoIncondicionado)
    {
        $this->valorDescontoIncondicionado = $valorDescontoIncondicionado;
    }

    /**
     * @return mixed
     */
    public function getCodigoCnae()
    {
        return $this->codigoCnae;
    }

    /**
     * @param mixed $codigoCnae
     */
    public function setCodigoCnae($codigoCnae)
    {
        $this->codigoCnae = $codigoCnae;
    }
}