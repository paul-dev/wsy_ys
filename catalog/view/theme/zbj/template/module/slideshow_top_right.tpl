<?php if ($banners) { ?>
<ul>
    <?php
    $_li = 0;
    foreach ($banners as $banner) {
    $_li++;
    if ($_li > 3) break;

    ?>
        <?php if ($banner['link']) { ?>
        <li><a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a></li>
        <?php } else { ?>
        <li><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></li>
        <?php } ?>
    <?php } ?>
</ul>
<?php } ?>