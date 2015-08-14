<?php
if (!$shop_exist) {
?>
<div>此店铺不存在或暂时不可用！</div>
<?php
}
?>

<?php foreach ($modules as $module) { ?>
<?php echo $module; ?>
<?php } ?>