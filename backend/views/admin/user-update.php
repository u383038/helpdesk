<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>


<ul class="nav nav-tabs">
    <li role="presentation"><a href="<?= Url::to(['admin/user-index'])?>">Пользователи</a></li>
</ul>

<br>

<div class="row">
    <div class="col-lg-12">

        <h4>Редактировать информацию <?= $name?></h4>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('Логин') ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true])->label('email') ?>

        <?= $form->field($model, 'position')->textInput()->label('Должность') ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <br><br>


        <h4>Изменить пароль пользователя <?= $name?></h4>
        <form action="<?= Url::to(['admin/user-update', 'id' => $id])?>" id="adminPassword" name="adminPassword" method="post">
            <label>
                Новый пароль
                <input type="password" class="form-control" name="newPassword"  >
            </label><br>
            <label>
                Повтор нового пароля
                <input type="password" class="form-control" name="repeatNewPassword"  >
            </label>
            <br><br>
            <button form="adminPassword" class="btn btn-primary" type="submit">Изменить</button>
        </form>
        <hr><br><br>
    </div>

</div>




</div>