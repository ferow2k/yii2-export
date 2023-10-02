<?php

use yii\helpers\Html;
use yii\bootstrap\Dropdown;
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
    '<i class="glyphicon glyphicon-download-alt"></i>Export<b class="caret"></b>',
    $buttonOptions
);
echo Dropdown::widget($dropDownOptions);
ActiveForm::end();

echo Html::endTag('div');
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.dropdown-menu').on('click', 'li', function(e) {
            const target = e.target;
            if (target.tagName === 'A') {
                e.preventDefault();
                const selectedOption = this.getAttribute('data-id');;
                const form = document.getElementById('w0'); // TODO: NOT HARDCODE
                const exportRequestParam = document.getElementsByName("<?= $exportRequestParam ?>")[0];

                if (!exportRequestParam || !selectedOption) {
                    return;
                }

                exportRequestParam.setAttribute('value', selectedOption)

                /**Object URL used to recover query strings on URL */
                form.setAttribute('action', (new URL(window.location.href)).toString())
                /** FORCE FORM METHOD */
                form.setAttribute('method', 'post')
                form.submit();
            }
        });
    });
</script>