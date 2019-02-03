<?php
$username = [
    'name' => 'username',
    'class' => 'register',
    'placeholder' => 'Username',
    'value' => set_value('username')
];

$password = [
    'name' => 'password',
    'class' => 'register',
    'placeholder' => 'Password'
];

$confirm_password = [
    'name' => 'confirm_password',
    'class' => 'register',
    'placeholder' => 'Confirm Password'
];

$fname = [
    'name' => 'fname',
    'class' => 'register',
    'placeholder' => 'First Name',
    'value' => set_value('fname')
];

$lname = [
    'name' => 'lname',
    'class' => 'register',
    'placeholder' => 'Last Name',
    'value' => set_value('lname')
];

$email = [
    'name' => 'email',
    'class' => 'register',
    'type' => 'email',
    'placeholder' => 'Email',
    'value' => set_value('email')
];

$submit = [
    'name' => 'submit',
    'class' => 'submit',
    'value' => 'Register'
];
?>

<?= $this->session->flashdata('reg_err') ?>
<?= form_fieldset('Register') ?>
<?= form_open('users/validate_register') ?>

<?= form_input($username) ?>
<?= form_error('username') ?>
<?= form_password($password) ?>
<?= form_error('password') ?>
<?= form_password($confirm_password) ?>
<?= form_error('confirm_password') ?>
<?= form_input($fname) ?>
<?= form_error('fname') ?>
<?= form_input($lname) ?>
<?= form_error('lname') ?>
<?= form_input($email) ?>
<?= form_error('email') ?>
<?= form_submit($submit) ?>

<?= form_close() ?>
<?= form_fieldset_close() ?>
<div class="d-flex justify-content-center links">
    You have an account?&nbsp;<a href="<?= base_url() ?>users">Login</a>
</div>
