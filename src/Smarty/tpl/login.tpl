<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Il Ritrovo</title>
    <link href="/IlRitrovo/src/Smarty/css/loginSignup.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap" rel="stylesheet" />
    </head>
    <body>

        <div class="modal">
            <div class="modal-content">
                <h2>Login</h2>
                <form action="/IlRitrovo/public/User/checkLogin" method="POST">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required />

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />

                    <button type="submit">Login</button>
                </form>
                <p>Don't have an account? <a href="CFrontController.php?controller=CUser&task=showSignupPage">Sign up here</a></p>
            </div> <!-- /.modal-content-->
        </div> <!-- /.modal-->
        
    </body>
</html>