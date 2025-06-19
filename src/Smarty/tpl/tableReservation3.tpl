<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Book a Table - Step 3</title>
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/styles.css" rel="stylesheet">
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/reservation.css" rel="stylesheet">
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
            </div>

            <hr class="step-separator">

            <!-- Riepilogo completo -->
            <div class="reservation-summary">
                <div class="summary-row">
                    <p><strong>Time Frame:</strong> {$timeFrame}</p>
                    <p><strong>Guests:</strong> {$people}</p>
                </div>
                <div class="summary-row">
                    <p><strong>Date:</strong> {$reservationDate}</p>
                    <p><strong>Comment:</strong> {$comment|default:'—'}</p>
                </div>
                <div class="summary-row">
                    <p><strong>Selected Table:</strong> {$idTable}</p>
                </div>
            </div>

            <!-- Pulsanti conferma -->
            <form method="post" action="signupHandler.php" class="booking-form">
                <div class="reservation-form-buttons">
                    <a href="CFrontController.php?controller=CReservation&task=tableReservationStepTwo" class="btn-cancel-step">Back</a>
                    <button type="submit" class="btn-save-step">Confirm</button>
                </div>
            </form>
        </div> <!-- /.panel-->

        <!-- Footer -->
        {include file='footerUser.tpl'}

    </body>
</html>