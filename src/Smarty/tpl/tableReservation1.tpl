<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Book a Table - Step 1</title>
        <link rel="stylesheet" href="../css/styles.css">
        <link rel="stylesheet" href="../css/reservation.css">
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
            <form method="post" action="CFrontController.php?controller=CReservation&task=showValidTable" class="booking-form">
                <div class="form-group">
                    <label for="time_frame">Time Frame</label>
                    <select name="time_frame" id="time_frame" required>
                        <option value="">-- Select --</option>
                        <option value="lunch" {if $reservationData.timeFrame == 'lunch'}selected{/if}>Lunch</option>
                        <option value="dinner" {if $reservationData.timeFrame == 'dinner'}selected{/if}>Dinner</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="guests">Guests</label>
                    <input type="number" name="guests" id="guests" min="1" max="20" value="{$reservationData.guests|default:''}" required>
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" value="{$reservationData.reservationDate|default:''}" required>
                </div>

                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea name="comment" id="comment" rows="4" placeholder="Allergies, high chair request, intolerances...">{$reservationData.comment|default:''}</textarea>
                </div>

                <div class="form-buttons">
                    <a href="home.html" class="btn cancel">Back to Home</a>
                    <button type="submit" class="btn save">Next</button>
                </div>
            </form>

        </div>  <!--/.panel-->

        <!-- Footer-->
        {include file='footerUser.tpl'}
    </body>
</html>