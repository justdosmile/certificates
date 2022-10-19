<?php

namespace Jds\Certdb;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\Validator\Length;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;
use \Bitrix\Main\SystemException;

class CertTable extends DataManager
{
    public static function getTableName(){
        return 'cert_db';
    }

    public static function readCSV($link){
        try{
            $data = file_get_contents($link);
            $rows = explode(PHP_EOL, $data);
            $arResult = array();
            foreach($rows as $row) {
                $arResult[] = str_getcsv($row,';');
            }
            self::loadData($arResult);
            return true;
        }catch (SystemException $e){
            echo $e->getMessage();
            return false;
        }
    }

    public static function loadData(array $data){
        foreach($data as $elem){
            self::add(array(
                "NUMBER" => $elem[0],
                "DATE_START" => $elem[1],
                "DATE_END" => $elem[2],
                "CLIENT" => $elem[3],
                "CREATOR" => $elem[4],
                "PRODUCT" => $elem[5],
                "STATUS" => $elem[6]
            ));
        }
        return true;
    }

    public static function cleanTable(){
        $connection = Application::getConnection();
        $connection->truncateTable(self::getTableName());
    }

    public static function getMap(){
        return array(
            new IntegerField('ID', array(
                'autocomplete' => true,
                'primary' => true,
            )),
            new StringField('NUMBER', array(
                'required' => true,
                'title' => Loc::getMessage('JDS_NUMBER'),
                'validation' => function () {
                    return array(
                        new Length(null, 255),
                    );
                },
            )),
            new StringField('DATE_START', array(
                'required' => true,
                'title' => Loc::getMessage('JDS_DATE_START'),
                'validation' => function () {
                    return array(
                        new Length(null, 255),
                    );
                },
            )),
            new StringField('DATE_END', array(
                'required' => true,
                'title' => Loc::getMessage('JDS_DATE_END'),
                'validation' => function () {
                    return array(
                        new Length(null, 255),
                    );
                },
            )),
            new StringField('CLIENT', array(
                'required' => true,
                'title' => Loc::getMessage('JDS_CLIENT'),
                'validation' => function () {
                    return array(
                        new Length(null, 255),
                    );
                },
            )),
            new StringField('CREATOR', array(
                'required' => true,
                'title' => Loc::getMessage('JDS_CREATOR'),
                'validation' => function () {
                    return array(
                        new Length(null, 255),
                    );
                },
            )),
            new StringField('PRODUCT', array(
                'required' => true,
                'title' => Loc::getMessage('JDS_PRODUCT'),
                'validation' => function () {
                    return array(
                        new Length(null, 255),
                    );
                },
            )),
            new StringField('STATUS', array(
                'required' => true,
                'title' => Loc::getMessage('JDS_STATUS'),
                'validation' => function () {
                    return array(
                        new Length(null, 255),
                    );
                },
            )),
        );
    }

}