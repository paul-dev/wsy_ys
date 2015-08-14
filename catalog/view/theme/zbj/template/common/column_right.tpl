<?php if ($modules) { ?>
<column id="column-right" class="<?php echo $custom_class; ?> hidden-xs">
  <?php foreach ($modules as $module) { ?>
  <?php echo $module; ?>
  <?php } ?>
</column>
<?php } ?>
