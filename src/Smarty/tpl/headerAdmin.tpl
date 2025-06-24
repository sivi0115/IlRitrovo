<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Header - Il Ritrovo (Admin)</title>
    <link href="/IlRitrovo/src/Smarty/css/header.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap" rel="stylesheet" />
  </head>
  <body>
    <header>
      <div class="header-top">
        <div class="logo-area">
          <img src="/IlRitrovo/src/Smarty/assets/images/logo/logo.png" alt="Logo Il Ritrovo" class="logo" />
          <span class="site-name">Il Ritrovo</span>
        </div> <!-- /.logo-area -->

        <nav class="main-nav">
          <ul>
            <li><a href="/IlRitrovo/public/User/showAdminHomePage">Home</a></li>
            <li><a href="/IlRitrovo/public/User/showUsersPage">Users</a></li>
            <li><a href="/IlRitrovo/public/Extra/showExtrasPage">Extras</a></li>
            <li><a href="/IlRitrovo/public/Review/showAdminReviewsPage">Reviews</a></li>
          </ul>
        </nav>

        {if isset($admin)}
          <div class="user-area">
            <form action="/IlRitrovo/public/User/logout" method="POST" style="margin:0;">
              <button type="submit" class="user-button" aria-label="Logout">Logout</button>
            </form>
          </div>
        {/if}
      </div> <!-- /.header-top -->
    </header>
  </body>
</html>