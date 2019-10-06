<?php
foreach ($tickets as $ticket)
{
    echo $ticket->name . ' <a href="'.\yii\helpers\Url::to(['ticket/view', 'id' => $ticket->id]).'" >ticket '.$ticket->id.'</a>';
}
?>