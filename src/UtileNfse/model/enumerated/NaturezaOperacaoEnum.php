<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 09/09/2016
 * Time: 10:17
 */

namespace UtileNfse\model\enumerated;


abstract class NaturezaOperacaoEnum
{
    const TRIBUTACAO_NO_MUNICIPIO = 1;
    const TRIBUTACAO_FORA_DO_MUNICIPIO = 2;
    const ISENCAO = 3;
    const IMUNE = 4;
    const EXIGIBILIDADE_SUPENSA_POR_DECISAO_JUDICIAL = 5;
    const EXIGIBILIDADE_SUSPENSA_POR_PROCEDIMENTO_ADMINISTRATIVO = 6;

}