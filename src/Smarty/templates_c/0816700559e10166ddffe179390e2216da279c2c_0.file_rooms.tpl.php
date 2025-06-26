<?php
/* Smarty version 5.5.1, created on 2025-06-26 15:01:46
  from 'file:rooms.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_685d44ba986e91_97033323',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0816700559e10166ddffe179390e2216da279c2c' => 
    array (
      0 => 'rooms.tpl',
      1 => 1750841504,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:footerUser.tpl' => 1,
  ),
))) {
function content_685d44ba986e91_97033323 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Applications/XAMPP/xamppfiles/htdocs/IlRitrovo/src/Smarty/tpl';
?><!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms - Il Ritrovo</title>
    <link rel="stylesheet" href="/IlRitrovo/src/Smarty/css/styles.css">
    <link rel="stylesheet" href="/IlRitrovo/src/Smarty/css/home.css">
    </head>
    <body>

        <!-- Header incluso tramite View-->

        <!-- ROOMS HERO SECTION -->
        <section class="rooms-section">
            <div style="margin: 0 auto; display: flex; flex-direction: column; align-items: center; text-align: center;">
                <h1> Discover the Perfect Space for Every Occasion </h1>
            </div>
        </section>

        <!-- ROOMS LIST -->
        <section class="event-section rooms-section" id="rooms">
            <!-- Room 1 -->
            <div class="content-wrapper">
                <div class="image-column">
                    <img src="/IlRitrovo/src/Smarty/assets/images/rooms/room1.jpeg" alt="Cozy and intimate room" class="framed-image">
                </div> <!-- /.image-column-->
                <div class="content-column">
                    <button class="tag-btn">ROOM 1</button>
                    <h1>The <strong>Rustic Retreat</strong></h1>
                    <p>
                        Surrounded by warm wooden textures, vintage accents, and the gentle glow of candlelight, the Rustic Retreat offers the perfect escape from the noise of everyday life. <br>
                        This room is ideal for couples seeking a private dinner, families celebrating in an intimate setting, or small groups who value tranquility and connection. <br>
                        Every detail — from handcrafted tables to soft ambient music — is chosen to create a serene and heartfelt dining experience.
                    </p>
                    <ul class="features">
                        <li><strong>🪵 Hand-finished wood decor</strong></li>
                        <li><strong>🕯️ Candlelight and dimmable wall sconces</strong></li>
                        <li><strong>🍽️ Comfortable for a maximum of 20 guests</strong></li>
                        <li><strong>📖 20€ booking fee</strong></li>
                    </ul>
                </div> <!-- /.content-column-->
            </div> <!-- /.content-wrapper-->

            <!-- Room 2 -->
            <div class="content-wrapper" style="flex-direction: row-reverse;">
                <div class="image-column">
                    <img src="/IlRitrovo/src/Smarty/assets/images/rooms/room2.jpeg" alt="Spacious room for events" class="framed-image">
                </div> <!-- /.image column-->
                <div class="content-column">
                    <button class="tag-btn">ROOM 2</button>
                    <h1>The <strong>Grand Hall</strong></h1>
                    <p>
                        Designed for unforgettable events, the Grand Hall brings together space, sophistication, and versatility. <br>
                        With soaring ceilings and a refined ambiance, this room accommodates large gatherings while maintaining an inviting warmth. <br> 
                        It’s the preferred choice for wedding receptions, corporate dinners, anniversaries, and milestone celebrations. Equipped with modern
                        amenities and customizable layouts, this is where tradition and elegance meet the needs of today.
                    </p>
                    <ul class="features">
                        <li><strong>🏛️ Elegant furnishings and high ceilings</strong></li>
                        <li><strong>👨‍🍳 Dedicated chef & staff service available</strong></li>
                        <li><strong>🍽️ Comfortable for a maximum of 100 guests</strong></li>
                        <li><strong>📖 100€ booking fee</strong></li>
                    </ul>
                </div> <!-- /.content-column-->
            </div> <!-- /.content-wrapper-->

            <!-- Room 3 -->
            <div class="content-wrapper">
                <div class="image-column">
                    <img src="/IlRitrovo/src/Smarty/assets/images/rooms/room3.jpeg" alt="Private tasting room" class="framed-image">
                </div> <!-- /.image-column-->
                <div class="content-column">
                    <button class="tag-btn">ROOM 3</button>
                    <h1>The <strong>Wine Cellar</strong></h1>
                    <p>
                        Tucked beneath the main dining floor, the Wine Cellar is our most exclusive space — a hidden gem designed for refined palates and intimate occasions. <br>
                        Surrounded by stone walls, rustic shelves, and vintage wine bottles, this cave-like room creates a private sanctuary ideal for tastings, anniversaries, or executive dinners. <br>
                        Personalized wine pairings, a private sommelier, and candlelit ambiance transform every moment into a celebration of taste and atmosphere.
                    </p>
                    <ul class="features">
                        <li><strong>🍷 Walls lined with our finest wine selection</strong></li>
                        <li><strong>🕯️ Secluded, cellar-style lighting and decor</strong></li>
                        <li><strong>🍽️ Comfortable for a maximum of 50 guests</strong></li>
                        <li><strong>📖 50€ booking fee</strong></li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Footer-->
        <?php $_smarty_tpl->renderSubTemplate('file:footerUser.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
    </body>
</html><?php }
}
