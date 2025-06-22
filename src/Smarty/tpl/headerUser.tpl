<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Header - Il Ritrovo</title>
    <link rel="stylesheet" href="../css/header.css" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap" rel="stylesheet" />
  </head>
  <body>
    <header>
      <div class="header-top">
        <!-- Logo e nome del sito -->
        <div class="logo-area">
          <img src="../assets/images/logo/logo.png" alt="The Meeting Place Logo" class="logo" />
          <span class="site-name">Il Ritrovo</span>
        </div>

        <!-- Barra di navigazione -->
        <nav class="main-nav">
          <ul>
            <li><a href="CFrontController.php?controller=CFrontController&task=showHome">Home</a></li>
            <li><a href="CFrontController.php?controller=CFrontController&task=showRooms">Rooms</a></li>
            <li><a href="CFrontController.php?controller=CFrontController&task=showMenu">Menu</a></li>
            <li><a href="CFrontController.php?controller=CReview&task=showReviewsPage">Reviews</a></li>
          </ul>
        </nav>

        <!-- Pulsante utente (sfera) -->
        <button class="user-button" aria-label="User menu"></button>

        <!-- Menù a tendina dell'utente -->
        <div class="user-dropdown hidden" id="userDropdown"></div>
      </div>
    </header>

    <!-- === MODALE LOGIN === -->
    <div class="modal hidden" id="loginModal">
      <div class="modal-content">
        <h2>Login</h2>
        <form id="loginForm" method="post" action="CFrontController.php?controller=CUser&task=login">
          <label for="loginEmail">Email</label>
          <input type="email" id="loginEmail" name="email" required />
          <label for="loginPassword">Password</label>
          <input type="password" id="loginPassword" name="password" required />
          <button type="submit">Login</button>
          <p>Don't have an account? <a href="#" id="toRegister">Register</a></p>
        </form>
      </div>
    </div>

    <!-- === MODALE REGISTRAZIONE === -->
    <div class="modal hidden" id="registerModal">
      <div class="modal-content">
        <h2>Register</h2>
        <form id="registerForm" method="post" action="CFrontController.php?controller=CUser&task=register">
          <label for="regUsername">Username</label>
          <input type="text" id="regUsername" name="username" required />
          <label for="regEmail">Email</label>
          <input type="email" id="regEmail" name="email" required />
          <label for="regPassword">Password</label>
          <input type="password" id="regPassword" name="password" required />
          <label for="regName">Name</label>
          <input type="text" id="regName" name="name" required />
          <label for="regSurname">Surname</label>
          <input type="text" id="regSurname" name="surname" required />
          <label for="regBirth">Date of Birth</label>
          <input type="date" id="regBirth" name="birthDate" required />
          <label for="regPhone">Phone</label>
          <input type="tel" id="regPhone" name="phone" required />
          <button type="submit">Register</button>
          <p>Already have an account? <a href="#" id="toLogin">Login</a></p>
        </form>
      </div>
    </div>

    {*
      === GESTIONE DELLO STATO DI LOGIN (isLoggedIn) ===
      - Smarty non può interpretare JavaScript direttamente.
      - Quindi assegniamo prima la variabile lato server con {assign}
      - Poi la stampiamo nel <script> usando {literal} per evitare problemi in editor (es. VS Code)
    *}
    {assign var="isLoggedInJs" value=false}
    {if isset($user)}
      {assign var="isLoggedInJs" value=true}
    {/if}

    {literal}
    <script>
      // Questa variabile sarà "true" o "false" in base alla sessione utente
      const isLoggedIn = {/literal}{$isLoggedInJs|json_encode}{literal};

      // === JavaScript per il menu utente e le modali ===

      // Prendiamo gli elementi dalla pagina
      const userButton = document.querySelector('.user-button');
      const dropdown = document.getElementById('userDropdown');
      const loginModal = document.getElementById("loginModal");
      const registerModal = document.getElementById("registerModal");

      // Mostra/nasconde il menù cliccando sul pulsante utente
      userButton.addEventListener('click', (e) => {
        e.stopPropagation(); // previene la chiusura automatica
        dropdown.classList.toggle('hidden');
        updateDropdownContent();
      });

      // Chiude il menù se clicco fuori
      document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target) && !userButton.contains(e.target)) {
          dropdown.classList.add('hidden');
        }
      });

      // Controlla se l’utente è loggato e aggiorna il contenuto del menù
      function updateDropdownContent() {
        if (isLoggedIn) {
          dropdown.innerHTML = `
            <a href="CFrontController.php?controller=CUtente&task=showUserProfile">Profilo</a>
            <a href="CFrontController.php?controller=CUser&task=logout">Logout</a>
          `;
        } else {
          dropdown.innerHTML = `
            <a href="#" id="openLogin">Login</a>
            <a href="#" id="openRegister">Register</a>
          `;

          // Eventi per aprire le modali
          setTimeout(() => {
            document.getElementById('openLogin').addEventListener('click', (e) => {
              e.preventDefault();
              dropdown.classList.add('hidden');
              loginModal.classList.remove('hidden');
            });

            document.getElementById('openRegister').addEventListener('click', (e) => {
              e.preventDefault();
              dropdown.classList.add('hidden');
              registerModal.classList.remove('hidden');
            });
          }, 0);
        }
      }

      // Cambio tra Login e Registrazione
      document.addEventListener("DOMContentLoaded", () => {
        const toRegister = document.getElementById("toRegister");
        const toLogin = document.getElementById("toLogin");

        if (toRegister) {
          toRegister.addEventListener("click", (e) => {
            e.preventDefault();
            loginModal.classList.add("hidden");
            registerModal.classList.remove("hidden");
          });
        }

        if (toLogin) {
          toLogin.addEventListener("click", (e) => {
            e.preventDefault();
            registerModal.classList.add("hidden");
            loginModal.classList.remove("hidden");
          });
        }

        // Chiude le modali cliccando fuori
        document.addEventListener("click", (e) => {
          if (e.target.classList.contains("modal")) {
            e.target.classList.add("hidden");
          }
        });
      });
    </script>
    {/literal}
  </body>
</html>