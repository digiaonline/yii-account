<?php
use nordsoftware\yii_account\helpers\Helper;

/* @var $this \nordsoftware\yii_account\controllers\RegisterController */
?>
<div class="register-controller done-action">

    <h1><?php echo CHtml::encode(Yii::app()->name); ?></h1>

    <p class="lead"><?php echo Helper::t('views', 'Thank you for registering!'); ?></p>

    <p><?php echo Helper::t('views', 'You will soon receive an email with instructions on how to activate your account.') ;?></p>

</div>