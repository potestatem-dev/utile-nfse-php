<?php
/**
 * Created by PhpStorm.
 * User: adelio
 * Date: 23/08/2016
 * Time: 16:51
 */

namespace UtileNfse\conversor;

use UtileNfse\model\dto\LoteDTO;
use UtileNfse\model\dto\PessoaDTO;
use UtileNfse\model\dto\RpsDTO;
use UtileNfse\model\dto\ServicoDTO;

interface IConversor
{
    public function criarXMLLoteRps(LoteDTO $loteDTO, \DOMDocument $docxml);
    public function criarXMLRps(RpsDTO $rps, \DOMDocument $docxml);
    public function criarXMLPrestador(PessoaDTO $prestadorDTO, \DOMDocument $docxml);
    public function criarXMLTomador(PessoaDTO $tomadorDTO, \DOMDocument $docxml);
    public function criarXMLServico(ServicoDTO $servicoDTO, \DOMDocument $docxml);
    public function criarXMLEnvioLote();
    public function criarXMLConsultaSituacaoLote(PessoaDTO $prestador);
    public function criarXMLConsultaLote(PessoaDTO $prestador);
    public function assinarArquivo(\DOMDocument $docxml);
    public function criarXMLNfse(\SimpleXMLElement $paramNfse);

}