<script type="text/javascript" src="catalog/view/javascript/bootstrap/js/jquery.SuperSlide.2.1.1.js"></script>
<div class="top-banner">
    <!-- <div id="slideshow<?php echo $module; ?>" class="owl-carousel" style="opacity: 1; width:100%;">
        <?php foreach ($banners as $banner) { ?>
        <div class="item">
            <?php if ($banner['link']) { ?>
            <a href="<?php echo $banner['link']; ?>"><img src="http://d02.res.meilishuo.net/img/_hd/e9/3a/dfba14efee25a3341da546e52c1a_2000_420.cg.jpg<?php //echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
            <?php } else { ?>
            <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
            <?php } ?>
        </div>
        <?php } ?>
    </div>-->
    <div class="banner-img">
        <ul>
            <?php foreach ($banners as $banner) { ?>
            <?php if ($banner['link']) { ?>
            <li><a href="<?php echo $banner['link']; ?>" target="_blank" title="<?php echo $banner['title']; ?>" style="background:url(<?php echo $banner['image']; ?>) no-repeat center 0"></a></li>
            <?php } else { ?>
            <li><a href="javascript:void(0);" title="<?php echo $banner['title']; ?>" style="background:url(<?php echo $banner['image']; ?>) no-repeat center 0"></a></li>
            <?php } ?>
            <?php } ?>
        </ul>
    </div>
</div>
<script>
    $(function(){
        //获取屏幕宽度并初始化 ul li等相关css属性
        var windowW = $(window).width();
        var _thisli = $('.banner-img').find('ul li');
        var active = 0;
        liLeng = _thisli.length;
        $('.banner-img').find('ul').css({'width':liLeng*windowW+'px'});
        _thisli.css('width',windowW+'px');


        //动态添加nav按钮，并设置样式
        var spanHtml ='';
         for(var i=0;i<liLeng;i++){
            spanHtml+='<span></span>';
         }
         $('.nav-btn').append(spanHtml);
         $('.nav-btn span:first').addClass('active');
         var navBtnWidth = $('.nav-btn span').width()+6;
         var navBtn = navBtnWidth*liLeng;
         $('.nav-btn').css({'width':navBtn+'px','height':'15px','margin-left':-navBtn/2+'px'});

         //自动播放
         function autoPlay(){
            active=active >=liLeng-1 ? 0:active+1;
             $('.banner-img').find('ul').animate({'left':-active*windowW+'px'},500);
             $('.nav-btn span').eq(active).addClass('active').siblings().removeClass('active');
         }

         //浏览器窗口发生变化 重置ul li
         $(window).resize(function(){
            windowW = $(window).width();
            $('.banner-img').find('ul').css({'width':liLeng*windowW+'px','left':-active*windowW+'px'});
            _thisli.css('width',windowW+'px');
         });
         
         
         //nav小导航点击事件
         $('.nav-btn span').click(function(){
            active = $(this).index();
            $(this).addClass('active').siblings().removeClass('active');
            $('.banner-img').find('ul').animate({'left':-active*windowW+'px'},500);
         });

         
         //鼠标移动 img 停止轮播
         $('.nav-btn span,.banner-img,.zbj-buttons .zbj-btn').hover(function(){
            $('.zbj-buttons .zbj-btn').css('opacity','1');
            clearInterval(timer);
         },function(){
            $('.zbj-buttons .zbj-btn').css('opacity','0');
            timer = setInterval(autoPlay,3000);
         });


         //上一个下一个按钮功能
         $('.zbj-buttons .prevbtn').click(function(){
            if(!$('.banner-img ul').is(':animated')){
                active = active<=0?liLeng-1:active-1;
                $('.banner-img').find('ul').animate({'left':-active*windowW+'px'},500);
                 $('.nav-btn span').eq(active).addClass('active').siblings().removeClass('active');
             }
         });
         $('.zbj-buttons .nextbtn').click(function(){
            if(!$('.banner-img ul').is(':animated')) autoPlay();
         });
         var timer = setInterval(autoPlay,3000);
    });
