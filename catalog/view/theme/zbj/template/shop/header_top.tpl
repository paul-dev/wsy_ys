<?php if ($modules) { ?>
<div class="container">
    <?php foreach ($modules as $module) { ?>
    <?php echo $module; ?>
    <?php } ?>
</div>
<?php } ?>

<?php if ($block_advance) echo $block_advance; ?>