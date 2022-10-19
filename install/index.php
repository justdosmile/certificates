<?php

use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\IO;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Jds\Certdb\CertTable;

Loc::loadMessages(__FILE__);

class jds_certdb extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();

        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_ID = 'jds.certdb';
        $this->MODULE_NAME = Loc::getMessage('JDS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('JDS_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('JDS_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'https://t.me/maxsap';
    }

    public function doInstall()
    {
        if (CheckVersion(ModuleManager::getVersion("main"), "14.00.00")) {
            $this->InstallFiles();
            ModuleManager::registerModule($this->MODULE_ID);
            $this->installDB();
            $this->demoDataDB();
            $this->createEmailMsg();
        } else {
            $APPLICATION->ThrowException(
                Loc::getMessage("JDS_INSTALL_ERROR_VERSION")
            );
        }
    }

    public function doUninstall()
    {
        $this->uninstallDB();
        $this->deleteEmailMsg();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    //  Создает почтовое событие и шаблон для администратора
    public function createEmailMsg(){
        $obEventType = new CEventType;
        $obEventType->Add(array(
            "EVENT_NAME"    => "CERT_DATA_UPLOAD",
            "NAME"          => Loc::getMessage('JDS_MODULE_EMAIL_TITLE'),
            "LID"           => "ru"
        ));

        $obTemplate = new CEventMessage;
        $obTemplate->Add(array(
            "ACTIVE" => "Y",
            "EVENT_NAME" => "CERT_DATA_UPLOAD",
            "LID" => array("ru","en"),
            "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
            "EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
            "SUBJECT" => Loc::getMessage('JDS_MODULE_EMAIL_TITLE'),
            "BODY_TYPE" => "text",
            "MESSAGE" => Loc::getMessage('JDS_MODULE_EMAIL_MSG')
        ));
    }

    //  Удаляет почтовое событие и шаблон для администратора
    public function deleteEmailMsg(){
        CEventType::Delete("CERT_DATA_UPLOAD");

        $rsMess = CEventMessage::GetList("site_id", "desc", array("TYPE_ID" => "CERT_DATA_UPLOAD"));
        while($arMess = $rsMess->GetNext())
        {
            CEventMessage::Delete($arMess["ID"]);
        }
    }

    public function InstallFiles()
    {
        $path = $this->GetPath()."/install/components";

        if(IO\Directory::isDirectoryExists($path))
            CopyDirFiles($path, $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        else
            throw new IO\InvalidPathException($path);

        return false;
    }

    public function UnInstallFiles()
    {
        IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/jds/');
        return false;
    }

    public function installDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            Base::getInstance('\Jds\Certdb\CertTable')->createDbTable();

        }
        return false;
    }

    public function uninstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $connection = Application::getInstance()->getConnection();
            $connection->queryExecute('drop table if exists ' . Base::getInstance('\Jds\Certdb\CertTable')->getDBTableName());
        }
        return false;
    }

    //  Добавляет демо данные в таблицу
    public function demoDataDB()
    {
        $result = CertTable::add(array(
            "NUMBER" => "139845843589345",
            "DATE_START" => "12 февраля 2021",
            "DATE_END" => "12 февраля 2026",
            "CLIENT" => "ПАО Автодок",
            "CREATOR" => "ООО ЕА ТТК",
            "PRODUCT" => "Автомобильные запчасти и комплектующие",
            "STATUS" => "Действующий"
        ));
        return false;
    }

    //  Путь к модулю, не зависит от папки
    public function GetPath($notDocumentRoot=false)
    {
        if($notDocumentRoot){
            return str_ireplace(Application::getDocumentRoot(),'',dirname(__DIR__));
        }else{
            return dirname(__DIR__);
        }
    }
}
