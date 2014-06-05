<?php

use nordsoftware\yii_account\helpers\Helper;

/* @var $this \nordsoftware\yii_account\controllers\SignupController */
/* @var $activateUrl string */
?>
<?php echo Helper::t('email', 'Thank you for signing up!') ;?><br>
<br>
<?php echo Helper::t('email', 'Please click the link below to activate your account:'); ?><br>
<?php echo CHtml::link($activateUrl, $activateUrl); ?>