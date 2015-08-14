<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<li>
    <a class="avatar" href="javascript:void(0);" target="_blank">
        <img src="<?php echo $review['avatar']; ?>">
        <p class="nickname"><?php echo $review['author']; ?></p>
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
    					</span>
    </p>
    <table>
        <tr>
            <td class="tle"><b>购买商品：</b></td>
            <td>
                <a href="<?php echo $review['href']; ?>" target="_blank">
                    <span><?php echo $review['product'].' 款式型号：'.$review['model']; ?></span>
                </a>
            </td>
        </tr>
        <tr>
            <td class="tle"><b>商品评价：</b></td>
            <td><span><?php echo $review['text']; ?></span></td>
        </tr>
    </table>
</li>
<?php } ?>
<div style="margin-top: 10px; text-align: center;"><?php echo $pagination; ?></div>
<?php } else { ?>
<p><?php echo $text_no_reviews; ?></p>
<?php } ?>
