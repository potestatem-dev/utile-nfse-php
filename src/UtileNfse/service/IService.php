<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 03/08/2016
 * Time: 23:39
 */

namespace UtileNfse\service;

interface IService
{
    public function enviarLoteRps($numeroLote);
    public function consultarSituacaoLoteRps($numeroLote);
    public function cancelarNfse();
    public function consultarLinkImpressaoNfse();
}