</script>
<div class="container" style="position:relative;height:115px;">
    <div class="zbj-buttons">
        <div class="prevbtn zbj-btn"><i class="fa fa-chevron-left fa-5x"></i></div>
        <div class="nextbtn zbj-btn"><i class="fa fa-chevron-right fa-5x"></i></div>
    </div>
    <div class="nav-btn"></div>
<div class="xbanner" >
<div class="owl-lf left">
	<ul>
    	<!--
        <?php
    	$_li = 0;
    	foreach ($category_tree as $category) {
    	$_li++;
    	?>
        <li<?php if ($_li == 1) echo ' class="last"'; ?>>
            <h3><?php echo $category['name']; ?></h3>
            <?php if (!empty($category['children'])) { ?>
            <p>
                <?php
                $_ai = 0;
                foreach ($category['children'] as $child) {
                $_ai++;
                ?>
                <a<?php if ($_ai == 1) echo ' class="red_a"'; ?> href="<?php echo $child['href']; ?>" title="<?php echo $child['name']; ?>"><?php echo $child['name']; ?></a>
                <?php } ?>
            </p>
            <?php } ?>

            <?php if ($category['filter']) { ?>
            <span class="owl-lb">></span>
            <div class="owl-drop">
                <?php
                foreach ($category['filter'] as $filter) {
                $filter_name = explode('_', $filter['name']);
                if (count($filter_name) > 1) {
                $filter_name = array_pop($filter_name);
                } else {
                $filter_name = $filter['name'];
                }
                ?>
                    <h3><a href="#"><?php echo $filter_name; ?></a></h3>
                    <?php if ($filter['filter']) { ?>
                    <p style="height: auto;">
                        <?php foreach ($filter['filter'] as $_filter) { ?>
                        <a href="<?php echo $category['href'].'&filter='.$_filter['filter_id']?>"><?php echo $_filter['name']; ?></a>
                        <?php } ?>
                    </p>
                    <?php } ?>
                <?php } ?>
            </div>
            <?php } ?>
        </li>
        <?php } ?>
        -->
        <li class="last">
            <div class="nav_big_class">
                <h3><span class="nav_class_icon nav_icon_one"></span>最热</h3>
                <p>
                    <a class="red_a" href="index.php?route=product/search&search=转运珠" target="_blank">转运珠</a>
                    <a href="index.php?route=product/category&path=20_26" target="_blank">钻戒</a>
                    <a href="index.php?route=product/category&path=34_48" target="_blank">珍珠</a>
                    <a href="index.php?route=product/category&path=57_53" target="_blank">黄金项链</a>
                    <a href="index.php?route=product/category&path=20_66" target="_blank">裸钻</a>
                    <a href="index.php?route=product/search&search=佛珠" target="_blank">佛珠</a>
                    <a href="index.php?route=product/category&path=17_115" target="_blank">黄花梨</a>
                    <a href="index.php?route=product/category&path=17_94&search=手串" target="_blank">崖柏手串</a>
                    <a href="index.php?route=product/category&path=17_93" target="_blank">小叶紫檀</a>
                    <a href="index.php?route=product/category&path=17_95" target="_blank">菩提</a>
                    <a href="index.php?route=product/category&path=57_72" target="_blank">投资金条</a>
                </p>
                <span class="owl-lb">></span>
            </div>
            <div class="owl-drop">
                <h3><a href="index.php?route=product/search&type=hot" target="_blank">最热</a></h3>
                <p style="height: auto;">
                    <a class="red_a" href="index.php?route=product/search&search=转运珠" target="_blank">转运珠</a>
                    <a href="index.php?route=product/category&path=20_26" target="_blank">钻戒</a>
                    <a href="index.php?route=product/category&path=34_48" target="_blank">珍珠</a>
                    <a href="index.php?route=product/category&path=57_53" target="_blank">黄金项链</a>
                    <a href="index.php?route=product/category&path=20_66" target="_blank">裸钻</a>
                    <a href="index.php?route=product/search&search=佛珠" target="_blank">佛珠</a>
                    <a href="index.php?route=product/category&path=17_115" target="_blank">黄花梨</a>
                    <a href="index.php?route=product/category&path=17_94&search=手串" target="_blank">崖柏手串</a>
                    <a href="index.php?route=product/category&path=17_93" target="_blank">小叶紫檀</a>
                    <a href="index.php?route=product/category&path=17_95" target="_blank">菩提</a>
                    <a href="index.php?route=product/category&path=57_72&search=金条" target="_blank">投资金条</a>
                    <a href="index.php?route=product/category&path=25" target="_blank">彩宝</a>
                </p>
            </div>
        </li>
        <li>
            <div class="nav_big_class" style="background:#fff">
                <h3><span class="nav_class_icon nav_icon_two"></span>黄金珠宝</h3>
                <p>
                    <a class="red_a" href="index.php?route=product/category&path=57&search=黄金" target="_blank">黄金</a>
                    <a href="index.php?route=product/category&path=20" target="_blank">钻石</a>
                    <a href="index.php?route=product/category&path=25" target="_blank">彩色宝石</a>
                    <a href="index.php?route=product/category&path=34_48" target="_blank">珍珠</a>
                    <a href="index.php?route=product/category&path=34&search=翡翠" target="_blank">翡翠</a>
                    <a href="index.php?route=product/category&path=34&search=玉石" target="_blank">玉石</a>
                    <a href="index.php?route=product/category&path=57&search=铂金" target="_blank">铂金</a>
                    <a href="index.php?route=product/category&path=34_117" target="_blank">琥珀</a>
                    <a href="index.php?route=product/category&path=57_118" target="_blank">黄金吊坠</a>
                    <a href="index.php?route=product/category&path=57_54" target="_blank">黄金戒指</a>
                    <a href="index.php?route=product/category&path=57_56" target="_blank">黄金手链</a>
                </p>
                <span class="owl-lb">></span>
            </div>
            <div class="owl-drop">
                <h3><a href="index.php?route=product/category&path=57" target="_blank">黄金珠宝</a></h3>
                <p style="height: auto;">
                    <a class="red_a" href="index.php?route=product/category&path=57&search=黄金" target="_blank">黄金</a>
                    <a href="index.php?route=product/category&path=20" target="_blank">钻石</a>
                    <a href="index.php?route=product/category&path=25" target="_blank">彩色宝石</a>
                    <a href="index.php?route=product/category&path=34_48" target="_blank">珍珠</a>
                    <a href="index.php?route=product/category&path=34&search=翡翠" target="_blank">翡翠</a>
                    <a href="index.php?route=product/category&path=34&search=玉石" target="_blank">玉石</a>
                    <a href="index.php?route=product/category&path=57&search=铂金" target="_blank">铂金</a>
                    <a href="index.php?route=product/category&path=34_117" target="_blank">琥珀</a>
                    <a href="index.php?route=product/category&path=57_118" target="_blank">黄金吊坠</a>
                    <a href="index.php?route=product/category&path=57_54" target="_blank">黄金戒指</a>
                    <a href="index.php?route=product/category&path=57_56" target="_blank">黄金手链</a>
                    <a href="index.php?route=product/category&path=57_72&search=金" target="_blank">投资金</a>
                    <a href="index.php?route=product/category&path=20_66" target="_blank">裸钻</a>
                    <a href="index.php?route=product/category&path=20_41" target="_blank">钻石吊坠</a>
                    <a href="index.php?route=product/category&path=34_48&search=项链" target="_blank">珍珠项链</a>
                    <a href="index.php?route=product/category&path=57_72&search=银" target="_blank">投资银</a>
                    <a href="index.php?route=product/category&path=34&search=碧玺" target="_blank">碧玺</a>
                    <a href="index.php?route=product/category&path=34&search=蜜蜡" target="_blank">蜜蜡</a>
                    <a href="index.php?route=product/category&path=34&search=手镯" target="_blank">玉镯</a>
                </p>
            </div>
        </li>
        <li>
            <div class="nav_big_class" style="background:#fff">
                <h3><span class="nav_class_icon nav_icon_three"></span>精选饰品</h3>
                <p>
                    <a class="red_a" href="index.php?route=product/search&search=饰品" target="_blank">饰品</a>
                    <a href="index.php?route=product/search&search=手链" target="_blank">手链</a>
                    <a href="index.php?route=product/search&search=项链" target="_blank">项链</a>
                    <a href="index.php?route=product/search&search=手镯" target="_blank">手镯</a>
                    <a href="index.php?route=product/search&search=发饰" target="_blank">发饰</a>
                    <a href="index.php?route=product/search&search=项坠" target="_blank">项坠</a>
                    <a href="index.php?route=product/search&search=戒指" target="_blank">戒指</a>
                    <a href="index.php?route=product/search&search=耳饰" target="_blank">耳饰</a>
                    <a href="index.php?route=product/search&search=银手镯" target="_blank">银手镯</a>
                    <a href="index.php?route=product/search&search=佛珠" target="_blank">佛珠</a>
                    <a href="index.php?route=product/category&path=17_94&search=手串" target="_blank">崖柏手串</a>
                    <a href="index.php?route=product/category&path=25_88&search=石榴石" target="_blank">石榴石</a>
                </p>
                <span class="owl-lb">></span>
            </div>
            <div class="owl-drop">
                <h3><a href="index.php?route=product/search&search=饰品" target="_blank">精选饰品</a></h3>
                <p style="height: auto;">
                    <a class="red_a" href="index.php?route=product/search&search=饰品" target="_blank">饰品</a>
                    <a href="index.php?route=product/search&search=手链" target="_blank">手链</a>
                    <a href="index.php?route=product/search&search=项链" target="_blank">项链</a>
                    <a href="index.php?route=product/search&search=手镯" target="_blank">手镯</a>
                    <a href="index.php?route=product/search&search=发饰" target="_blank">发饰</a>
                    <a href="index.php?route=product/search&search=项坠" target="_blank">项坠</a>
                    <a href="index.php?route=product/search&search=戒指" target="_blank">戒指</a>
                    <a href="index.php?route=product/search&search=耳饰" target="_blank">耳饰</a>
                    <a href="index.php?route=product/search&search=银手镯" target="_blank">银手镯</a>
                    <a href="index.php?route=product/search&search=佛珠" target="_blank">佛珠</a>
                    <a href="index.php?route=product/category&path=17_94&search=手串" target="_blank">崖柏手串</a>
                    <a href="index.php?route=product/category&path=25_88&search=石榴石" target="_blank">石榴石</a>
                    <a href="index.php?route=product/category&path=25_119" target="_blank">水晶饰品</a>
                    <a href="index.php?route=product/search&search=红绳手链" target="_blank">红绳手链</a>
                    <a href="index.php?route=product/search&search=锁骨链" target="_blank">锁骨链</a>
                </p>
            </div>
        </li>
        <li>
            <div class="nav_big_class" style="background:#fff">
                <h3><span class="nav_class_icon nav_icon_four"></span>奇趣收藏</h3>
                <p>
                    <a class="red_a" href="index.php?route=product/category&path=17&search=木" target="_blank">木器制品</a>
                    <a href="index.php?route=product/category&path=17_37" target="_blank">瓷器</a>
                    <a href="index.php?route=product/category&path=17_96" target="_blank">奇石</a>
                    <a href="index.php?route=product/category&path=17_38" target="_blank">古玩字画</a>
                    <a href="index.php?route=product/category&path=17_40" target="_blank">紫砂</a>
                    <a href="index.php?route=product/category&path=18&search=礼品" target="_blank">礼品</a>
                    <a href="index.php?route=product/category&path=18&search=工艺品" target="_blank">工艺品</a>
                </p>
                <span class="owl-lb">></span>
            </div>
            <div class="owl-drop">
                <h3><a href="#">奇趣收藏</a></h3>
                <p style="height: auto;">
                    <a class="red_a" href="index.php?route=product/category&path=17&search=木" target="_blank">木器制品</a>
                    <a href="index.php?route=product/category&path=17_37" target="_blank">瓷器</a>
                    <a href="index.php?route=product/category&path=17_96" target="_blank">奇石</a>
                    <a href="index.php?route=product/category&path=17_38" target="_blank">古玩字画</a>
                    <a href="index.php?route=product/category&path=17_40" target="_blank">紫砂</a>
                    <a href="index.php?route=product/category&path=18&search=礼品" target="_blank">礼品</a>
                    <a href="index.php?route=product/category&path=18&search=工艺品" target="_blank">工艺品</a>
                </p>
            </div>
        </li>
        <li>
            <div class="nav_big_class" style="background:#fff">
                <h3><span class="nav_class_icon nav_icon nav_icon_five"></span>腕表名表</h3>
                <p>
                    <a class="red_a" href="index.php?route=product/category&path=33_98" target="_blank">机械表</a>
                    <a href="index.php?route=product/category&path=33_101" target="_blank">光动能表</a>
                    <a href="index.php?route=product/category&path=33_102" target="_blank">时装表</a>
                    <a href="index.php?route=product/category&path=33_103" target="_blank">军用表</a>
                    <a href="index.php?route=product/category&path=33_104" target="_blank">运动表</a>
                    <a href="index.php?route=product/category&path=33&search=情侣" target="_blank">情侣表</a>
                    <a href="index.php?route=product/category&path=33&search=瑞士" target="_blank">瑞士表</a>
                    <a href="index.php?route=product/category&path=33&search=防水" target="_blank">防水表</a>
                </p>
                <span class="owl-lb">></span>
            </div>
            <div class="owl-drop">
                <h3><a href="#">奇趣收藏</a></h3>
                <p style="height: auto;">
                    <a class="red_a" href="index.php?route=product/category&path=33_98" target="_blank">机械表</a>
                    <a href="index.php?route=product/category&path=33_101" target="_blank">光动能表</a>
                    <a href="index.php?route=product/category&path=33_102" target="_blank">时装表</a>
                    <a href="index.php?route=product/category&path=33_103" target="_blank">军用表</a>
                    <a href="index.php?route=product/category&path=33_104" target="_blank">运动表</a>
                    <a href="index.php?route=product/category&path=33&search=情侣" target="_blank">情侣表</a>
                    <a href="index.php?route=product/category&path=33&search=瑞士" target="_blank">瑞士表</a>
                    <a href="index.php?route=product/category&path=33&search=防水" target="_blank">防水表</a>
                </p>
            </div>
        </li>

    </ul>
