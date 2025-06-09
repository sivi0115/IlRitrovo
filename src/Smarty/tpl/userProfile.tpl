<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">       
        <!-- Template Stylesheet -->
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/styles.css" rel="stylesheet">
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/user.css" rel="stylesheet">
    </head>

    <body style="background-color: #f8f1e8;">
        <!-- Header -->
        {* {include file='headerUser.tpl'} *}

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
                            <h3><strong>Username: </strong>{$username}</h3>
                        </div> <!-- /.username-header-->
                        <!-- Group 1: Username + Email and Password -->
                        <div class="user-info-group">
                            <div class="credentials-row">
                                <div class="credential-item"><strong>Email: </strong>{$email}</div>
                                <div class="credential-item"><strong>Password: </strong>********</div>
                            </div> <!-- /.credentials-row-->
                            <div class="form-action-right">
                                <a href="CFrontController.php?controller=CUser&task=showEditProfileMetadata" class="edit-btn">Edit</a>
                            </div> <!-- /.form-action-right-->
                            {if $edit_section == 'metadata'}
                                <form method="post" action="CFrontController.php?controller=CUser&task=editProfileMetadata" class="edit-form open">
                                    <div class="credentials-row">
                                        <div class="credential-item">
                                            <label>Username</label>
                                            <input type="text" name="username" value="{$username}" required>
                                        </div> <!-- /.credential-item-->
                                        <div class="credential-item">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{$email}" required>
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
                            {/if}
                        </div> <!-- /.user-info-group-->
                        <hr class="separator">
                        <!-- Gruppo 2: Dati personali -->
                        <div class="user-info-group">
                            <div class="personal-data-row">
                                <div class="personal-item"><strong>Name:</strong> {$name}</div>
                                <div class="personal-item"><strong>Surname:</strong> {$surname}</div>
                            </div> <!-- /.personal-data-row-->
                            <div class="personal-data-row">
                                <div class="personal-item"><strong>Birth Date:</strong> {$birthdate}</div>
                                <div class="personal-item"><strong>Phone:</strong> {$phone}</div>
                            </div> <!-- /.personal-data-row-->
                            <div class="form-action-right">
                                <a href="CFrontController.php?controller=CUser&task=showEditProfileData" class="edit-btn">Edit</a>
                            </div><!-- /.form-action-right-->
                            {if $edit_section == 'data'}
                                <form method="post" action="CFrontController.php?controller=CUser&task=editProfileData" class="edit-form open">
                                    <div class="personal-data-row">
                                        <div class="personal-item">
                                            <label>Name</label>
                                            <input type="text" name="name" value="{$name}" required>
                                        </div> <!-- /.personal-data-item-->
                                        <div class="personal-item">
                                            <label>Surname</label>
                                            <input type="text" name="surname" value="{$surname}" required>
                                        </div> <!-- /.personal-data-item-->
                                    </div> <!-- /.personal-data-row-->
                                    <div class="personal-data-row">
                                        <div class="personal-item">
                                            <label>Birth Date</label>
                                            <input type="date" name="birthdate" value="{$birthdate}" required>
                                        </div> <!-- /.personal-data-item-->
                                        <div class="personal-item">
                                            <label>Phone</label>
                                            <input type="tel" name="phone" value="{$phone}" required>
                                        </div> <!-- /.personal-data-item-->
                                    </div> <!-- /.personal-data-row-->
                                    <div class="form-action-right">
                                        <button type="submit" class="edit-btn">Save Data</button>
                                    </div> <!-- /.form-action-right-->
                                </form>
                            {/if}
                        </div> <!-- /.user-info-group-->
                    </div> <!-- /.profile-info-section-->
                </div> <!-- /.profile-container-->
            </div> <!-- /.panel-body-->
        </div> <!-- /.panel panel-default-->

        <!-- Cards -->
        <div class="panel">
            <div class="panel-heading">My Credit Cards</div>
            <div class="card-row">
                {foreach from=$cards item=card}
                {assign var=cardClass value=$card->getType()|lower|regex_replace:'/[^a-z]/':''}
                    <div class="credit-card {$cardClass}">
                        <div class="card-header">{$card->getType()}</div>
                        <div class="card-body">
                            <ul>
                                <li><strong>Number:</strong> **** **** **** {$card->getNumber()|substr:-4}</li>
                                <li><strong>Holder:</strong> {$card->getHolder()}</li>
                                <li><strong>Expiration:</strong> {$card->getExpiration()}</li>
                            </ul>
                            <div class="form-action-right">
                                <a href="?action=editCard&id={$card->getIdCreditCard()}" class="card-edit-btn">Edit</a>
                                <a href="?action=deleteCard&id={$card->getIdCreditCard()}" 
                                class="card-delete-btn" 
                                onclick="return confirm('Do you really wat to delete this card?');">Delete</a>
                            </div> <!-- /.form-action-right-->
                        </div> <!-- /.card-body-->
                    </div> <!-- /.credit-card-class-->
                {/foreach}
                <div class="credit-card add-card-btn" onclick="location.href='?action=addCard'" title="Aggiungi nuova carta">
                    <div class="card-header" style="text-align:center; font-size:2.5rem; cursor:pointer; user-select:none; color:#ff9f43;">+</div>
                </div> <!-- /credit-card add-card-btn-->
            </div> <!-- /.card-row-->
            {if $showForm}
                <form method="post" action="{$formAction}" class="card-form">
                    <label for="cardType">Type</label>
                    <select name="cardType" id="cardType" required>
                        <option value="">Select type</option>
                        {foreach from=$allowedTypes item=type}
                            <option value="{$type}" {if $cardData.type == $type}selected{/if}>{$type}</option>
                        {/foreach}
                    </select>
                    <label for="cardNumber">Number</label>
                    <input type="text" name="cardNumber" id="cardNumber" maxlength="19" placeholder="XXXX XXXX XXXX XXXX" required value="{$cardData.number|default:''}">
                    <label for="cardHolder">Holder</label>
                    <input type="text" name="cardHolder" id="cardHolder" required value="{$cardData.holder|default:''}">
                    <label for="expiryDate">Expiration (MM/AA)</label>
                    <input type="text" name="expiryDate" id="expiryDate" maxlength="5" placeholder="MM/AA" required value="{$cardData.expiration|default:''}">
                    <div class="form-action-right">
                        <button type="submit" name="save" class="save-btn">Save</button>
                        <a href="{$cancelUrl}" class="cancel-btn">Cancel</a>
                    </div> <!-- /.form-action-right-->
                </form>
            {/if}
        </div> <!-- /.panel-->

        <!-- Reservations-->

        <!-- Footer-->
        {* {include file='footerUser.tpl'} *}
    </body>
</html>