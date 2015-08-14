<div class="banners" style="margin-top:-15px;">
    <div id="special-sider">
        <?php foreach ($banners as $banner) { ?>
        <?php if ($banner['link']) { ?>
        <a href="<?php echo $banner['link']; ?>" target="_blank" title="<?php echo $banner['title']; ?>" style="background:url(<?php echo $banner['image']; ?>)  50% 50% no-repeat;"></a>
        <?php } else { ?>
        <a href="javascript:void(0);" target="_blank" title="<?php echo $banner['title']; ?>" style="background:url(<?php echo $banner['image']; ?>)  50% 50% no-repeat;"></a>
        <?php } ?>
        <?php } ?>
    </div>
    <div class="setted_content" style="position:relative">
        <div class="banners-nav">
            <ul>
                <?php foreach ($banners as $banner) { ?>
                <li></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<script>
    $(function(){
        var bannerLength = $('#special-sider a').length;
        $('#special-sider a').eq(0).fadeIn().siblings().fadeOut();
        $('.banners-nav ul li').eq(0).addClass('current').siblings().removeClass('current');
        var _i=0
        function autoPlay(){
            _i= _i>=bannerLength-1?0:_i+1;
            $('#special-sider a').eq(_i).fadeIn().siblings().fadeOut();
            $('.banners-nav ul li').eq(_i).addClass('current').siblings().removeClass('current');

        }
        $('#special-sider a').hover(function(){
            clearInterval(zs_timer);
        },function(){
            zs_timer = setInterval(autoPlay,3000);
        });
        $('.banners-nav ul li').click(function(){
            _i = $(this).index();
            $('#special-sider a').eq(_i).fadeIn().siblings().fadeOut();
            $('.banners-nav ul li').eq(_i).addClass('current').siblings().removeClass('current');
        });
        var zs_timer = setInterval(autoPlay,3000);
    });
</script>