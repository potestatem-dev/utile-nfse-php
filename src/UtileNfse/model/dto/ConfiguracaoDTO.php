<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 03/08/2016
 * Time: 23:46
 */

namespace UtileNfse\model\dto;


class ConfiguracaoDTO
{
    private $nomeCertificadoPfx;
    private $diretorioCertificados;
    private $senhaCertificado;
    private $diretorioXML;
    private $ambiente;
    private $urlWebservice;

    /**
     * @return mixed
     */
    public function getNomeCertificadoPfx()
    {
        return $this->nomeCertificadoPfx;
    }

    /**
     * @param mixed $nomeCertificadoPfx
     */
    public function setNomeCertificadoPfx($nomeCertificadoPfx)
    {
        $this->nomeCertificadoPfx = $nomeCertificadoPfx;
    }

    /**
     * @return mixed
     */
    public function getDiretorioCertificados()
    {
        return $this->diretorioCertificados;
    }

    /**
     * @param mixed $diretorioCertificados
     */
    public function setDiretorioCertificados($diretorioCertificados)
    {
        $this->diretorioCertificados = $diretorioCertificados;
    }

    /**
     * @return mixed
     */
    public function getSenhaCertificado()
    {
        return $this->senhaCertificado;
    }

    /**
     * @param mixed $senhaCertificado
     */
    public function setSenhaCertificado($senhaCertificado)
    {
        $this->senhaCertificado = $senhaCertificado;
    }

    /**
     * @return mixed
     */
    public function getDiretorioXML()
    {
        return $this->diretorioXML;
    }

    /**
     * @param mixed $diretorioXML
     */
    public function setDiretorioXML($diretorioXML)
    {
        $this->diretorioXML = $diretorioXML;
    }

    /**
     * @return mixed
     */
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    /**
     * @param mixed $ambiente
     */
    public function setAmbiente($ambiente)
    {
        $this->ambiente = $ambiente;
    }

    /**
     * @return mixed
     */
    public function getUrlWebservice()
    {
        return $this->urlWebservice;
    }

    /**
     * @param mixed $urlWebservice
     */
    public function setUrlWebservice($urlWebservice)
    {
        $this->urlWebservice = $urlWebservice;
    }
}