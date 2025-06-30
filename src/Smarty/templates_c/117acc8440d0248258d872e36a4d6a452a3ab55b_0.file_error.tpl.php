<?php
/* Smarty version 5.5.1, created on 2025-06-30 10:00:32
  from 'file:error.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_68624420d26816_15643595',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '117acc8440d0248258d872e36a4d6a452a3ab55b' => 
    array (
      0 => 'error.tpl',
      1 => 1751270406,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_68624420d26816_15643595 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Applications/XAMPP/xamppfiles/htdocs/IlRitrovo/src/Smarty/tpl';
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Error</title>
        <link href="../css/styles.css" rel="stylesheet">
        <?php echo '<script'; ?>
>
            function goBackProperly() {
                if (document.referrer) {
                    window.location.href = document.referrer; // reload da server
                } else {
                    window.history.back(); // fallback se referrer non disponibile
                }
            }
        <?php echo '</script'; ?>
>
    </head>
    <body>
        <div class="error-container">
            <h1>An error has occurred: </h1>
            <p><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('errorMessage'), ENT_QUOTES, 'UTF-8', true);?>
</p>
            <p><a href="#" onclick="goBackProperly(); return false;">Back</a></p>
        </div>
    </body>
</html><?php }
}
