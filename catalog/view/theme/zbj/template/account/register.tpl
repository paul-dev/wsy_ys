<?php echo $header; ?>
<div class="container">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="main_login">
    <div class="left login-bg" style="width: 320px;">
      <!--<a href="#"><img src="/catalog/view/theme/zbj/image/registerbg.png" width="320" height="300" alt="注册背景图片" title="注册背景图片"></a>-->
        <?php echo $content_top; ?>
    </div>
    <div class="right register-content">
      <h1 class="title">用户注册</h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <div class="form-group required row">
              <label class="col-sm-3 control-label" style="width:100px;margin-left:-11px;" for="input-email"><?php echo $entry_email; ?></label>
              <div class="col-sm-9">
                  <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                  <?php if ($error_email) { ?>
                  <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php } ?>
              </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-fullname"><?php echo $entry_fullname; ?></label>
            <div class="col-sm-9">
              <input type="text" name="fullname" value="<?php echo $fullname; ?>" placeholder="<?php echo $entry_fullname; ?>" id="input-fullname" class="form-control" />
              <?php if ($error_fullname) { ?>
              <div class="text-danger"><?php echo $error_fullname; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group required" style="display: none;">
            <label class="col-sm-3 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
            <div class="col-sm-9">
              <input type="tel" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
              <?php if ($error_telephone) { ?>
              <div class="text-danger"><?php echo $error_telephone; ?></div>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-9">
              <input type="password" name="password" value="<?php echo $password; ?>" autocomplete="false" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
            <div class="col-sm-9">
              <input type="password" name="confirm" value="<?php echo $confirm; ?>" autocomplete="false" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?php echo $error_confirm; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group required">
              <label class="col-sm-3 control-label" for="input-captcha">验证码</label>
              <div class="col-sm-9">
                  <input type="text" name="captcha" value="" maxlength="6" autocomplete="false" placeholder="验证码" id="input-captcha" class="form-control" style="width: 30%; float: left;" />
                  <img src="index.php?route=tool/captcha" alt="验证码" title="看不清？点击换一张！" id="captcha" style="cursor: pointer; float: right;" />
                  <?php if ($error_captcha) { ?>
                  <div class="text-danger" style="clear: both;"><?php echo $error_captcha; ?></div>
                  <?php } ?>
              </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo $entry_newsletter; ?></label>
            <div class="col-sm-9">
              <?php if ($newsletter) { ?>
              <label class="radio-inline">
                <input type="radio" name="newsletter" value="1" checked="checked" />
                <?php echo $text_yes; ?></label>
              <label class="radio-inline">
                <input type="radio" name="newsletter" value="0" />
                <?php echo $text_no; ?></label>
              <?php } else { ?>
              <label class="radio-inline">
                <input type="radio" name="newsletter" value="1" />
                <?php echo $text_yes; ?></label>
              <label class="radio-inline">
                <input type="radio" name="newsletter" value="0" checked="checked" />
                <?php echo $text_no; ?></label>
              <?php } ?>
            </div>
          </div>
          <?php if ($text_agree) { ?>
        <div class="buttons">
          <div style="padding:0 20px;text-align:center">
            <input type="submit" value="立 即 注 册" class="btn btn-primary" style="width: 100%;" />
            <p style="padding-top:10px;">
              <?php if ($agree || $agree === false) { ?>
                <input type="checkbox" name="agree" value="1" checked="checked" style="position:relative;top:3px;"/>
              <?php } else { ?>
                <input type="checkbox" name="agree" value="1" style="position:relative;top:3px;" />
              <?php } ?>
                <?php echo $text_agree; ?>
            </p>
          </div>
        </div>
        <?php } else { ?>
        <div class="buttons">
          <div class="pull-right">
            <input type="submit" value="立 即 注 册" class="btn btn-primary" style="width: 100%;" />
          </div>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
    $('#captcha').on('click', function(){
        $(this).attr('src', 'index.php?route=tool/captcha#'+new Date().getTime());
        $('input[name=\'captcha\']').val('');
    });
//--></script>
<?php echo $footer; ?>