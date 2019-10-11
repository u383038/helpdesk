<?php

use yii\helpers\Url;

?>


<ul class="nav nav-tabs">
    <li role="presentation" class="active"><a href="#">Пользователи</a></li>
</ul>

<br>

<div class="row">
    <div class="col-lg-12">
        <h4>Пользователи</h4>
        <a href="<?= Url::to(['admin/user-create']) ?>" class="btn btn-success">Добавить нового пользователя</a>
        <br><br>
        <table class="table table-bordered">
            <tr>
                <th>id</th>
                <th>Должность</th>
                <th>Логин</th>
                <th>email</th>
                <!--<th>Количество<br> добавленных сертификатов</th>
                <th>Количество<br> удаленных сертификатов</th>-->
            </tr>

            <?php foreach ($model as $item):?>
                <tr>
                    <td>
                        <?= $item['id']?>
                    </td>
                    <td>
                        <?= $item['position']?>
                    </td>
                    <td>
                        <?= $item['username']?>
                    </td>
                    <td>
                        <?= $item['email']?>
                    </td>
                    <td>
                        <a href="<?= Url::to(['admin/user-update', 'id' => $item['id']]) ?>" class="btn btn-xs btn-primary">Редактировать</a>
                        <a href="<?= Url::to(['admin/user-delete', 'id' => $item['id']]) ?>" class="btn btn-xs btn-danger">Удалить</a>
                    </td>
                </tr>
            <?php endforeach;?>
        </table>

    </div>

</div>




</div>