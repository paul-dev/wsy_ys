<div class="list-group">
  <?php foreach ($categories as $category) { ?>
  <?php if ($category['category_id'] == $category_id) { ?>
  <a href="<?php echo $category['href']; ?>" class="list-group-item active"><?php echo $category['name']; ?></a>
  <?php if ($category['children']) { ?>
  <?php foreach ($category['children'] as $child) { ?>
  <?php if ($child['category_id'] == $child_id) { ?>
  <a href="<?php echo $child['href']; ?>" class="list-group-item active">&nbsp;&nbsp;&nbsp;- <?php echo $child['name']; ?></a>
  <?php } else { ?>
  <a href="<?php echo $child['href']; ?>" class="list-group-item">&nbsp;&nbsp;&nbsp;- <?php echo $child['name']; ?></a>
  <?php } ?>
  <?php } ?>
  <?php } ?>
  <?php } else { ?>
  <a href="<?php echo $category['href']; ?>" class="list-group-item"><?php echo $category['name']; ?></a>
  <?php } ?>
  <?php } ?>
</div>
<?php
$icons = array('nav_icon_one', 'nav_icon_two', 'nav_icon_three', 'nav_icon_four', 'nav_icon_five');
?>
<div class="xbanner" style="display: none;">
  <div class="owl-lf left" style="position:static;">
    <ul>
        <?php foreach ($categories as $category) { ?>
        <li>
            <div class="nav_big_class">
                <h3><span class="<?php if (!$is_shop) echo 'nav_class_icon '.$icons[rand(0,4)]; ?>"></span><a href="<?php echo $category['href']; ?>"<?php if (!$is_shop) echo ' style="color: #f69;"'; ?>><?php echo $category['name']; ?></a></h3>
                <?php if ($category['children']) { ?>
                <p>
                    <?php foreach ($category['children'] as $child) { ?>
                    <a<?php if ($child['category_id'] == $child_id) echo ' class="red_a"'; ?> href="<?php echo $child['href']; ?>" ><?php echo $child['name']; ?></a>
                    <?php } ?>
                </p>
                <span class="owl-lb">></span>
                <?php } ?>
            </div>
            <?php if ($category['children']) { ?>
            <div class="owl-drop">
                <h3><a href="<?php echo $category['href']; ?>"<?php if ($is_shop) echo ' style="color: #666;"'; ?>><?php echo $category['name']; ?></a></h3>
                <p style="height: auto;">
                    <?php foreach ($category['children'] as $child) { ?>
                    <a<?php if ($child['category_id'] == $child_id) echo ' class="red_a"'; ?> href="<?php echo $child['href']; ?>" ><?php echo $child['name']; ?></a>
                    <?php } ?>
                </p>
            </div>
            <?php } ?>
        </li>
        <?php } ?>
    </ul>
  </div>
  <script type="text/javascript" src="catalog/view/javascript/bootstrap/js/jquery.SuperSlide.2.1.1.js"></script>
  <script  type="text/javascript">
      jQuery(".owl-lf ul").slide({
          type:"menu",// 效果类型，针对菜单/导航而引入的参数（默认slide）
          titCell:"li", //鼠标触发对象
          targetCell:".owl-drop", //titCell里面包含的要显示/消失的对
          delayTime:0 , //效果时间
          triggerTime:0, //鼠标延迟触发时间（默认150）
          returnDefault:true //鼠标移走后返回默认状态，例如默认频道是“预告片”，鼠标移走后会返回“预告片”（默认false）
      });
  </script>
</div>
