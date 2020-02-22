<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 03/08/2016
 * Time: 23:42
 */

namespace UtileNfse\model\dto;


class RpsDTO
{
    private $numero;
    private $serie;
    private $tipo;
    private $dataEmissao;
    private $status;
    private $servico;
    private $prestador;
    private $tomador;
    private $naturezaOperacao;
    private $regimeEspecialTributacao;

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * @param mixed $serie
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getDataEmissao()
    {
        return $this->dataEmissao;
    }

    /**
     * @param mixed $dataEmissao
     */
    public function setDataEmissao($dataEmissao)
    {
        $this->dataEmissao = $dataEmissao;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getServico()
    {
        return $this->servico;
    }

    /**
     * @param mixed $servico
     */
    public function setServico($servico)
    {
        $this->servico = $servico;
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
    public function setPrestador(PessoaDTO $prestador)
    {
        $this->prestador = $prestador;
    }

    /**
     * @return mixed
     */
    public function getTomador()
    {
        return $this->tomador;
    }

    /**
     * @param mixed $tomador
     */
    public function setTomador($tomador)
    {
        $this->tomador = $tomador;
    }

    /**
     * @return mixed
     */
    public function getNaturezaOperacao()
    {
        return $this->naturezaOperacao;
    }

    /**
     * @param mixed $naturezaOperacao
     */
    public function setNaturezaOperacao($naturezaOperacao)
    {
        $this->naturezaOperacao = $naturezaOperacao;
    }

    /**
     * @return mixed
     */
    public function getRegimeEspecialTributacao()
    {
        return $this->regimeEspecialTributacao;
    }

    /**
     * @param mixed $regimeEspecialTributacao
     */
    public function setRegimeEspecialTributacao($regimeEspecialTributacao)
    {
        $this->regimeEspecialTributacao = $regimeEspecialTributacao;
    }
}