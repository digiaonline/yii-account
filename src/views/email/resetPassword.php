<?php

use nordsoftware\yii_account\helpers\Helper;

/* @var $this \nordsoftware\yii_account\controllers\PasswordController */
/* @var $resetUrl string */
?>
<?php echo Helper::t('email', 'Reset password'); ?><br>
<br>
<?php echo Helper::t('email', 'Please click the link below to reset the password for your account:'); ?><br>
<?php echo CHtml::link($resetUrl, $resetUrl); ?>