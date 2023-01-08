<?php
use core\form\Form as Form;

?>

<h1>Авторизация в системе</h1>
<?php $form = Form::begin("/login", "post"); ?>
<?= $form->field($model, "email"); ?>
<?= $form->field($model, "password"); ?>
<br />
<button type="submit" class="btn btn-primary">Авторизация</button>
<?= Form::end(); ?>
