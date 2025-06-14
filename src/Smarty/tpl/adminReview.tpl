<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">       
        <title>Reviews - Il Ritrovo</title>
        <link href="../css/styles.css" rel="stylesheet">
        <link href="../css/reviews.css" rel="stylesheet">
    </head>
    <body>
        <div class="page-container">

            <!-- Header -->
            {include file='headerAdmin.tpl'}

            <!-- Immagine di intestazione -->
            <section class="review-image">
            <img src="../assets/images/home/reviews.jpg" alt="Customer enjoying meal" />
            </section>

            <section>
                <h2>Customer Reviews</h2>
                {foreach from=$allReviews item=review}
                    <div class="review-card">
                        <div class="review-meta">
                            <span><strong>{$review->getUsername()}</strong> rated <strong>{$review->getStars()}/5</strong></span>
                            <span class="review-timestamp">{$review->getCreationTime()}</span>
                            <p class="review-body">{$review->getBody()}</p>
                        </div> <!-- /.review-meta-->
                        <!-- Pulsante elimina recensione -->
                        <form action="CFrontController.php?controller=CReview&task=deleteReview&idReview={$review->getIdReview()}" method="post" onsubmit="return confirm('Are you sure you want to delete this review?');">
                            <button type="submit" class="btn delete">Delete Review</button>
                        </form>

                        {if $review->getIdReply() === null}
                            {if isset($showReplyForm) && $showReplyForm == $review->getIdReview()}
                                <section class="review-box admin-reply-form">
                                    <form action="CFrontController.php?controller=CReply&task=addReply&idReview={$review->getIdReview()}" method="post">
                                        <label for="reply-{$review->getIdReview()}">Write reply:</label>
                                        <textarea id="reply-{$review->getIdReview()}" name="replyBody" rows="3" required></textarea>
                                        <div class="form-action-right">
                                        <button type="submit" class="btn save">Submit Reply</button>
                                        </div>
                                    </form>
                                </section>
                                <a href="CFrontController.php?controller=CReview&task=backAdminReview" class="btn delete">Cancel</a>
                            {else}
                                <a href="CFrontController.php?controller=CReply&task=showReplyForm={$review->getIdReview()}" class="btn save">Reply</a>
                            {/if}
                        {else}
                            <div class="admin-reply">
                                <p><strong>Reply from the restaurant:</strong></p>
                                <p>{$review->getReply()->getBody()}</p>
                                <form action="CFrontController.php?controller=CReview&task=deleteReply&idReply={$review->getIdReply()}" method="post" onsubmit="return confirm('Are you sure you want to delete this reply?');">
                                    <button type="submit" class="btn delete">Delete Reply</button>
                                </form>
                            </div> <!-- /.admin-reply-->
                        {/if}
                    </div> <!-- /.review-card-->
                {/foreach}
            </section>

        </div> <!-- /.page-container -->

        <!-- Footer-->
        {include file='footerAdmin.tpl'}
    </body>
</html>