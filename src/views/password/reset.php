<?php
use nordsoftware\yii_account\helpers\Helper;

/* @var $this \nordsoftware\yii_account\controllers\PasswordController */
/* @var $model \nordsoftware\yii_account\models\form\ResetPasswordForm */
/* @var $form \TbActiveForm */
?>
<div class="password-controller change-action">

    <h1><?php echo CHtml::encode(Yii::app()->name); ?></h1>

    <p class="help-block">
        <?php echo Helper::t(
            'views',
            'Please enter a new password twice to change the password for your account.'
        ); ?>
    </p>

    <?php $form = $this->beginWidget(
        '\TbActiveForm',
        array('id' => $this->resetFormId)
    ); ?>

    <fieldset>
        <?php echo $form->passwordFieldControlGroup(
            $model,
            'password',
            array('label' => false, 'placeholder' => $model->getAttributeLabel('password'), 'block' => true)
        ); ?>
        <?php echo $form->passwordFieldControlGroup(
            $model,
            'verifyPassword',
            array('label' => false, 'placeholder' => $model->getAttributeLabel('verifyPassword'), 'block' => true)
        ); ?>
    </fieldset>

    <div class="row">
        <div class="forgot-submit col-xs-8">
            <?php echo TbHtml::submitButton(
                Helper::t('views', 'Change Password'),
                array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'size' => TbHtml::BUTTON_SIZE_LARGE, 'block' => true)
            ); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>