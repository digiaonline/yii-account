<?php
use nordsoftware\yii_account\helpers\Helper;
use nordsoftware\yii_account\Module;

/* @var $this \nordsoftware\yii_account\controllers\SignupController */
/* @var $model \nordsoftware\yii_account\models\form\SignupForm */
/* @var $form \TbActiveForm */
?>
<div class="register-controller index-action">

    <h1><?php echo CHtml::encode(\Yii::app()->name); ?></h1>

    <?php $form = $this->beginWidget(
        '\TbActiveForm',
        array('id' => $this->formId, 'enableAjaxValidation' => true)
    ); ?>

    <p class="register-login help-block">
        <?php echo Helper::t(
            'views', 'If you already have an account &ndash; {loginLink}.',
            array('{loginLink}' => TbHtml::link(Helper::t('views', 'Log In'), array('/account/authenticate/login')))
        ); ?>
    </p>

    <fieldset>
        <?php echo $form->textFieldControlGroup(
            $model,
            'email',
            array('label' => false, 'placeholder' => $model->getAttributeLabel('email'), 'block' => true)
        ); ?>
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
        <?php echo $form->passwordFieldControlGroup(
            $model,
            'verifyPassword',
            array('label' => false, 'placeholder' => $model->getAttributeLabel('verifyPassword'), 'block' => true)
        ); ?>
        <?php if ($model->scenario === 'withCaptcha'): ?>
            <?php echo $form->textFieldControlGroup(
                $model,
                'captcha',
                array(
                    'label' => false,
                    'placeholder' => $model->getAttributeLabel('captcha'),
                    'block' => true,
                    'controlOptions' => array(
                        'before' => $this->widget(
                            $this->module->getClassName(Module::CLASS_CAPTCHA_WIDGET),
                            array(),
                            true
                        )
                    ),
                )
            ); ?>
        <?php endif; ?>
    </fieldset>

    <div class="row">
        <div class="register-submit col-xs-8">
            <?php echo TbHtml::submitButton(
                Helper::t('views', 'Create Account'),
                array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'size' => TbHtml::BUTTON_SIZE_LARGE, 'block' => true)
            ); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>