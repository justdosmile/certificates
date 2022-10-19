<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc as Loc;
use Jds\Certdb\CertTable;
use	Bitrix\Main\HttpApplication;

class CertdbCheck extends CBitrixComponent
{
    protected function checkModules()
    {
        if (!Main\Loader::includeModule('jds.certdb'))
            throw new Main\LoaderException(Loc::getMessage('JDS_MODULE_NOT_INSTALLED'));
    }

    function getResult($number)
    {
        $result = CertTable::getList(array(
            'select'  => array('ID', 'NUMBER','DATE_START', 'DATE_END', 'CLIENT', 'CLIENT', 'PRODUCT', 'STATUS'),
            'filter'  => array('NUMBER' => $number),
        ));

        return $result;
    }

    public function executeComponent()
    {
        $this->includeComponentLang('class.php');
        $this->checkModules();
        $request = HttpApplication::getInstance()->getContext()->getRequest();

        if($request['num']){
            $number = $request->getQuery('num');
            $result = $this->getResult($number);
            $this->arResult = $result->fetchAll();
        }

        $this->includeComponentTemplate();
    }
};