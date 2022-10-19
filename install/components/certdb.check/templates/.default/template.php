<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

?>
<h1>Проверка сертификата</h1>
<form action="<?php echo $APPLICATION->GetCurPage();?>" method="get">
    <input type="text" name="num">
    <button>Проверить</button>
</form>
<?php
if(!empty($arResult)){
?>
     <div class="result">
        <?php
        foreach($arResult[0] as $elem):
        ?>

        <div class="result_block">
            <div class="row">
                <?=$elem;?>
            </div>
        </div>

        <?php
        endforeach;
        ?>
     </div>
<?php
}
?>

<style>
    .result{
        width:1200px;
        max-width:100%;
        margin:20px auto;
    }
    .result_block{
        display:flex;
        align-items:center;
        background:#efefef;
    }
</style>
