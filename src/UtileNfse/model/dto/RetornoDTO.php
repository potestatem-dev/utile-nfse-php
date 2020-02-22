<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 15/09/2016
 * Time: 10:29
 */

namespace UtileNfse\model\dto;


class RetornoDTO
{
    private $listaErros;
    private $lote;
    private $listaNfse;
    private $urlImpressaoNfse;

    /**
     * @return mixed
     */
    public function getListaErros()
    {
        return $this->listaErros;
    }

    /**
     * @param mixed $listaErros
     */
    public function setListaErros($listaErros)
    {
        $this->listaErros = $listaErros;
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
    public function getListaNfse()
    {
        return $this->listaNfse;
    }

    /**
     * @param mixed $listaNfse
     */
    public function setListaNfse($listaNfse)
    {
        $this->listaNfse = $listaNfse;
    }

    /**
     * @return mixed
     */
    public function getUrlImpressaoNfse()
    {
        return $this->urlImpressaoNfse;
    }

    /**
     * @param mixed $urlImpressaoNfse
     */
    public function setUrlImpressaoNfse($urlImpressaoNfse)
    {
        $this->urlImpressaoNfse = $urlImpressaoNfse;
    }
}