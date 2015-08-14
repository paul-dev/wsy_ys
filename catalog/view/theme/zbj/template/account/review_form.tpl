<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"> <?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-10'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>" style="width: 80%; padding-left: 5px;"><?php echo $content_top; ?>
      <h1>商品评论</h1>
      <p>请认真如实填写</p>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group required">
                <div class="col-sm-6">
                    <label class="control-label">等级</label>&nbsp;&nbsp;
                    <input type="radio" name="rating_level" value="5" /> 好评&nbsp;&nbsp;
                    <input type="radio" name="rating_level" value="3" /> 中评&nbsp;&nbsp;
                    <input type="radio" name="rating_level" value="1" /> 差评&nbsp;&nbsp;
                    <?php if ($error_rating_level) { ?>
                    <div class="text-danger"><?php echo $error_rating_level; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group required">
                <div class="col-sm-6" style="width: 25%;">
                    <div class="rating">
                        <label class="control-label">描述</label>&nbsp;&nbsp;
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <?php if (0 < $i) { ?>
                        <span class="fa fa-stack" onclick="addStar(this)" id="product_<?php echo $i; ?>"><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } else { ?>
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } ?>
                        <?php } ?>
                    </div>
                    <input type="hidden" name="rating_product" value="0" />
                    <?php if ($error_rating_product) { ?>
                    <div class="text-danger"><?php echo $error_rating_product; ?></div>
                    <?php } ?>
                </div>
                <div class="col-sm-6" style="width: 25%;">
                    <div class="rating">
                        <label class="control-label">质量</label>&nbsp;&nbsp;
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <?php if (0 < $i) { ?>
                        <span class="fa fa-stack" onclick="addStar(this)" id="quality_<?php echo $i; ?>"><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } else { ?>
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } ?>
                        <?php } ?>
                    </div>
                    <input type="hidden" name="rating_quality" value="0" />
                    <?php if ($error_rating_quality) { ?>
                    <div class="text-danger"><?php echo $error_rating_quality; ?></div>
                    <?php } ?>
                </div>
                <div class="col-sm-6" style="width: 25%;">
                    <div class="rating">
                        <label class="control-label">服务</label>&nbsp;&nbsp;
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <?php if (0 < $i) { ?>
                        <span class="fa fa-stack" onclick="addStar(this)" id="service_<?php echo $i; ?>"><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } else { ?>
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } ?>
                        <?php } ?>
                    </div>
                    <input type="hidden" name="rating_service" value="0" />
                    <?php if ($error_rating_service) { ?>
                    <div class="text-danger"><?php echo $error_rating_service; ?></div>
                    <?php } ?>
                </div>
                <div class="col-sm-6" style="width: 25%;">
                    <div class="rating">
                        <label class="control-label">物流</label>&nbsp;&nbsp;
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <?php if (0 < $i) { ?>
                        <span class="fa fa-stack" onclick="addStar(this)" id="deliver_<?php echo $i; ?>"><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } else { ?>
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } ?>
                        <?php } ?>
                    </div>
                    <input type="hidden" name="rating_deliver" value="0" />
                    <?php if ($error_rating_deliver) { ?>
                    <div class="text-danger"><?php echo $error_rating_deliver; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label" for="input-review">评论</label>
                    <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                    <?php if ($error_text) { ?>
                    <div class="text-danger"><?php echo $error_text; ?></div>
                    <?php } ?>
                    <div class="help-block">
                        <span class="text-danger">注意:</span> 不接受 HTML 格式内容！
                    </div>
                </div>
            </div>
            <div class="form-group required">
                <div class="col-md-3" style="width: 20%;">
                    <label class="control-label" for="input-captcha">验证码</label>
                    <input type="text" name="captcha" value="" id="input-captcha" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <img src="index.php?route=tool/captcha" alt="" id="captcha" />
                    <?php if ($error_captcha) { ?>
                    <div class="text-danger"><?php echo $error_captcha; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="buttons clearfix">
                <div class="pull-right">
                    <input type="submit" id="button-review" class="btn btn-primary" value="确认提交" />
                </div>
            </div>
        </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
    function addStar(obj) {
        var _ids = $(obj).attr('id').split('_');
        var _rating = false;
        if ($(obj).find('i.fa-star').length > 0) {
            //$(obj).find('i').first().remove();
            var _flag = false;
            $(obj).parent('.rating').find('span').each(function(){
                if ($(this).attr('id') == $(obj).attr('id')) _flag = true;
                if ($(this).find('i.fa-star').length > 0 && _flag)
                    $(this).find('i').first().remove();
            });
            _rating = _ids[1] - 1;
        } else {
            $(obj).parent('.rating').find('span').each(function(){
                if ($(this).attr('id') == $(obj).attr('id')) return false;
                if ($(this).find('i.fa-star').length == 0)
                    $(this).find('i').first().before('<i class="fa fa-star fa-stack-2x"></i>');
            });
            $(obj).find('i').first().before('<i class="fa fa-star fa-stack-2x"></i>');
            _rating = _ids[1];
        }

        //if (_rating < 1) _rating = 1;
        $('input[name="rating_'+_ids[0]+'"]').val(_rating);
    }
    $('input[name="rating_level"]').click(function(){
        var _level = $('input[name="rating_level"]:checked').val();
        $('.rating').each(function(){
            $(this).find('span i.fa-star').remove();
            $(this).find('span').eq(_level - 1).click();
        });
    });
    //--></script>
<?php echo $footer; ?>
