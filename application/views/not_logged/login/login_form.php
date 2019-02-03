<?php
$username = [
    'name' => 'username',
    'placeholder' => 'Username',
    'class' => 'login',
    'value' => set_value('username')
];

$password = [
    'name' => 'password',
    'placeholder' => 'Password',
    'class' => 'login'
];

$submit = [
    'name' => 'submit',
    'value' => 'LOGIN',
    'class' => 'submit'
]
?>
<?= form_fieldset('Log in your account') ?>
<?= form_open('users/login') ?>
<?= form_input($username) ?>
<?= form_password($password) ?>
<?= form_submit($submit) ?>
<?= form_close() ?>
<?= form_fieldset_close() ?>
<div class="register_link">
    <a href="users/register" class="register_link">
        Don't have an account? Register now!
    </a>
</div>
<?php if ($this->session->flashdata('errmsg')) : ?>
<div class="errmsg">
    <?= $this->session->flashdata('errmsg') ?>
</div>
<?php endif; ?>
