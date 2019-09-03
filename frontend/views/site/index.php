<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;

?>
<div class="site-index">
    <h1>Calculator</h1>

    <div class="row">
        <div class="col-lg-5">
            <div class="alerts"></div>

            <?php $form = ActiveForm::begin(['id' => 'calc-form', 'action' => Url::to(['site/calculate'])]); ?>

            <?= $form->field($model, 'city')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'name')->textInput() ?>

            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'options' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ],
                'dateFormat' => 'yyyy-MM-dd',
            ]) ?>

            <?= $form->field($model, 'persons')->textInput() ?>

            <?= $form->field($model, 'bed_count')->textInput() ?>

            <?= $form->field($model, 'has_child')->checkbox() ?>

            <div class="form-group">
                <?= Html::submitButton('Calculate', ['class' => 'btn btn-primary', 'name' => 'calc-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
