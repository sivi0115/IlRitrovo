<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Book a Table - Step 1</title>
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/styles.css" rel="stylesheet">
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/reservation.css" rel="stylesheet">
    </head>
    <body>

        <!-- Header -->
        {include file='headerUser.tpl'}
        
        <div class="panel">

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step-circle active">1</div>
                <div class="step-line"></div>
                <div class="step-circle">2</div>
                <div class="step-line"></div>
                <div class="step-circle">3</div>
            </div> <!-- /.step-indicator-->

            <hr class="step-separator">

            <!-- Heading -->
            <h2 class="panel-heading">Book a Table with us</h2>

            <!-- Booking Form -->
            <form method="post" action="signupHandler.php" class="booking-form">
                <div class="form-group">
                    <label for="timeFrame">Time Frame</label>
                    <select name="timeFrame" id="timeFrame" required>
                        <option value="">-- Select --</option>
                        <option value="lunch" {if $timeFrame == 'lunch'}selected{/if}>Lunch</option>
                        <option value="dinner" {if $timeFrame == 'dinner'}selected{/if}>Dinner</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="people">Guests</label>
                    <input type="number" name="people" id="people" min="1" max="20" value="{$people|default:''}" required>
                </div>

                <div class="form-group">
                    <label for="reservationDate">Date</label>
                    <input type="date" name="reservationDate" id="reservationDate" value="{$reservationDate|default:''}" required>
                </div>

                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea name="comment" id="comment" rows="4" placeholder="Allergies, high chair request, intolerances...">{$comment|default:''}</textarea>
                </div>

                <div class="reservation-form-buttons">
                    <a href="CFrontController.php?controller=CFrontController&task=showHome" class="btn-cancel-step">Back to Home</a>
                    <button type="submit" class="btn-save-step">Next</button>
                </div>
            </form>

        </div>  <!--/.panel-->

        <!-- Footer-->
        {include file='footerUser.tpl'}
    </body>
</html>