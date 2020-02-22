<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 16/09/2016
 * Time: 15:55
 */

namespace UtileNfse\model\dto;


class NfseDTO
{
    private $numero;
    private $codigoVerificacao;
    private $numeroRps;
    private $dataEmissao;
    private $dataCancelamento;
    private $codigoCancelamento;

    /**
     * @return mixed
     */
    public function getCodigoCancelamento()
    {
        return $this->codigoCancelamento;
    }

    /**
     * @param mixed $codigoCancelamento
     */
    public function setCodigoCancelamento($codigoCancelamento)
    {
        $this->codigoCancelamento = $codigoCancelamento;
    }

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
    public function getCodigoVerificacao()
    {
        return $this->codigoVerificacao;
    }

    /**
     * @param mixed $codigoVerificacao
     */
    public function setCodigoVerificacao($codigoVerificacao)
    {
        $this->codigoVerificacao = $codigoVerificacao;
    }

    /**
     * @return mixed
     */
    public function getNumeroRps()
    {
        return $this->numeroRps;
    }

    /**
     * @param mixed $numeroRps
     */
    public function setNumeroRps($numeroRps)
    {
        $this->numeroRps = $numeroRps;
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
    public function getDataCancelamento()
    {
        return $this->dataCancelamento;
    }

    /**
     * @param mixed $dataCancelamento
     */
    public function setDataCancelamento($dataCancelamento)
    {
        $this->dataCancelamento = $dataCancelamento;
    }
}