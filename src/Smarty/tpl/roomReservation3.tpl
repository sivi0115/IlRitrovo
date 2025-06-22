<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Book a Room - Step 3</title>
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/styles.css" rel="stylesheet">
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/reservation.css" rel="stylesheet">
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/user.css" rel="stylesheet">
    </head>
    <body>
        <!-- Header -->
        {include file='headerUser.tpl'}

        <div class="panel">

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step-circle">1</div>
                <div class="step-line"></div>
                <div class="step-circle">2</div>
                <div class="step-line"></div>
                <div class="step-circle active">3</div>
                <div class="step-line"></div>
                <div class="step-circle">4</div>
            </div>

            <hr class="step-separator">

            <!-- Riepilogo scelte -->
            <div class="reservation-summary">
                <div class="summary-row">
                    <p><strong>Time Frame:</strong> {$timeFrame}</p>
                    <p><strong>Guests:</strong> {$people}</p>
                </div> <!-- /.summary-row-->
                <div class="summary-row">
                    <p><strong>Date:</strong> {$reservationDate}</p>
                    <p><strong>Comment:</strong> {$comment|default:'—'}</p>
                </div> <!-- /.summary-row-->
                {if $extras|@count > 0}
                <div class="summary-row extras-row">
                    <p class="summary-title"><strong>Extras Selected:</strong></p>
                    <ul class="extras-list">
                        {foreach from=$extras item=extra}
                            <li>{$extra->getNameExtra()} – €{$extra->getPriceExtra()}</li>
                        {/foreach}
                    </ul>
                </div> <!-- /.summary-row-->
                {/if}
                <div class="summary-row room-row">
                    <p><strong>Room Selected:</strong> {$selectedRoom->getAreaName()} – €{$selectedRoom->getTax()}</p>
                </div> <!-- /.summary-row-->
                <div class="summary-row price-row">
                    <p><strong>Total Price to Pay:</strong> €{$totalPrice}</p>
                </div> <!-- /.summary-row-->
            </div> <!-- /.reservation-summary-->

            <!-- Payment Methods -->
            <div class="panel">
                <div class="panel-heading">Choose a Payment Method</div>
                <div class="card-row">
                    {foreach from=$userCreditCards item=card}
                        {assign var=cardClass value=$card->getType()|lower|regex_replace:'/[^a-z]/':''}
                        <div class="credit-card">
                            <div class="card-header {$cardClass}">{$card->getType()}</div>
                            <div class="card-body">
                                <ul>
                                    <li><strong>Number:</strong> **** **** **** {$card->getNumber()|substr:-4}</li>
                                    <li><strong>Holder:</strong> {$card->getHolder()}</li>
                                    <li><strong>Expiration:</strong> {$card->getExpiration()}</li>
                                </ul>
                                <form method="post" action="signupHandler.php">
                                    <input type="hidden" name="idCreditCard" value="{$card->getIdCreditCard()}">
                                    <button type="submit" class="btn save">Select Card</button>
                                </form>
                            </div> <!-- /.card-body-->
                        </div> <!-- /.credit-card-->
                    {/foreach}
                    <div class="credit-card add-card-btn" onclick="location.href='CFrontController.php?controller=CCreditCard&task=showAddForm'" title="Add new card">
                        <div class="card-header" style="text-align:center; font-size:2.5rem; cursor:pointer; user-select:none; color:#ff9f43;">+</div>
                    </div> <!-- /.add-card-btn-->
                </div> <!-- /.card-row-->

                {if $showForm}
                    <!-- Form per aggiungere carta, lasciato invariato -->
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
                            <button type="submit" name="save" class="btn save">Save</button>
                        </div> <!-- /.form-action-right-->
                    </form>
                {/if}
            </div> <!-- /.panel-->
            
            <!-- Bottoni di navigazione -->
            <form method="post" action="CFrontController.php?controller=CReservation&task=checkRoomReservation">
                <input type="hidden" name="selectedCardId" id="selectedCardId" value="{$selectedCardId}">
                <div class="reservation-form-buttons">
                    <button type="button" class="btn-cancel-step" onclick="location.href='CFrontController.php?controller=CReservation&task=roomReservationStepTwo'">Back</button>
                    <button type="submit" class="btn-save-step">Next</button>
                </div>
            </form>

        <!-- Footer -->
        {include file='footerUser.tpl'}
    </body>
</html>