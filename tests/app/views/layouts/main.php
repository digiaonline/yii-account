<?php
/* @var $this Controller */
/* @var $content string */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php Yii::app()->yiistrap->register(); ?>
</head>
<body>
    <div class="container">
        <?php echo $content; ?>
    </div>
</body>
</html>