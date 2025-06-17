<?php
/* Smarty version 5.5.1, created on 2025-06-14 20:28:58
  from 'file:headerUser.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_684dbf6a77ebb2_41123709',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e6d66523609b7ab6a5fefa883a9feb706ec27920' => 
    array (
      0 => 'headerUser.tpl',
      1 => 1749924911,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_684dbf6a77ebb2_41123709 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Users/marco/public_html/Progetto/IlRitrovo/src/Smarty/tpl';
?><!DOCTYPE html>
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
        <!-- Logo and site name -->
        <div class="logo-area">
          <img src="../assets/images/logo/logo.png" alt="The Meeting Place Logo" class="logo" />
          <span class="site-name">Il Ritrovo</span>
        </div> <!-- /.logo-area-->
        <!-- Navigation bar -->
        <nav class="main-nav">
          <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="rooms.html">Rooms</a></li>
            <li><a href="menu.html">Menu</a></li>
            <li><a href="review.html">Reviews</a></li>
          </ul>
        </nav>
        <!-- User button -->
        <button class="user-button" aria-label="User menu"></button>
        <!-- User dropdown -->
        <div class="user-dropdown hidden" id="userDropdown"></div>
      </div>
    </header>

    <!-- === LOGIN MODAL === -->
    <div class="modal hidden" id="loginModal">
      <div class="modal-content">
        <h2>Login</h2>
        <form id="loginForm">
          <label for="loginEmail">Email</label>
          <input type="email" id="loginEmail" name="email" required />
          <label for="loginPassword">Password</label>
          <input type="password" id="loginPassword" name="password" required />
          <button type="submit">Login</button>
          <p>Don't have an account? <a href="#" id="toRegister">Register</a></p>
        </form>
      </div> <!-- /.modal-content-->
    </div> <!-- /.modal-hidden-->

    <!-- === REGISTER MODAL === -->
    <div class="modal hidden" id="registerModal">
      <div class="modal-content">
        <h2>Register</h2>
        <form id="registerForm">
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
      </div> <!-- /.modal-content-->
    </div> <!-- /.modal-hidden-->

    <!-- === SCRIPT === -->
    <?php echo '<script'; ?>
>
      // Simulate login state: set to true to simulate logged-in user
      let isLoggedIn = false; 

      const userButton = document.querySelector('.user-button');
      const dropdown = document.getElementById('userDropdown');

      userButton.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
        updateDropdownContent();
      });

      document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target) && !userButton.contains(e.target)) {
          dropdown.classList.add('hidden');
        }
      });

      function updateDropdownContent() {
        if (isLoggedIn) {
          dropdown.innerHTML = `
            <a href="/profile">Go to your profile</a>
            <a href="#" id="logout">Logout</a>
          `;

          document.getElementById('logout').addEventListener('click', (e) => {
            e.preventDefault();
            logoutUser();
          });

        } else {
          dropdown.innerHTML = `
            <a href="#" id="openLogin">Login</a>
            <a href="#" id="openRegister">Register</a>
          `;

          setTimeout(() => {
            document.getElementById('openLogin').addEventListener('click', (e) => {
              e.preventDefault();
              dropdown.classList.add('hidden');
              document.getElementById('loginModal').classList.remove('hidden');
            });

            document.getElementById('openRegister').addEventListener('click', (e) => {
              e.preventDefault();
              dropdown.classList.add('hidden');
              document.getElementById('registerModal').classList.remove('hidden');
            });
          }, 0);
        }
      }

      function logoutUser() {
        // Here you would clear session, tokens etc.
        alert("Logging out...");
        // Simulate logout by setting state false and reloading
        isLoggedIn = false;
        dropdown.classList.add('hidden');
        location.reload();
      }

      // Switch between login and registration modals
      document.addEventListener("DOMContentLoaded", () => {
        const loginModal = document.getElementById("loginModal");
        const registerModal = document.getElementById("registerModal");
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

        document.addEventListener("click", (e) => {
          if (e.target.classList.contains("modal")) {
            e.target.classList.add("hidden");
          }
        });
      });
    <?php echo '</script'; ?>
>
  </body>
  </html><?php }
}
