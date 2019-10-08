<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id, 'user_id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'user_id' => $model->user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description',
            'type',
            'file',
        ],
    ]) ?>

</div>
</div>
<div class="col-md-offset-1 col-md-5" >

    <div id="block" style="height: 400px; overflow-y: scroll;">
    <?php foreach ($messages as $message): ?>
    <h4><b><?= $message->user->username ?></b> <?= $message->date?></h4>
    <p><?= $message->text?></p>
    <?php endforeach; ?>
    </div>

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($newMessage, 'text') ?>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    var block = document.getElementById("block");
    block.scrollTop = block.scrollHeight;
</script>