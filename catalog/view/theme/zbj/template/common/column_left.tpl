<?php if ($modules) { ?>
<div id="column-left" class="col-sm-2 hidden-xs" style="<?php echo $custom_style; ?>">
  <?php foreach ($modules as $module) { ?>
  <?php echo $module; ?>
  <?php } ?>
</div>
<?php } ?>