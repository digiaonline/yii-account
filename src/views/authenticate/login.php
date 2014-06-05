<?php
use nordsoftware\yii_account\helpers\Helper;

/* @var $this \nordsoftware\yii_account\controllers\AuthenticateController */
/* @var $model \nordsoftware\yii_account\models\form\LoginForm */
/* @var $form \TbActiveForm */
?>
<div class="authenticate-controller login-action">

    <h1><?php echo CHtml::encode(Yii::app()->name); ?></h1>

    <?php $form = $this->beginWidget(
        '\TbActiveForm',
        array('id' => $this->loginFormId)
    ); ?>

    <fieldset>
        <?php echo $form->textFieldControlGroup(
            $model,
            'username',
            array('label' => false, 'placeholder' => $model->getAttributeLabel('username'), 'block' => true)
        ); ?>
        <?php echo $form->passwordFieldControlGroup(
            $model,
            'password',
            array('label' => false, 'placeholder' => $model->getAttributeLabel('password'), 'block' => true)
        ); ?>
    </fieldset>

    <div class="row">
        <div class="login-submit col-xs-6">
            <?php echo TbHtml::submitButton(
                Helper::t('views', 'Log In'),
                array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'size' => TbHtml::BUTTON_SIZE_LARGE, 'block' => true)
            ); ?>
        </div>
        <div class="login-stay-logged-in col-xs-6">
            <?php echo $form->checkBoxControlGroup($model, 'stayLoggedIn'); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

    <ul class="login-links list-unstyled">
        <li><?php echo TbHtml::link(Helper::t('views', 'Create an account'), array('/account/signup/index')); ?></li>
        <li>|</li>
        <li><?php echo TbHtml::link(Helper::t('views', 'Forgot password'), array('/account/password/forgot')); ?></li>
    </ul>

</div>