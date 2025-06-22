<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">       
    <title>Reviews - Il Ritrovo</title>
    <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/styles.css" rel="stylesheet">
        <link href="/~marco/Progetto/IlRitrovo/src/Smarty/css/reviews.css" rel="stylesheet">
  </head>
  <body>
    <div class="page-container">
      
      <!-- Header -->
      {include file='headerUser.tpl'}

      <!-- Immagine di intestazione -->
      <section class="review-image">
        <img src="../assets/images/home/reviews.jpg" alt="Customer enjoying meal" />
      </section>

      <!-- Sezione recensione personale -->
      {if isset($loggedUser)}
        <section class="review-box">
          {if $userReview === null}
            <h2>Leave your review</h2>
            <form action="CFrontController.php?controller=CReview&task=checkAddReview" method="post">
              <label for="stars">Rating:</label>
                <div class="rating-stars">
                  {for $i=5 to 1 step -1}
                    <input type="radio" name="stars" id="star{$i}" value="{$stars}" required>
                    <label for="star{$i}">★</label>
                  {/for}
                </div> <!-- /.rating-stars-->
              <label for="body">Your Review:</label>
              <textarea name="body" value="$body" rows="4" required></textarea>
              <div class="form-action-right">
                <button type="submit" class="btn save">Submit</button>
              </div> <!-- /.form-action-right-->
            </form>
          {else}
            <h2>Your Review</h2>
            <div class="existing-review">
              <p><strong>Rating:</strong> {$review->getStars()} / 5</p>
              <p><strong>Review:</strong> {$review->getBody()}</p>
              <div class="form-action-right">
                <a href="CFrontController.php?controller=CReview&task=deleteReview&idReview={$review->getIdReview()}" class="btn delete">Delete</a>
              </div> <!-- /.form-action-right-->
            </div> <!-- /.existing-review-->
          {/if}
        </section>
      {/if}

      <!-- Sezione tutte le recensioni -->
      <section>
        <h2>What our customers say</h2>
        {foreach from=$allReviews item=review}
          <div class="review-card">
            <div class="review-meta">
              <span><strong>{$review->getUsername()}</strong> rated 
                <strong>{$review->getStars()}/5</strong>
              </span>
              <span class="review-timestamp">{$review->getCreationTime()}</span>
            </div> <!-- /.review-meta-->
            <p class="review-body">{$review->getBody()}</p>
            {if $review->getIdReply() !== null}
              <div class="admin-reply">
                <p><strong>Reply from the restaurant:</strong></p>
                <p>{$review->getIdReply()->getBody()}</p>
              </div> <!-- /.admin-reply-->
            {/if}
          </div> <!-- /.review-card-->
        {/foreach}
      </section>
    </div> <!-- /.page-container -->

    <!-- Footer-->
    {include file='footerUser.tpl'}
  </body>
</html>