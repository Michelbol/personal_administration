<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26/03/2019
 * Time: 07:31
 */

namespace App;
use SimpleXMLElement;


class Ofx
{
    const ofxCredit = 'CREDIT';

    private  $arquivo;

    public $bankTranList;

    public $dtStar;

    public $dtEnd;

    public $bankId;

    public $acctId;

    public $org;

    public function __construct($arquivo)
    {
        $this->arquivo  =   $arquivo;
        return $this->retorno();
    }

    public function closeTags($ofx=null) {
        $buffer = '';
        $source = fopen($ofx, 'r') or die("Unable to open file!");
        while(!feof($source)) {
            $line = trim(fgets($source));
            if ($line === '') continue;
            if (substr($line, -1, 1) !== '>') {
                list($tag) = explode('>', $line, 2);
                $line .= '</' . substr($tag, 1) . '>';
            }
            $buffer .= $line ."\n";
        }
        $xmlOut =   explode("<OFX>", $buffer);
        //$name = realpath(dirname($ofx)) . '/' . date('Ymd') . '.ofx';
        //$file = fopen($name, "w") or die("Unable to open file!");
        //fwrite($file, $buffer);
        //fclose($file);
        return isset($xmlOut[1])?"<OFX>".$xmlOut[1]:$buffer;
    }

    public function retorno()
    {
        $retorno    =   new SimpleXMLElement(utf8_encode($this->closeTags($this->arquivo)));
        $this->bankTranList =   $retorno->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->STMTTRN;
        $this->dtStar   =   $retorno->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->DTSTART;
        $this->dtEnd    =   $retorno->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->DTEND;
        $this->org      =   $retorno->SIGNONMSGSRSV1->SONRS->FI->ORG;
        $this->acctId   =   $retorno->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKACCTFROM->ACCTID;
        $this->bankId   =   $retorno->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKACCTFROM->BANKID;
        return $this;
    }
}
