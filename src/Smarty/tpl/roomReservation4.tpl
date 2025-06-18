<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Book a Room - Step 4</title>
        <link rel="stylesheet" href="../css/styles.css">
        <link rel="stylesheet" href="../css/user.css">
        <link rel="stylesheet" href="../css/reservation.css">
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
                <div class="step-circle">3</div>
                <div class="step-line"></div>
                <div class="step-circle active">4</div>
            </div>

            <hr class="step-separator">

            <!-- Riepilogo scelte -->
            <div class="reservation-summary">
                <div class="summary-row">
                    <p><strong>Time Frame:</strong> {$reservationData.timeFrame}</p>
                    <p><strong>Guests:</strong> {$reservationData.guests}</p>
                </div> <!-- /.summary-row-->
                <div class="summary-row">
                    <p><strong>Date:</strong> {$reservationData.reservationDate}</p>
                    <p><strong>Comment:</strong> {$reservationData.comment|default:'—'}</p>
                </div> <!-- /.summary-row-->
                {if $reservationData.extras|@count > 0}
                <div class="summary-row extras-row">
                    <p class="summary-title"><strong>Extras Selected:</strong></p>
                    <ul class="extras-list">
                        {foreach from=$reservationData.extras item=extra}
                            <li>{$extra.name} – €{$extra.price}</li>
                        {/foreach}
                    </ul>
                </div> <!-- /.summary-row-->
                {/if}
                <div class="summary-row room-row">
                    <p><strong>Room Selected:</strong> {$reservationData.room.areaName} – €{$reservationData.room.tax}</p>
                </div> <!-- /.summary-row-->
                <div class="summary-row price-row">
                    <p><strong>Total Price to Pay:</strong> €{$reservationData.totalPrice}</p>
                </div> <!-- /.summary-row-->
            </div> <!-- /.reservation-summary-->

            <!-- Selected Payment Method -->
            <div class="summary-row">
                <p><strong>Selected Payment Method:</strong></p>
                <div class="credit-card compact">
                    <div class="card-header {$selectedCard->getType()|lower|regex_replace:'/[^a-z]/':''}">
                        {$selectedCard->getType()}
                    </div> <!-- /.card-header-->
                    <div class="card-body">
                        <ul>
                            <li><strong>Number:</strong> **** **** **** {$selectedCard->getNumber()|substr:-4}</li>
                            <li><strong>Holder:</strong> {$selectedCard->getHolder()}</li>
                            <li><strong>Expiration:</strong> {$selectedCard->getExpiration()}</li>
                        </ul>
                    </div> <!-- /.card-body-->
                </div> <!-- /.credit-card-compact-->
            </div> <!-- /.summary-row-->

            <!-- Bottoni di navigazione -->
            <form method="post" action="CFrontController.php?controller=CReservation&task=finalizeReservation">
                <input type="hidden" name="selectedCardId" value="{$selectedCard.id}">
                <div class="reservation-form-buttons">
                    <button type="button" class="btn-cancel-step" onclick="location.href='CFrontController.php?controller=CReservation&task=roomReservationStepThree'">Back</button>
                    <button type="submit" class="btn-save-step">Confirm </button>
                </div> <!-- /.reservation-form-buttons-->
            </form>

        </div> <!--  /.panel-->

    <!-- Footer -->
    {include file='footerUser.tpl'}
    </body>
</html>