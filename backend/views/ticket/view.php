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
                <select id="status">
                <option value="0">Новая заявка</option>
                <option value="1">В рассмотрение</option>
                <option value="2">В процессе</option>
                <option value="3">Выполнена</option>
                <option value="5">Отклонена</option>
                </select>

                <div id="deny_info"></div>
                <form id="deny" style="display: none">
                    <br>
                    причина отклонения
                    <input name="reason" value="">
                    <input type="submit" value="Отклонить">
                </form>
            </td>
        </tr>
    </table>
    <div id="info" style="display: none"><p>Статус изменен</p></div>

    <!--таски-->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#newtask" data-toggle="tab">New Task</a></li>
        <li><a href="#tasks" data-toggle="tab">Tasks</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active" id="newtask">
            <br><br>
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal', 'id' =>'add'],
                'fieldConfig' => [
                    'template' => "<div class=\"col-lg-3\">{label}</div>\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-lg-12 col-lg-offset-3\">{error}</div>",
                    'labelOptions' => ['class' => ''],
                ],

            ]); ?>
            <?= $form->field($newTask, 'name') ?>
            <?= $form->field($newTask, 'description')->textarea() ?>
            <?= $form->field($newTask, 'user_id')->dropDownList(ArrayHelper::map(User::find()->all(), 'id', 'username')) ?>
            <?= $form->field($newTask, 'deadline')->textInput(['type' => 'date'])  ?>
            <input type="hidden" name="task" value="1">
            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="tab-pane fade" id="tasks">
            <br><br>
            <ol>
            <?php foreach ($tasks as $task):?>
                <li><?= $task->name.' '.$task->description.' '.$task->deadline.' '.$task->user->username ?>
            <?php endforeach; ?>
            </ol>
        </div>
    </div>


</div>
</div>

<!--Чаты-->
<div class="col-md-offset-1 col-md-5" >

    <ul class="nav nav-tabs">
        <li class="active"><a href="#dev" data-toggle="tab">Dev msg</a></li>
        <li><a href="#user" data-toggle="tab">User msg</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="dev">
            <br>
            <!--адм чат-->
            <div id="block" style="height: 400px; overflow-y: scroll;">
                <?php foreach ($messagesDev as $message): ?>
                    <h4><b><?= $message->user->username ?></b> <?= $message->date?></h4>
                    <p><?= $message->text?></p>
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
        <div class="tab-pane fade" id="user">
            <br>
            <!--user чат-->
            <div id="block2" style="height: 400px; overflow-y: scroll;">
                <?php foreach ($messagesUser as $message): ?>
                    <h4><b><?= $message->user->username ?></b> <?= $message->date?></h4>
                    <p><?= $message->text?></p>
                <?php endforeach; ?>
            </div>

            <!--отправить сообщение-->
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($newMessageUser, 'text') ?>
            <input type="hidden" value="true" name="usermsg">
            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="tab-pane" id="messages">...</div>
        <div class="tab-pane" id="settings">...</div>
    </div>


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