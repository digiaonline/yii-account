<?php
/* @var $this SiteController */
?>
<div class="site-controller index-action">
    This is the site/index action.

    <ul>
        <?php if (Yii::app()->user->isGuest): ?>
            <li><?php echo TbHtml::link('Register', array('/account/register')); ?></li>
            <li><?php echo TbHtml::link('Login', array('/account/login')); ?></li>
        <?php else: ?>
            <li><?php echo TbHtml::link('Logout', array('/account/logout')); ?></li>
        <?php endif; ?>
    </ul>
</div>