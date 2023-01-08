<?php
use core\form\Form as Form;

?>

<h1>Регистрация в системе</h1>
<?php $form = Form::begin("/register", "post"); ?>
<div class="row">
    <div class="col"><?= $form->field($model, "firstname"); ?></div>
    <div class="col"><?= $form->field($model, "lastname"); ?></div>
</div>
<?= $form->field($model, "email"); ?>
<?= $form->field($model, "password"); ?>
<?= $form->field($model, "passwordConfirm"); ?>
<br />
<button type="submit" class="btn btn-primary">Регистрация</button>
<?= Form::end(); ?>
