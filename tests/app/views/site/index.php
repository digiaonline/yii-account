<?php
/* @var $this SiteController */
?>
<div class="site-controller index-action">
    <h1><?php echo CHtml::encode(Yii::app()->name); ?></h1>

    <p class="lead">This is the test application used for running automated tests on the yii-account project.</p>

    <ul class="list-unstyled">
        <?php if (Yii::app()->user->isGuest): ?>
            <li><?php echo TbHtml::link('Register', array('/account/register')); ?></li>
            <li><?php echo TbHtml::link('Login', array('/account/authenticate/login')); ?></li>
        <?php else: ?>
            <li><?php echo TbHtml::link('Logout', array('/account/authenticate/logout'), array('id' => 'logoutLink')); ?></li>
        <?php endif; ?>
    </ul>
</div>