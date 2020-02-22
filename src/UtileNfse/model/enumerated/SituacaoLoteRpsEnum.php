<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 15/09/2016
 * Time: 16:11
 */

namespace UtileNfse\model\enumerated;


abstract class SituacaoLoteRpsEnum
{
    const NAO_RECEBIDO = 1;
    const NAO_PROCESSADO = 2;
    const PROCESSADO_COM_ERRO = 3;
    const PROCESSADO_COM_SUCESSO = 4;
}