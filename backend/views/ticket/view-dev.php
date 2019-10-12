<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="col-md-6">
    <div class="ticket-view">

        <b><?= Html::encode($this->title) ?></b>


        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'user_id' => $model->user_id], [
            'class' => 'btn-xs btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <br><br>

        <table class="table table-bordered table-striped">
            <tr>
                <th>Name</th>
                <td><?= $model->name ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?= $model->description ?></td>
            </tr>
            <tr>
                <th>Type</th>
                <td><?= $model->type ?></td>
            </tr>
            <tr>
                <th>File</th>
                <td><?= '<a href="http://helpdesk/public_html/upload/'.$model->file.'">' .$model->file .'</a>' ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <?php
                        switch ($model->status)
                        {
                            case 0:
                                echo 'Новая заявка';break;
                            case 1:
                                echo 'В рассмотрении';break;
                            case 2:
                                echo 'В процессе';break;
                            case 3:
                                echo 'Выполнена';break;
                            case 5:
                                echo 'Отклонена. Причина: ' . $model->deny_reason;break;
                        }

                    ?>
                </td>
            </tr>
        </table>
        <div id="info" style="display: none"><p>Статус изменен</p></div>


        <h3>Tasks</h3>
                <ol>
                    <?php foreach ($tasks as $task):?>
                    <li><?= $task->name.' '.$task->description.' '.$task->deadline.' '.$task->user->username .'('.$task->user->position.')' ?>
                        <?php endforeach; ?>
                </ol>



    </div>
</div>

<!--Чаты-->
<div class="col-md-offset-1 col-md-5" >

    <!--адм чат-->
    <div id="block" style="height: 400px; overflow-y: scroll;">
        <?php foreach ($messagesDev as $message): ?>
            <h4><b><?= $message->user->username ?></b> <?= $message->date ?></h4>
            <p><?= $message->text ?></p>
        <?php endforeach; ?>
    </div>

    <!--отправить сообщение-->

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($newMessageDev, 'text') ?>
    <input type="hidden" value="true" name="devmsg">
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>


<script type="text/javascript">
    //scroll for chat
    var block = document.getElementById("block");
    block.scrollTop = block.scrollHeight;
    var block2 = document.getElementById("block2");
    block2.scrollTop = block2.scrollHeight;
</script>
</script>
<script src="/public_html/admin/assets/334d6878/jquery.js"></script>
<script>
    $('#myTab a[href="#newtask"]').tab('show') // Select tab by name
    $('#myTab a:first').tab('show') // Select first tab

    $('#myTab a[href="#dev"]').tab('show') // Select tab by name
    $('#myTab a:first').tab('show') // Select first tab
</script>
<script>
    //function for change deny reason info
    function getDenyReason(result) {
        if(result !== null)
        {
            return $('#deny_info').append('Причина отказа: '+result);
        }else{
            return $('#deny_info').empty();
        }
    }
    $( document ).ready(function() {
        //auto choose option by status
        $('select option[value="<?= $model->status ?>"]').prop('selected', true);

        //function for change deny reason info for start
        if('<?= $model->deny_reason ?>' != '')
        {
            console.log('suka');
            $('#deny_info').append('Причина отказа: <?= $model->deny_reason ?>');
        }else
        {
            $('#deny_info').empty();
        }

        //change status on back for every change option
        $('select#status').on('change', function() {
            if(this.value == 5)
            {
                return $('#deny').show();
            }else
            {
                $.ajax({
                    type: 'post',
                    url: '<?= Url::to(['ticket/view', 'id' => $model->id])?>',
                    data: 'status='+this.value
                }).done(function(result) {
                    getDenyReason(null);
                    $('#info').show().hide(2000);
                    console.log('success');
                }).fail(function() {
                    console.log('fail');
                });

                return $('#deny').hide();
            }
        });

        //if form submited deny reason
        $('#deny').submit(function(e) {
            var $form = $(this);
            $.ajax({
                type: 'post',
                url: '<?= Url::to(['ticket/view', 'id' => $model->id])?>',
                data: $form.serialize()
            }).done(function(result) {
                getDenyReason(result);
                $('#deny').hide('3000');
                console.log('success');
            }).fail(function() {
                console.log('fail');
            });
            //отмена действия по умолчанию для кнопки submit
            e.preventDefault();
        });
    });
</script>