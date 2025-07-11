<?php
/* Smarty version 5.5.1, created on 2025-06-24 12:23:17
  from 'file:signUp.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_685a7c957640a0_47809381',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '035d76e8047099586b639db5a9acb32b5d8cdc08' => 
    array (
      0 => 'signUp.tpl',
      1 => 1750760594,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_685a7c957640a0_47809381 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Users/marco/public_html/Progetto/IlRitrovo/src/Smarty/tpl';
?><!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign Up - Il Ritrovo</title>
    <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/loginSignup.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap" rel="stylesheet" />
    </head>
    <body>

        <!-- Header incluso tramite View -->

        <div class="modal" role="dialog" aria-labelledby="signup-title" aria-modal="true">
            <div class="modal-content">
                <h2 id="signup-title">Create your account</h2>
                <form action="signupHandler.php" method="POST">
                    <label for="username">Username</label>
                    <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    minlength="3"
                    maxlength="20"
                    title="Username must be 3-20 characters, letters, numbers and underscore only"
                    autocomplete="username"
                    />
                    <label for="email">Email</label>
                    <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    autocomplete="email"
                    />
                    <label for="name">Name</label>
                    <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    autocomplete="given-name"
                    />
                    <label for="surname">Surname</label>
                    <input
                    type="text"
                    id="surname"
                    name="surname"
                    required
                    autocomplete="family-name"
                    />
                    <label for="birthDate">Birth Date</label>
                    <input
                    type="date"
                    id="birthDate"
                    name="birthDate"
                    required
                    max="<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('date_format')(time(),'%Y-%m-%d');?>
"
                    autocomplete="bday"
                    />
                    <label for="phone">Phone</label>
                    <input
                    type="tel"
                    id="phone"
                    name="phone"
                    placeholder="+391234567890"
                    required
                    title="Phone number must contain 8 to 15 digits, may start with +"
                    autocomplete="tel"
                    />
                    <label for="password">Password</label>
                    <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    minlength="8"
                    aria-describedby="passwordHelp"
                    autocomplete="new-password"
                    />
                    <div id="passwordHelp" class="password-requirements" aria-live="polite">
                        <p>Password must include:</p>
                        <ul>
                            <li id="length" class="invalid">At least 8 characters</li>
                            <li id="uppercase" class="invalid">At least one uppercase letter</li>
                            <li id="lowercase" class="invalid">At least one lowercase letter</li>
                            <li id="number" class="invalid">At least one number</li>
                            <li id="special" class="invalid">At least one special character</li>
                        </ul>
                    </div> <!-- /.password requirements-->

                    <button type="submit">Sign Up</button>
                </form>
                <p>
                    Already have an account? <a href="CFrontController.php?controller=CUser&task=showLoginPage">Login here</a>.
                </p>
            </div> <!-- /.modal-content-->
        </div> <!-- /.modal-->

        <?php echo '<script'; ?>
>
            // Prendo il campo input della password dal DOM tramite il suo id 'password'
            const passwordInput = document.getElementById('password');

            // Prendo i vari elementi della lista dei requisiti password per poterli aggiornare dinamicamente
            const lengthReq = document.getElementById('length');
            const uppercaseReq = document.getElementById('uppercase');
            const lowercaseReq = document.getElementById('lowercase');
            const numberReq = document.getElementById('number');
            const specialReq = document.getElementById('special');

            // Aggiungo un listener che reagisce all'evento 'input' del campo password, cioè ogni volta che l'utente digita qualcosa
            passwordInput.addEventListener('input', () => {
            // Prendo il valore corrente digitato nella password
            const val = passwordInput.value;

            // Controllo se la lunghezza è almeno 8 caratteri
            if (val.length >= 8) {
                // Se la condizione è soddisfatta, aggiungo la classe 'valid' e rimuovo 'invalid'
                lengthReq.classList.add('valid');
                lengthReq.classList.remove('invalid');
            } else {
                // Altrimenti faccio l'opposto
                lengthReq.classList.add('invalid');
                lengthReq.classList.remove('valid');
            }

            // Controllo se la password contiene almeno una lettera maiuscola usando una regex
            if (/[A-Z]/.test(val)) {
                uppercaseReq.classList.add('valid');
                uppercaseReq.classList.remove('invalid');
            } else {
                uppercaseReq.classList.add('invalid');
                uppercaseReq.classList.remove('valid');
            }

            // Controllo se la password contiene almeno una lettera minuscola
            if (/[a-z]/.test(val)) {
                lowercaseReq.classList.add('valid');
                lowercaseReq.classList.remove('invalid');
            } else {
                lowercaseReq.classList.add('invalid');
                lowercaseReq.classList.remove('valid');
            }

            // Controllo se la password contiene almeno un numero
            if (/\d/.test(val)) {
                numberReq.classList.add('valid');
                numberReq.classList.remove('invalid');
            } else {
                numberReq.classList.add('invalid');
                numberReq.classList.remove('valid');
            }

            // Controllo se la password contiene almeno un carattere speciale (non alfanumerico)
            if (/[^a-zA-Z0-9]/.test(val)) {
                specialReq.classList.add('valid');
                specialReq.classList.remove('invalid');
            } else {
                specialReq.classList.add('invalid');
                specialReq.classList.remove('valid');
            }
            });
        <?php echo '</script'; ?>
>
    </body>
</html><?php }
}
