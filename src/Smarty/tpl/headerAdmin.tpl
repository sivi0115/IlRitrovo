<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Header - Il Ritrovo (Admin)</title>
    <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/header.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap" rel="stylesheet" />
  </head>
  <body>

    <header>
      <div class="header-top">
        <div class="logo-area">
          <img src="../assets/images/logo/logo.png" alt="Logo Il Ritrovo" class="logo" />
          <span class="site-name">Il Ritrovo</span>
        </div> <!-- /.logo-area-->

        <nav class="main-nav">
          <ul>
            <li><a href="CFrontController.php?controller=CReview&task=showAdminHomePage">Home</a></li>
            <li><a href="CFrontController.php?controller=CReview&task=showUsersPage">Users</a></li>
            <li><a href="CFrontController.php?controller=CReview&task=showExtrasPage">Extras</a></li>
            <li><a href="CFrontController.php?controller=CReview&task=showAdminReviewsPage">Reviews</a></li>
          </ul>
        </nav>

        <!-- Pulsante utente (sfera) -->
        <button class="user-button" aria-label="User menu"></button>

        <!-- Dropdown utente -->
        <div class="user-dropdown hidden" id="userDropdown"></div>

      </div> <!-- /.header-top-->
    </header>

    {assign var="adminLoggedInJs" value=false}
    {if isset($admin)}
      {assign var="adminLoggedInJs" value=true}
    {/if}

    {literal}
    <script>
      const adminLoggedIn = {/literal}{$adminLoggedInJs|json_encode}{literal};

      const userButton = document.querySelector('.user-button');
      const dropdown = document.getElementById('userDropdown');

      userButton.addEventListener('click', e => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
        updateDropdownContent();
      });

      document.addEventListener('click', e => {
        if (!dropdown.contains(e.target) && !userButton.contains(e.target)) {
          dropdown.classList.add('hidden');
        }
      });

      function updateDropdownContent() {
        if (adminLoggedIn) {
          dropdown.innerHTML = `
            <a href="CFrontController.php?controller=CUser&task=logout">Logout</a>
          `;
        } else {
          dropdown.innerHTML = `
            <span style="padding: 10px; display: block; color: #666;">Non sei loggato</span>
          `;
        }
      }
    </script>
    {/literal}

  </body>
</html>