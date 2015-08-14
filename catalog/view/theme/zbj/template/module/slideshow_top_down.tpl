<?php if ($banners) { ?>
<div class="baner-gg">
    <a class="owl_prev"></a>
    <div class="bd left">
        <div class="ulWrap">
            <?php
            $_li = 1;
            foreach ($banners as $banner) {
                if ($_li % 3 == 1) echo '<ul>';
            ?>
                <?php if ($banner['link']) { ?>
                <li><a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a></li>
                <?php } else { ?>
                <li><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></li>
                <?php } ?>
            <?php
                if ($_li % 3 == 0) echo '</ul>';
                $_li++;
            }
            if ($_li > 1 && $_li % 3 <> 1) echo '</ul>';
            ?>
        </div>
    </div>
    <a class="owl_next"></a>
</div>
<?php } ?>