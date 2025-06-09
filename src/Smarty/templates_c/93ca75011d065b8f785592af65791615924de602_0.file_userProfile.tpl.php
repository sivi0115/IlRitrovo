<?php
/* Smarty version 5.5.1, created on 2025-06-09 10:51:08
  from 'file:userProfile.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6846a07cede0d9_48453431',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '93ca75011d065b8f785592af65791615924de602' => 
    array (
      0 => 'userProfile.tpl',
      1 => 1749459067,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6846a07cede0d9_48453431 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Users/marco/public_html/Progetto/IlRitrovo/src/Smarty/tpl';
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">       
        <!-- Template Stylesheet -->
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/styles.css" rel="stylesheet">
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/user.css" rel="stylesheet">
    </head>

    <body style="background-color: #f8f1e8;">
        <!-- Header -->
        
        <!-- Profile -->
        <div class="panel panel-default">
            <div class="panel-heading">User Profile</div>
            <div class="panel-body">
                <div class="profile-container">
                    <!-- Profile Image -->
                    <div class="profile-image-section">
                        <img src="../assets/images/logo/user.jpg" alt="Test User" class="profile-img current">
                        <a href="CFrontController.php?controller=CUser&task=showEditProfileImage" class="edit-image-btn" aria-label="Modifica immagine profilo">✎</a>
                    </div> <!-- /.profile-image-section-->
                    <!-- Metadata Section -->
                    <div class="profile-info-section">
                        <!-- Username -->
                        <div class="username-header">
                            <h3><strong>Username: </strong><?php echo $_smarty_tpl->getValue('username');?>
</h3>
                        </div> <!-- /.username-header-->
                        <!-- Group 1: Username + Email and Password -->
                        <div class="user-info-group">
                            <div class="credentials-row">
                                <div class="credential-item"><strong>Email: </strong><?php echo $_smarty_tpl->getValue('email');?>
</div>
                                <div class="credential-item"><strong>Password: </strong>********</div>
                            </div> <!-- /.credentials-row-->
                            <div class="form-action-right">
                                <a href="CFrontController.php?controller=CUser&task=showEditProfileMetadata" class="edit-btn">Edit</a>
                            </div> <!-- /.form-action-right-->
                            <?php if ($_smarty_tpl->getValue('edit_section') == 'metadata') {?>
                                <form method="post" action="CFrontController.php?controller=CUser&task=editProfileMetadata" class="edit-form open">
                                    <div class="credentials-row">
                                        <div class="credential-item">
                                            <label>Username</label>
                                            <input type="text" name="username" value="<?php echo $_smarty_tpl->getValue('username');?>
" required>
                                        </div> <!-- /.credential-item-->
                                        <div class="credential-item">
                                            <label>Email</label>
                                            <input type="email" name="email" value="<?php echo $_smarty_tpl->getValue('email');?>
" required>
                                        </div> <!-- /.credential-item-->
                                    </div> <!-- /.credentials-row-->
                                    <div class="credentials-row">
                                        <div class="credential-item">
                                            <label>Password</label>
                                            <input type="password" name="password" required>
                                        </div> <!-- /.credential-item-->
                                    </div> <!-- /.credentials-row-->
                                    <div class="form-action-right">
                                        <button type="submit" class="edit-btn">Save access data</button>
                                    </div> <!-- /.form-action-right-->
                                </form>
                            <?php }?>
                        </div> <!-- /.user-info-group-->
                        <hr class="separator">
                        <!-- Gruppo 2: Dati personali -->
                        <div class="user-info-group">
                            <div class="personal-data-row">
                                <div class="personal-item"><strong>Name:</strong> <?php echo $_smarty_tpl->getValue('name');?>
</div>
                                <div class="personal-item"><strong>Surname:</strong> <?php echo $_smarty_tpl->getValue('surname');?>
</div>
                            </div> <!-- /.personal-data-row-->
                            <div class="personal-data-row">
                                <div class="personal-item"><strong>Birth Date:</strong> <?php echo $_smarty_tpl->getValue('birthdate');?>
</div>
                                <div class="personal-item"><strong>Phone:</strong> <?php echo $_smarty_tpl->getValue('phone');?>
</div>
                            </div> <!-- /.personal-data-row-->
                            <div class="form-action-right">
                                <a href="CFrontController.php?controller=CUser&task=showEditProfileData" class="edit-btn">Edit</a>
                            </div><!-- /.form-action-right-->
                            <?php if ($_smarty_tpl->getValue('edit_section') == 'data') {?>
                                <form method="post" action="CFrontController.php?controller=CUser&task=editProfileData" class="edit-form open">
                                    <div class="personal-data-row">
                                        <div class="personal-item">
                                            <label>Name</label>
                                            <input type="text" name="name" value="<?php echo $_smarty_tpl->getValue('name');?>
" required>
                                        </div> <!-- /.personal-data-item-->
                                        <div class="personal-item">
                                            <label>Surname</label>
                                            <input type="text" name="surname" value="<?php echo $_smarty_tpl->getValue('surname');?>
" required>
                                        </div> <!-- /.personal-data-item-->
                                    </div> <!-- /.personal-data-row-->
                                    <div class="personal-data-row">
                                        <div class="personal-item">
                                            <label>Birth Date</label>
                                            <input type="date" name="birthdate" value="<?php echo $_smarty_tpl->getValue('birthdate');?>
" required>
                                        </div> <!-- /.personal-data-item-->
                                        <div class="personal-item">
                                            <label>Phone</label>
                                            <input type="tel" name="phone" value="<?php echo $_smarty_tpl->getValue('phone');?>
" required>
                                        </div> <!-- /.personal-data-item-->
                                    </div> <!-- /.personal-data-row-->
                                    <div class="form-action-right">
                                        <button type="submit" class="edit-btn">Save Data</button>
                                    </div> <!-- /.form-action-right-->
                                </form>
                            <?php }?>
                        </div> <!-- /.user-info-group-->
                    </div> <!-- /.profile-info-section-->
                </div> <!-- /.profile-container-->
            </div> <!-- /.panel-body-->
        </div> <!-- /.panel panel-default-->

        <!-- Cards -->
        <div class="panel">
            <div class="panel-heading">My Credit Cards</div>
            <div class="card-row">
                <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('cards'), 'card');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('card')->value) {
$foreach0DoElse = false;
?>
                <?php $_smarty_tpl->assign('cardClass', $_smarty_tpl->getSmarty()->getModifierCallback('regex_replace')(mb_strtolower((string) $_smarty_tpl->getValue('card')->getType(), 'UTF-8'),'/[^a-z]/',''), false, NULL);?>
                    <div class="credit-card <?php echo $_smarty_tpl->getValue('cardClass');?>
">
                        <div class="card-header"><?php echo $_smarty_tpl->getValue('card')->getType();?>
</div>
                        <div class="card-body">
                            <ul>
                                <li><strong>Number:</strong> **** **** **** <?php echo substr((string) $_smarty_tpl->getValue('card')->getNumber(), (int) -4);?>
</li>
                                <li><strong>Holder:</strong> <?php echo $_smarty_tpl->getValue('card')->getHolder();?>
</li>
                                <li><strong>Expiration:</strong> <?php echo $_smarty_tpl->getValue('card')->getExpiration();?>
</li>
                            </ul>
                            <div class="form-action-right">
                                <a href="?action=editCard&id=<?php echo $_smarty_tpl->getValue('card')->getIdCreditCard();?>
" class="card-edit-btn">Edit</a>
                                <a href="?action=deleteCard&id=<?php echo $_smarty_tpl->getValue('card')->getIdCreditCard();?>
" 
                                class="card-delete-btn" 
                                onclick="return confirm('Do you really wat to delete this card?');">Delete</a>
                            </div> <!-- /.form-action-right-->
                        </div> <!-- /.card-body-->
                    </div> <!-- /.credit-card-class-->
                <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
                <div class="credit-card add-card-btn" onclick="location.href='?action=addCard'" title="Aggiungi nuova carta">
                    <div class="card-header" style="text-align:center; font-size:2.5rem; cursor:pointer; user-select:none; color:#ff9f43;">+</div>
                </div> <!-- /credit-card add-card-btn-->
            </div> <!-- /.card-row-->
            <?php if ($_smarty_tpl->getValue('showForm')) {?>
                <form method="post" action="<?php echo $_smarty_tpl->getValue('formAction');?>
" class="card-form">
                    <label for="cardType">Type</label>
                    <select name="cardType" id="cardType" required>
                        <option value="">Select type</option>
                        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('allowedTypes'), 'type');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('type')->value) {
$foreach1DoElse = false;
?>
                            <option value="<?php echo $_smarty_tpl->getValue('type');?>
" <?php if ($_smarty_tpl->getValue('cardData')['type'] == $_smarty_tpl->getValue('type')) {?>selected<?php }?>><?php echo $_smarty_tpl->getValue('type');?>
</option>
                        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
                    </select>
                    <label for="cardNumber">Number</label>
                    <input type="text" name="cardNumber" id="cardNumber" maxlength="19" placeholder="XXXX XXXX XXXX XXXX" required value="<?php echo (($tmp = $_smarty_tpl->getValue('cardData')['number'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
">
                    <label for="cardHolder">Holder</label>
                    <input type="text" name="cardHolder" id="cardHolder" required value="<?php echo (($tmp = $_smarty_tpl->getValue('cardData')['holder'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
">
                    <label for="expiryDate">Expiration (MM/AA)</label>
                    <input type="text" name="expiryDate" id="expiryDate" maxlength="5" placeholder="MM/AA" required value="<?php echo (($tmp = $_smarty_tpl->getValue('cardData')['expiration'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
">
                    <div class="form-action-right">
                        <button type="submit" name="save" class="save-btn">Save</button>
                        <a href="<?php echo $_smarty_tpl->getValue('cancelUrl');?>
" class="cancel-btn">Cancel</a>
                    </div> <!-- /.form-action-right-->
                </form>
            <?php }?>
        </div> <!-- /.panel-->

        <!-- Reservations-->

        <!-- Footer-->
            </body>
</html><?php }
}
