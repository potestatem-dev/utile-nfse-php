<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 03/08/2016
 * Time: 23:45
 */

namespace UtileNfse\model\dto;


class RequisicaoDTO
{
    private $configuracao;
    private $lote;
    private $nfse;
    private $prestador;
    private $codigoTributacaoMunicipio;

    /**
     * @return mixed
     */
    public function getConfiguracao()
    {
        return $this->configuracao;
    }

    /**
     * @param mixed $configuracao
     */
    public function setConfiguracao(ConfiguracaoDTO $configuracao)
    {
        $this->configuracao = $configuracao;
    }

    /**
     * @return mixed
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * @param mixed $lote
     */
    public function setLote($lote)
    {
        $this->lote = $lote;
    }

    /**
     * @return mixed
     */
    public function getNfse()
    {
        return $this->nfse;
    }

    /**
     * @param mixed $nfse
     */
    public function setNfse($nfse)
    {
        $this->nfse = $nfse;
    }

    /**
     * @return mixed
     */
    public function getPrestador()
    {
        return $this->prestador;
    }

    /**
     * @param mixed $prestador
     */
    public function setPrestador($prestador)
    {
        $this->prestador = $prestador;
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
}