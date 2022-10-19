<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array(
    "NAME" => Loc::getMessage('JDS_CHECK_DESCRIPTION_TITLE'),
    "DESCRIPTION" => Loc::getMessage('JDS_CHECK__DESCRIPTION'),
    "ICON" => '/images/icon.gif',
    "SORT" => 20,
    "PATH" => array(
        "ID" => 'jds',
        "NAME" => Loc::getMessage('JDS_CHECK_DESCRIPTION_GROUP'),
        "SORT" => 10
    )
);
