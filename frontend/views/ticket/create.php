<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */
/* @var $form ActiveForm */
?>
<div class="create">

    <?php $form = ActiveForm::begin(); ?>
    
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'type')->dropDownList([
            '0' => 'Ошибка',
            '1' => 'Доработка',
            '2' => 'Нововедение'
        ]);?>
        <?= $form->field($model, 'description')->textarea() ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- create -->
