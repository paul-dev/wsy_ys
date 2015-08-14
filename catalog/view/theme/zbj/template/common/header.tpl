<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />

<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="catalog/view/theme/zbj/stylesheet/base.css">
<link href="catalog/view/theme/zbj/stylesheet/stylesheet.css" rel="stylesheet">
<!--[if (lt IE 9)]><link href="catalog/view/theme/zbj/stylesheet/stylesheet-ltie9.css" rel="stylesheet"><![endif]-->
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery.scrollLoading-min.js" type="text/javascript"></script>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php echo $google_analytics; ?>
</head>
<body class="<?php echo $class; ?>">
<nav id="top" style="margin: auto;">
  <div class="container" style="padding: 0;">
    <?php echo $currency; ?>
    <?php echo $language; ?>
    <div id="top-links" class="nav pull-right">
      <ul class="list-inline" style="position:relative;margin-bottom:8px;">
      <!--
      	<li><a href="#"><i class="fa fa-qq"></i><span class="hidden-xs hidden-sm hidden-md">QQ登录</span></a></li>
      	<li><a href="#"><i class="fa fa-weixin"></i><span class="hidden-xs hidden-sm hidden-md">微信登录</span></a></li>
        <li><a href="#"><i></i><span class="hidden-xs hidden-sm hidden-md">登录</span></a></li>
        <li><a href="#"><i></i><span class="hidden-xs hidden-sm hidden-md">注册</span></a></li>
        <li><a href="#"><i></i><span class="hidden-xs hidden-sm hidden-md">帮助中心</span></a></li>
      -->
        
        <li style="display: none;"><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>
          <?php if ($logged) { ?>
          <li><a href="<?php echo $account; ?>"><img src="<?php echo $avatar; ?>" /></a></li>
          <li class="dropdown"><a href="<?php echo $account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $nickname; ?> <span class="caret"></span></a>
              <ul class="dropdown-menu dropdown-menu-left">
                  <?php if ($isSeller) { ?>
                  <li><a href="<?php echo $url_seller; ?>"><?php echo $text_seller; ?></a></li>
                  <?php } ?>
                  <li><a href="<?php echo $account; ?>">我的账户</a></li>
                  <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
              </ul>
          </li>
          <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
          <?php } else { ?>
          <li><a href="/connect/qzone/oauth/qq_login.php"><!-- <img src="/image/qq_login.png"> --><i class="i_qq"></i>QQ登录</a></li>
          <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
          <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
          <?php } ?>
          <li class="dropdown" style="display: none;"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
          <ul class="dropdown-menu dropdown-menu-right">
            <?php if ($logged) { ?>
              <li><a href="<?php echo $url_seller; ?>"><?php echo $text_seller; ?></a></li>
              <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
            <?php } else { ?>
            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
            <?php } ?>
          </ul>
        </li>
        <li style="display: none;"><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
        <li id="header-cart-label">
        	<a href="javascript:void(0);" onclick="$('#cart >ul').slideToggle();" title="<?php echo $text_shopping_cart; ?>">
        		<i class="fa fa-shopping-cart"></i> 
        		<span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span>
        		<span class="shopping_cart_num">0</span>
        	</a>
          <div class="shopping_cart_list">
            <?php echo $cart; ?>
          </div>
		</li>

        <li><a href="<?php echo $order; ?>"><i class="i_order"></i>我的订单</a></li>
        <li class="no_bor"><a href=""><i class="fa fa-question-circle fa-lg" style="margin-right:4px;font-size:18px;"></i>帮助中心</a></li>
        <li class="no_bor" style="display: none;"><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>
      </ul>
    </div>
  </div>
</nav>
<header>
  <div class="container">
   <!--  <div class="row">
      <div class="col-sm-4">
        <div id="logo">
          <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>" style="float: left;"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <?php } else { ?>
          <h1 style="float: left;"><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
            <a href=""><img style="padding-top: 15px;" src="/image/catalog/event_image/event-100X50.gif"></a>
        </div>
      </div>
      <div class="col-sm-5" style="padding-top: 15px;"><?php //echo $search; ?>
      </div>
      <div class="col-sm-3" style="padding-top: 15px;"><?php //echo $cart; ?></div>
    </div> -->
    <div class="header clearfix">
      <div class="header_left">
        <div id="logo" style="margin-top: 10px;">
        <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>"/></a>
          <?php } else { ?>
          <h1 style="float: left;"><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>
      </div>
      <div class="header_right">
        <div class="search_box">
          <?php echo $search; ?>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="container_n">
<div class="container">
  <!-- <nav id="menu" class="navbar">
    <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav" style="margin-left: 120px;">
          <li><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a></li>
          <li style="margin-left: 20px;"><a href="<?php echo $link_limit_buy; ?>"><?php echo $text_limit_buy; ?></a></li>
          <li style="margin-left: 20px;"><a href="<?php echo $link_on_sale; ?>"><?php echo $text_on_sale; ?></a></li>
          <li style="margin-left: 20px;"><a href="<?php echo $link_on_going; ?>"><?php echo $text_on_going; ?></a></li>
          <li style="margin-left: 20px;"><a href="<?php echo $link_best_shop; ?>"><?php echo $text_best_shop; ?></a></li>
          <li style="margin-left: 20px;"><a href="<?php echo $link_auction; ?>"><?php echo $text_auction; ?></a></li>
          <li style="margin-left: 20px;"><a href="<?php echo $link_custom_buy; ?>"><?php echo $text_custom_buy; ?></a></li>
          <li style="margin-left: 20px;"><a href="<?php echo $link_community; ?>" target="_blank"><?php echo $text_community; ?></a></li>
          <li style="margin-left: 20px;"><a href="<?php echo $link_benefits; ?>"><?php echo $text_benefits; ?></a></li>
          <?php if ($categories && 1 <> 1) { ?>
            <?php foreach ($categories as $category) { ?>
              <?php if ($category['children']) { ?>
                <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
                  <div class="dropdown-menu">
                    <div class="dropdown-inner">
                      <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
                        <ul class="list-unstyled">
                          <?php foreach ($children as $child) { ?>
                            <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                          <?php } ?>
                        </ul>
                      <?php } ?>
                    </div>
                    <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a> 
                  </div>
                </li>
              <?php } else { ?>
                <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
              <?php } ?>
            <?php } ?>
          <?php } ?>
      </ul>
    </div>
  </nav> -->
  <div id="nav_home">
    <ul class="nav_list">
      <li><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a></li>
      <li><a href="<?php echo $link_limit_buy; ?>"><?php echo $text_limit_buy; ?></a></li>
      <li><a href="<?php echo $link_on_sale; ?>"><?php echo $text_on_sale; ?></a></li>
      <li><a href="<?php echo $link_on_going; ?>"><?php echo $text_on_going; ?></a></li>
      <li><a href="<?php echo $link_best_shop; ?>"><?php echo $text_best_shop; ?></a></li>
      <li><a href="<?php echo $link_auction; ?>"><?php echo $text_auction; ?></a></li>
      <li><a href="<?php echo $link_custom_buy; ?>"><?php echo $text_custom_buy; ?></a></li>
      <li><a href="<?php echo $link_community; ?>" target="_blank"><?php echo $text_community; ?></a></li>
      <li><a href="<?php echo $link_benefits; ?>"><?php echo $text_benefits; ?></a></li>
    </ul>
  </div>
</div>
</div>

