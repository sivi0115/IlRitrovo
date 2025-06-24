<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Header - Il Ritrovo</title>
    <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/header.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap" rel="stylesheet" />
  </head>
  <body>
    <header>
      <div class="header-top">
        <!-- Logo e nome del sito -->
        <div class="logo-area">
          <img src="../assets/images/logo/logo.png" alt="Il Ritrovo Logo" class="logo" />
          <span class="site-name">Il Ritrovo</span>
        </div> <!-- /.logo-area-->

        <!-- Barra di navigazione -->
        <nav class="main-nav">
          <ul>
            <li><a href="CFrontController.php?controller=CUser&task=showHomePage">Home</a></li>
            <li><a href="CFrontController.php?controller=CFUser&task=showRoomsPage">Rooms</a></li>
            <li><a href="CFrontController.php?controller=CUser&task=showMenuPage">Menu</a></li>
            <li><a href="CFrontController.php?controller=CReview&task=showReviewsPage">Reviews</a></li>
          </ul>
        </nav>

        <!-- Pulsanti utente -->
        <div class="user-area">
          {if $isLogged}
            <a href="CFrontController.php?controller=CUser&task=showProfile" class="user-button">Profile</a>
            <a href="signupHandler.php" class="user-button">Logout</a>
          {else}
            <a href="signupHandler.php" class="user-button">Login</a>
            <a href="signupHandler.php" class="user-button">Sign up</a>
          {/if}
        </div> <!-- /.user-area-->
      </div> <!-- /.header-top-->
    </header>
  </body>
</html>