</div>
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


<div class="owl-ri right">
    <?php if (isset($inner_slide[1])) echo $inner_slide[1]; ?>
    <div class="notice">
    	<div class="notice_top"><a href="#" class="br_ri on">最新公告</a><a href="#">公告资讯</a></div>
        <div class="notice_conten">
        <ul>
        	<?php foreach ($latest_notice as $notice) { ?>
            <li><a href="<?php echo $notice['href']; ?>" title="<?php echo $notice['full_title']; ?>"><?php echo $notice['title']; ?></a></li>
            <?php } ?>
        </ul>
        <ul>
            <?php foreach ($latest_news as $news) { ?>
            <li><a href="<?php echo $news['href']; ?>" title="<?php echo $news['full_title']; ?>"><?php echo $news['title']; ?></a></li>
            <?php } ?>
        </ul>
        </div>
    </div>
</div>
<!-- banner down -->
<?php if (isset($inner_slide[0])) echo $inner_slide[0]; ?> 
</div>
    
</div>
<script type="text/javascript"><!--
/*$('#slideshow<?php echo $module; ?>').owlCarousel({
	items: 1,
	autoPlay: 3000,
	singleItem: true,
	navigation: true,
	navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
	pagination: true,
    stopOnHover: true,
    scrollPerPage: true
});*/

jQuery(".baner-gg").slide({mainCell:".bd .ulWrap",autoPage:true,effect:"leftLoop",autoPlay:false,prevCell:".owl_prev",nextCell:".owl_next"});
jQuery(".notice").slide({titCell:".notice_top a",mainCell:".notice_conten"});

--></script>