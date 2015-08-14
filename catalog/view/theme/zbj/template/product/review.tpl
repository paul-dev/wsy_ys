<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<!-- <table class="table table-striped table-bordered">
  <tr>
    <td style="width: 50%;">
        <?php if ($review['rating'] == 5) { ?>
        (好评)
        <?php } elseif ($review['rating'] == 1) { ?>
        (差评)
        <?php } else { ?>
        (中评)
        <?php } ?>
        <strong><?php echo $review['author']; ?></strong>
    </td>
    <td class="text-right"><?php echo $review['date_added']; ?></td>
  </tr>
  <tr>
    <td colspan="2"><p><?php echo $review['text']; ?></p>
      <?php for ($i = 1; $i <= 5; $i++) { ?>
      <?php if ($review['rating'] < $i) { ?>
      <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
      <?php } else { ?>
      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
      <?php } ?>
      <?php } ?></td>
  </tr>
</table> -->
<div class="goods-comment-show"> 
  <a class="avatar" href="javascript:void(0);" target="_blank" title="<?php echo $review['author']; ?>">
    <img src="<?php echo $review['avatar']; ?>">
    <span class="twitter_comment_name"><?php echo $review['author']; ?></span> 
  </a> 
  <p class="good-level">
    <span class="fa fa-stack">
      <i class="icon-good-level">
      <?php if ($review['rating'] == 5) { ?>
        好评
        <?php } elseif ($review['rating'] == 1) { ?>
        差评
        <?php } else { ?>
        中评
        <?php } ?>
      </i>
      <?php for ($i = 1; $i <= 5; $i++) { ?>
        <?php if ($review['rating'] < $i) { ?>
          <i class="fa fa-star-o"></i>
        <?php } else { ?>
          <i class="fa fa-star"></i>
        <?php } ?>
      <?php } ?>
      <i class="comment-date"><?php echo $review['date_added']; ?></i>
    </span>

  </p> 
  <p class="l_con"><?php echo $review['text']; ?></p> 
</div>
<?php } ?>
<div class="text-right" style="margin-top: 10px;"><?php echo $pagination; ?></div>
<?php } else { ?>
<p><?php echo $text_no_reviews; ?></p>
<?php } ?>
