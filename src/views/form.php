<?php

use yii\helpers\Html;
use yii\bootstrap4\Dropdown;
use yii\widgets\ActiveForm;

/**
 * @var array $options
 * @var array $formOptions
 * @var array $dropDownOptions
 * @var array $buttonOptions
 */
echo Html::beginTag('div', $options);

$form = ActiveForm::begin($formOptions);
echo Html::hiddenInput($exportRequestParam);
echo Html::button(
    '<i class="glyphicon glyphicon-download-alt"></i> ' . Yii::t('app', 'Export') . ' <b class="caret"></b>',
    $buttonOptions
);
echo Dropdown::widget($dropDownOptions);
ActiveForm::end();

echo Html::endTag('div');
