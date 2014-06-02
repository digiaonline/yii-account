<?php

use nordsoftware\yii_account\helpers\Helper;

/* @var $this \nordsoftware\yii_account\controllers\PasswordController */
/* @var $model \nordsoftware\yii_account\models\form\RecoverPasswordForm */
/* @var $form \TbActiveForm */
?>
<div class="password-controller forgot-action">

    <h1><?php echo CHtml::encode(\Yii::app()->name); ?></h1>

    <p class="help-block">
        <?php echo Helper::t(
            'views',
            'Please enter your email and we will send you instructions on how to recover your account.'
        ); ?>
    </p>

    <?php $form = $this->beginWidget(
        '\TbActiveForm',
        array('id' => $this->forgotFormId)
    ); ?>

    <fieldset>
        <?php echo $form->textFieldControlGroup(
            $model,
            'email',
            array('label' => false, 'placeholder' => $model->getAttributeLabel('email'), 'block' => true)
        ); ?>
    </fieldset>

    <div class="row">
        <div class="forgot-submit col-xs-8">
            <?php echo TbHtml::submitButton(
                Helper::t('views', 'Recover Account'),
                array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'size' => TbHtml::BUTTON_SIZE_LARGE, 'block' => true)
            ); ?>
        </div>
        <div class="forgot-cancel col-xs-4">
            <?php echo TbHtml::linkButton(
                Helper::t('views', 'Cancel'),
                array(
                    'url' => array('/account/authenticate/login'),
                    'color' => TbHtml::BUTTON_COLOR_LINK,
                    'size' => TbHtml::BUTTON_SIZE_LARGE,
                    'block' => true,
                )
            ); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>