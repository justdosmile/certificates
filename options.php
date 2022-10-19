<?php

use Bitrix\Main\Localization\Loc;
use	Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Jds\Certdb\CertTable;
use Bitrix\Main\Diag;

$context = HttpApplication::getInstance()->getContext();
$request = $context->getRequest();

Loc::loadMessages(__FILE__);
Loc::loadMessages($context->getServer()->getDocumentRoot()."/bitrix/modules/main/options.php");

$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

Loader::includeModule($module_id);


$tabControl = new CAdminTabControl("tabControl", array(
    array(
        "DIV" => "edit1",
        "TAB" => Loc::getMessage("MAIN_TAB_SET"),
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET"),
    ),
));

$aTabs = array(
	array(
		"DIV" 	  => "edit",
		"TAB" 	  => Loc::getMessage("JDS_OPTIONS_TAB_NAME"),
		"TITLE"   => Loc::getMessage("JDS_OPTIONS_TAB_NAME"),
		"OPTIONS" => array(
			Loc::getMessage("JDS_OPTIONS_TAB_COMMON"),
			array(
				"file_link",
				Loc::getMessage("JDS_OPTIONS_TAB_LINK"),
				"https://",
                array("text", 80)
			),
            array(
                "refresh",
                Loc::getMessage("JDS_OPTIONS_TAB_REFRESH"),
                "Y",
                array("checkbox")
            )
		)
	)
);

$tabControl = new CAdminTabControl(
	"tabControl",
	$aTabs
);

$urlParams = [
    "mid" => $module_id,
    "lang" => LANGUAGE_ID,
];
$formActionUrl = sprintf("%s?%s", $APPLICATION->GetCurPage(), http_build_query($urlParams));
?>
<form action="<?= $formActionUrl; ?>" method="post">
	<?php
    $tabControl->Begin();

	foreach($aTabs as $aTab) {

        if ($aTab["OPTIONS"]) {

            $tabControl->BeginNextTab();

            __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
        }
    }
    if($request['res']==1){
        echo CAdminMessage::ShowNote(Loc::GetMessage("JDS_OPTIONS_INFO"));
    }
	$tabControl->Buttons();
	?>

	<input type="submit" name="Update" value="<?php echo(Loc::GetMessage("JDS_OPTIONS_INPUT_APPLY")); ?>" class="adm-btn-save" />
	<input type="reset" name="Reset" value="<?php echo(Loc::GetMessage("JDS_OPTIONS_INPUT_DEFAULT")); ?>" />

	<?php
	echo(bitrix_sessid_post());
    $tabControl->End();
    ?>
</form>

<?php
if ($request->isPost() && $request['Update'] && check_bitrix_sessid()){

    $option_link = $request->getPost('file_link');
    $option_refresh = $request->getPost('refresh');

    if($option_refresh=='Y'){
        CertTable::cleanTable();
    }

    $result = CertTable::readCSV($option_link);

    if($result){
        LocalRedirect($APPLICATION->GetCurPage()."?mid=".$module_id."&lang=".LANG."&res=1");
    }else{
        LocalRedirect($APPLICATION->GetCurPage()."?mid=".$module_id."&lang=".LANG."&res=0");
    }
}
