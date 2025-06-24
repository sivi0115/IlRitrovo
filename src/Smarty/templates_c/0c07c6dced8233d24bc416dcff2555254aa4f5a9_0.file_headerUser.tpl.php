<?php
/* Smarty version 5.5.1, created on 2025-06-24 15:22:45
  from 'file:headerUser.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_685aa6a5d75a42_74942325',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0c07c6dced8233d24bc416dcff2555254aa4f5a9' => 
    array (
      0 => 'headerUser.tpl',
      1 => 1750771360,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_685aa6a5d75a42_74942325 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Applications/XAMPP/xamppfiles/htdocs/IlRitrovo/src/Smarty/tpl';
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Header - Il Ritrovo</title>
    <link href="/IlRitrovo/src/Smarty/css/header.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap" rel="stylesheet" />
  </head>
  <body>
    <header>
      <div class="header-top">
        <!-- Logo e nome del sito -->
        <div class="logo-area">
          <img src="/IlRitrovo/src/Smarty/assets/images/logo/logo.png" alt="Il Ritrovo Logo" class="logo" />
          <span class="site-name">Il Ritrovo</span>
        </div> <!-- /.logo-area-->

        <!-- Barra di navigazione -->
        <nav class="main-nav">
          <ul>
            <li><a href="/IlRitrovo/public/User/showHomePage">Home</a></li>
            <li><a href="/IlRitrovo/public/User/showRoomsPage">Rooms</a></li>
            <li><a href="/IlRitrovo/public/User/showMenuPage">Menu</a></li>
            <li><a href="/IlRitrovo/public/Review/showReviewsPage">Reviews</a></li>
          </ul>
        </nav>

        <!-- Pulsanti utente -->
        <div class="user-area">
          <?php if ($_smarty_tpl->getValue('isLogged')) {?>
            <a href="/IlRitrovo/public/User/showProfile" class="user-button">Profile</a>
            <a href="/IlRitrovo/public/User/logout" class="user-button">Logout</a>
          <?php } else { ?>
            <a href="/IlRitrovo/public/User/showLoginForm" class="user-button">Login</a>
            <a href="/IlRitrovo/public/User/showSignUpForm" class="user-button">Sign up</a>
          <?php }?>
        </div> <!-- /.user-area-->
      </div> <!-- /.header-top-->
    </header>
  </body>
</html><?php }
}
