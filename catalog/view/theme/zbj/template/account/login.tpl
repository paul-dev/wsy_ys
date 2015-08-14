<?php echo $header; ?>
<div class="container">
	 <?php if ($success) { ?>
	  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
	  <?php } ?>
	  <?php if ($error_warning) { ?>
	  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
	  <?php } ?>
	<div class="main_login">
        <div class="left login-bg" style="width: 320px; margin-top: -15px;">
            <!--<a href="#"><img src="/catalog/view/theme/zbj/image/registerbg.png" width="320" height="300" alt="注册背景图片" title="注册背景图片"></a>-->
            <?php echo $content_top; ?>
        </div>
		<div class="right register-content">
			<h1 class="title">用户登录</h1>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
	      		<div class="form-group">
	        		<label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
	        		<input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
	      		</div>
	            <div class="form-group">
	                <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
	                <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
	                <p style="margin-top:10px;">
	                	<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
	                </p>
	            </div>
	              <input type="submit" value="<?php echo $button_login; ?>" id="submit-login-btn" class="btn btn-primary" />
	              <?php if ($redirect) { ?>
	              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
	              <?php } ?>
	        </form>
		</div>
	</div>
</div>
<?php echo $footer; ?>