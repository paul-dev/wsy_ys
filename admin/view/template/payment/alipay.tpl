<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-alipay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-alipay">
                    <table class="form">
                        <tr>
                            <td><span class="required">*</span> <?php echo $entry_trade_type; ?><br>(请根据对应的接口填入正确的参数)</td>
                            <td>
                                <?php if ($alipay_trade_type=='trade_create_by_buyer') { ?>
                                双接口:&nbsp;<input type="radio" name="alipay_trade_type" value="trade_create_by_buyer"  checked="true"/>&nbsp;&nbsp;
                                直接到帐:&nbsp;<input type="radio" name="alipay_trade_type" value="create_direct_pay_by_user"/>&nbsp;&nbsp;
                                担保接口:&nbsp;<input type="radio" name="alipay_trade_type" value="create_partner_trade_by_buyer"/>&nbsp;&nbsp;
                                <?php } else if($alipay_trade_type=='create_direct_pay_by_user') {  ?>
                                双接口:&nbsp;<input type="radio" name="alipay_trade_type" value="trade_create_by_buyer"/>&nbsp;&nbsp;
                                直接到帐:&nbsp;<input type="radio" name="alipay_trade_type" value="create_direct_pay_by_user"  checked="true"/>&nbsp;&nbsp;
                                担保接口:&nbsp;<input type="radio" name="alipay_trade_type" value="create_partner_trade_by_buyer"/>&nbsp;&nbsp;
                                <?php } else {  ?>
                                双接口:&nbsp;<input type="radio" name="alipay_trade_type" value="trade_create_by_buyer"/>&nbsp;&nbsp;
                                直接到帐:&nbsp;<input type="radio" name="alipay_trade_type" value="create_direct_pay_by_user"/>&nbsp;&nbsp;
                                担保接口:&nbsp;<input type="radio" name="alipay_trade_type" value="create_partner_trade_by_buyer"  checked="true"/>&nbsp;&nbsp;
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="required">*</span> <?php echo $entry_partner; ?></td>
                            <td><input type="text" name="alipay_partner" value="<?php echo $alipay_partner; ?>" size="50" />
                                <?php if ($error_partner) { ?>
                                <span class="error"><?php echo $error_partner; ?></span>
                                <?php } ?></td>
                        </tr>

                        <tr>
                            <td><span class="required">*</span> <?php echo $entry_security_code; ?></td>
                            <td><input type="text" name="alipay_security_code" value="<?php echo $alipay_security_code; ?>" size="50" />
                                <?php if ($error_secrity_code) { ?>
                                <span class="error"><?php echo $error_secrity_code; ?></span>
                                <?php } ?></td>
                        </tr>
                        <tr>
                            <td><span class="required">*</span> <?php echo $entry_seller_email; ?></td>
                            <td><input type="text" name="alipay_seller_email" value="<?php echo $alipay_seller_email; ?>" size="50" />
                                <?php if ($error_email) { ?>
                                <span class="error"><?php echo $error_email; ?></span>
                                <?php } ?></td>
                        </tr>

                        <!--
                            <tr>
                                  <td><span class="required">*</span> <?php echo $entry_anti_phishing; ?></td>
                                  <td>
                                   <?php if ($alipay_anti_phishing==1) { ?>
                                        Yes:&nbsp;<input type="radio" name="alipay_anti_phishing" value="1" checked="true"/>&nbsp;&nbsp;
                                        No:&nbsp;<input type="radio" name="alipay_anti_phishing" value="0"/>&nbsp;&nbsp;
                                    <?php } else { ?>
                                        Yes:&nbsp;<input type="radio" name="alipay_anti_phishing" value="1"/>&nbsp;&nbsp;
                                        No:&nbsp;<input type="radio" name="alipay_anti_phishing" value="0" checked="true"/>&nbsp;&nbsp;
                                    <?php } ?>
                                </td>
                            </tr>
                        -->
                        <tr>
                            <td><?php echo $entry_order_status; ?></td>
                            <td><select name="alipay_order_status_id">
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $alipay_order_status_id) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_status; ?></td>
                            <td><select name="alipay_status">
                                    <?php if ($alipay_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_sort_order; ?></td>
                            <td><input type="text" name="alipay_sort_order" value="<?php echo $alipay_sort_order; ?>" size="1" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>使用注意已经存在CNY的人民币汇率设置。Code为CNY</td>
                        </tr>
                    </table>
                </form>            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>