<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8">       
      <!-- Template Stylesheet -->
      <link href="../css/styles.css" rel="stylesheet">
      <link href="../css/home.css" rel="stylesheet">
  </head>
  <body>
    <!-- Header -->
    {include file='headerUser.tpl'}

    <!-- Booking Section -->
    <section class="booking">
      <div class="booking-content">
          <h1><span class="highlight">Where flavours meet</span></h1>
          <br><p><strong>Have an unforgettable experience with your family.</strong></p>
          <p><strong>Enjoy our carefully crafted dishes made from the freshest local ingredients.</strong></p>
          <p><strong>Relax in a warm and inviting atmosphere, perfect for any occasion.</strong></p>
          <div class="booking-buttons">
            <a href="#" class="btn">Book Table</a>
            <a href="#" class="btn">Book Room</a>
          </div> <!-- /.booking-buttons-->
      </div> <!-- /.booking-content-->
      <div class="rhombus-wrapper">
          <div class="rhombus small rhombus-1"><img src="../assets/images/menu/chocolate_cake.jpg" alt=""></div>
          <div class="rhombus small rhombus-2"><img src="../assets/images/home/interior.jpg" alt=""></div>
          <div class="rhombus large rhombus-3"><img src="../assets/images/home/location.jpg" alt=""></div>
          <div class="rhombus small rhombus-4"><img src="../assets/images/home/bar.jpg" alt=""></div>
          <div class="rhombus small rhombus-5"><img src="../assets/images/menu/mixed_bruschetta.jpg" alt=""></div>
      </div> <!-- /.rhombus-wrapper-->
    </section>

    <!-- Rooms Section -->
    <section class="event-section rooms-section">
      <div class="content-wrapper">
        <!-- IMAGE ON THE LEFT -->
        <div class="image-column">
          <img src="../assets/images/home/event.jpg" alt="Rustic and elegant event room" class="framed-image">
        </div> <!-- /.image-column-->
        <!-- TEXT ON THE RIGHT -->
        <div class="content-column">
          <button class="tag-btn">OUR ROOMS</button>
          <h1>Spaces that make your <strong>Moments Special</strong></h1>
          <p>
            Whether you're celebrating a wedding, a birthday, or hosting a business dinner, our rustic and elegant rooms offer the perfect atmosphere for every special event. Step into the warmth of Il Ritrovo.
          </p>
          <ul class="features">
            <li><strong>🎉 Private rooms for any kind of event</strong></li>
            <li><strong>🌿 Rustic charm and modern comfort</strong></li>
            <li><strong>🍷 Custom menus and wine selection</strong></li>
            <li><strong>🎶 Live music and personalized decorations</strong></li>
          </ul>
          <div class="booking-buttons">
            <a href="#" class="btn">Discover our rooms →</a>
          </div> <!-- /.booking-buttons-->
        </div> <!-- /.content-column-->
      </div> <!-- /.content-wrapper-->
    </section>

    <!-- Menu Section-->
    <section class="menu-section rooms-section">
      <div class="content-column">
        <button class="tag-btn">OUR MENU</button>
        <h1>Authentic Flavors <strong>Served with Heart</strong></h1>
        <p>
          From rustic starters to refined main courses, our menu celebrates seasonal ingredients and traditional flavors with a touch of creativity. Ideal for business dinners, family events, or just a cozy evening.
        </p>
        <ul class="features">
          <li><strong>✓ Handpicked local produce</strong></li>
          <li><strong>✓ Vegetarian & vegan options</strong></li>
          <li><strong>✓ Curated wine pairings</strong></li>
          <li><strong>✓ Seasonal tasting menu</strong></li>
        </ul>
        <a href="menu.html" class="btn">Explore the menu →</a>
      </div> <!-- /.content-column-->
      <div class="image-column">
        <img src="../assets/images/home/menu.jpg" alt="Menu preview" class="framed-image">
      </div> <!-- /.image-column-->
    </section>

    <!-- Footer-->
    {include file='footerUser.tpl'}
  </body>
</html>