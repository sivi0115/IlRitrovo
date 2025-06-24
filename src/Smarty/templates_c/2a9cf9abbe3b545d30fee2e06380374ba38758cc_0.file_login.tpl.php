<?php
/* Smarty version 5.5.1, created on 2025-06-24 16:04:42
  from 'file:login.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_685ab07aa63395_78243025',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2a9cf9abbe3b545d30fee2e06380374ba38758cc' => 
    array (
      0 => 'login.tpl',
      1 => 1750773871,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_685ab07aa63395_78243025 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Applications/XAMPP/xamppfiles/htdocs/IlRitrovo/src/Smarty/tpl';
?><!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Il Ritrovo</title>
    <link href="/IlRitrovo/src/Smarty/css/loginSignup.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap" rel="stylesheet" />
    </head>
    <body>

        <div class="modal">
            <div class="modal-content">
                <h2>Login</h2>
                <form action="/IlRitrovo/public/User/checkLogin" method="POST">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required />

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />

                    <button type="submit">Login</button>
                </form>
                <p>Don't have an account? <a href="CFrontController.php?controller=CUser&task=showSignupPage">Sign up here</a></p>
            </div> <!-- /.modal-content-->
        </div> <!-- /.modal-->
        
    </body>
</html><?php }
}
