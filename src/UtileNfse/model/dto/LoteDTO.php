<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 03/08/2016
 * Time: 23:45
 */

namespace UtileNfse\model\dto;


class LoteDTO
{
    private $numero;
    private $dataEnvio;
    private $dataProtocolo;
    private $protocolo;
    private $listaRps;
    private $situacao;

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
    public function getDataEnvio()
    {
        return $this->dataEnvio;
    }

    /**
     * @param mixed $dataEnvio
     */
    public function setDataEnvio($dataEnvio)
    {
        $this->dataEnvio = $dataEnvio;
    }

    /**
     * @return mixed
     */
    public function getDataProtocolo()
    {
        return $this->dataProtocolo;
    }

    /**
     * @param mixed $dataProtocolo
     */
    public function setDataProtocolo($dataProtocolo)
    {
        $this->dataProtocolo = $dataProtocolo;
    }

    /**
     * @return mixed
     */
    public function getProtocolo()
    {
        return $this->protocolo;
    }

    /**
     * @param mixed $protocolo
     */
    public function setProtocolo($protocolo)
    {
        $this->protocolo = $protocolo;
    }

    /**
     * @return mixed
     */
    public function getListaRps()
    {
        return $this->listaRps;
    }

    /**
     * @param mixed $listaRps
     */
    public function setListaRps($listaRps)
    {
        $this->listaRps = $listaRps;
    }

    /**
     * @return mixed
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * @param mixed $situacao
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
    }
}