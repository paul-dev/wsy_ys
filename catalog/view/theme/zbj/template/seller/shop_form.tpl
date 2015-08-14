<?php echo $header; ?>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/zbj/stylesheet/base.css">
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/zbj/stylesheet/createshop.css">
    <div class="create-shop">
        <div class="create-steps clearfix">

            <div class="zbjstone first step-input active">
                <div class="zbjstone-icon">
                    <i class="fa fa-circle zbjstone-bg zbjstone-bg-bottom"></i>
                    <i class="fa fa-circle zbjstone-bg zbjstone-bg-top"></i>
                    <i class="zbjstone-text">1</i> 
                </div>
                <div class="zbjstone-line"> 
                    <i class="zbjstline-bg zbjstline-bg-bottom"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top-left"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top-right"></i> 
                </div>
                <div class="zbjstone-label">开店信息填写</div>
            </div>

            <div class="zbjstone step-cert">
                <div class="zbjstone-icon">
                    <i class="fa fa-circle zbjstone-bg zbjstone-bg-bottom"></i>
                    <i class="fa fa-circle zbjstone-bg zbjstone-bg-top"></i>
                    <i class="zbjstone-text">2</i> 
                </div>
                <div class="zbjstone-line"> 
                    <i class="zbjstline-bg zbjstline-bg-bottom"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top-left"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top-right"></i> 
                </div>
                <div class="zbjstone-label">资质审核</div>
            </div>
            
            <div class="zbjstone step-pay">
                <div class="zbjstone-icon">
                    <i class="fa fa-circle zbjstone-bg zbjstone-bg-bottom"></i>
                    <i class="fa fa-circle zbjstone-bg zbjstone-bg-top"></i>
                    <i class="zbjstone-text">3</i> 
                </div>
                <div class="zbjstone-line"> 
                    <i class="zbjstline-bg zbjstline-bg-bottom"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top-left"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top-right"></i> 
                </div>
                <div class="zbjstone-label">缴纳保证金</div>
            </div>

            <div class="zbjstone last step-complete">
                <div class="zbjstone-icon">
                    <i class="fa fa-circle zbjstone-bg zbjstone-bg-bottom"></i>
                    <i class="fa fa-circle zbjstone-bg zbjstone-bg-top"></i>
                    <i class="zbjstone-text">4</i> 
                </div>
                <div class="zbjstone-line"> 
                    <i class="zbjstline-bg zbjstline-bg-bottom"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top-left"></i> 
                    <i class="zbjstline-bg zbjstline-bg-top-right"></i> 
                </div>
                <div class="zbjstone-label">开店成功</div>
            </div>
        </div>

        <style>.alert.alert-danger,.alert.alert-success{margin-top:20px;}</style>
        <div class="container-fluid">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php } ?>
                <?php if ($success && 1 != 1) { ?>
                <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php } ?>
                <?php if ($is_processing) { ?>
                    <?php if ($cert_approve == 1) { ?>
                    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                        认证审核中，请稍后！
                    </div>
                    <script type="text/javascript"><!--
                        $('.zbjstone').removeClass('active');
                        $('.zbjstone.step-cert').addClass('active');
                    //--></script>
                    <?php } elseif ($cert_approve == 9) { ?>
                    <div class="alert alert-success"><i class="fa fa-check-circle"></i>
                        认证审核通过！<br/><?php echo $cert_comment; ?>
                        <br/>点击下载：<a href="/account/download/agreement" target="_blank">珠宝街服务协议</a>
                    </div>
                    <script type="text/javascript"><!--
                        $('.zbjstone').removeClass('active');
                        $('.zbjstone.step-pay').addClass('active');
                    //--></script>
                    <?php } else { ?>
                    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
                        认证审核状态异常，请联系客服！
                    </div>
                    <script type="text/javascript"><!--
                        $('.zbjstone').removeClass('active');
                        $('.zbjstone.step-cert').addClass('active');
                    //--></script>
                    <?php } ?>
                <?php } else { ?>
                <?php if ($cert_approve == 3) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
                    认证审核失败！<br/><?php echo $cert_comment; ?>
                </div>
                <?php } ?>
                <script type="text/javascript"><!--
                    $('.zbjstone').removeClass('active');
                    $('.zbjstone.step-input').addClass('active');
                //--></script>
                <?php } ?>
            </div>

        <?php if (!$is_processing) { ?>
        <div class="shop-title-tip mt20">填写开店信息</div>
        <div class="pt20">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal creatshop-form">
                <div class="tab-content">
                    <p class="title">
                    <span>常规信息</span>（必须完善的基本资料）<span class="red">*</span>
                    </p>
                    <div class="box-form-info" id="tab-general">
                        <div class="form-group required" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-url"><span data-toggle="tooltip" data-html="true" title="<?php echo htmlspecialchars($help_url); ?>"><?php echo $entry_url; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="config_url" value="<?php //echo $config_url; ?>" placeholder="<?php echo $entry_url; ?>" id="input-url" class="form-control" />
                                <?php if ($error_url) { ?>
                                <div class="text-danger"><?php echo $error_url; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-shop-key"><span data-toggle="tooltip" data-html="true" title="<?php echo htmlspecialchars($help_shop_key); ?>"><?php echo $entry_shop_key; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="config_key" value="<?php echo $config_key; ?>" placeholder="<?php echo $entry_shop_key; ?>" id="input-shop-key" class="form-control" />
                                <?php if ($error_shop_key) { ?>
                                <div class="text-danger"><?php echo $error_shop_key; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-ssl"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_ssl; ?>"><?php echo $entry_ssl; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="config_ssl" value="<?php echo $config_ssl; ?>" placeholder="<?php echo $entry_ssl; ?>" id="input-ssl" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="config_name" value="<?php echo $config_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                                <?php if ($error_name) { ?>
                                <div class="text-danger"><?php echo $error_name; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-owner"><?php echo $entry_owner; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="config_owner" value="<?php echo $config_owner; ?>" placeholder="<?php echo $entry_owner; ?>" id="input-owner" class="form-control" />
                                <?php if ($error_owner) { ?>
                                <div class="text-danger"><?php echo $error_owner; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-address"><?php echo $entry_address; ?></label>
                            <div class="col-sm-10">
                                <textarea name="config_address" rows="5" placeholder="<?php echo $entry_address; ?>" id="input-address" class="form-control"><?php echo $config_address; ?></textarea>
                                <?php if ($error_address) { ?>
                                <div class="text-danger"><?php echo $error_address; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group"<?php if (!$is_seller) echo ' style="display: none;"'; ?>>
                            <label class="col-sm-2 control-label" for="input-geocode"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_geocode; ?>"><?php echo $entry_geocode; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="config_geocode" value="<?php echo $config_geocode; ?>" placeholder="<?php echo $entry_geocode; ?>" id="input-geocode" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="config_email" value="<?php echo $config_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                <?php if ($error_email) { ?>
                                <div class="text-danger"><?php echo $error_email; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                                <?php if ($error_telephone) { ?>
                                <div class="text-danger"><?php echo $error_telephone; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="config_fax" value="<?php echo $config_fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-open"><span data-toggle="tooltip" title="<?php echo $help_open; ?>"><?php echo $entry_open; ?></span></label>
                            <div class="col-sm-10">
                                <textarea name="config_open" rows="5" placeholder="<?php echo $entry_open; ?>" id="input-open" class="form-control"><?php echo $config_open; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-comment"><span data-toggle="tooltip" title="<?php echo $help_comment; ?>"><?php echo $entry_comment; ?></span></label>
                            <div class="col-sm-10">
                                <textarea name="config_comment" rows="5" placeholder="<?php echo $entry_comment; ?>" id="input-comment" class="form-control"><?php echo $config_comment; ?></textarea>
                            </div>
                        </div>
                        <?php if ($locations) { ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_location; ?>"><?php echo $entry_location; ?></span></label>
                            <div class="col-sm-10">
                                <?php foreach ($locations as $location) { ?>
                                <div class="checkbox">
                                    <label>
                                        <?php if (in_array($location['location_id'], $config_location)) { ?>
                                        <input type="checkbox" name="config_location[]" value="<?php echo $location['location_id']; ?>" checked="checked" />
                                        <?php echo $location['name']; ?>
                                        <?php } else { ?>
                                        <input type="checkbox" name="config_location[]" value="<?php echo $location['location_id']; ?>" />
                                        <?php echo $location['name']; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="box-form-info" id="tab-store" style="display:none">
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-meta-title"><?php echo $entry_meta_title; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="config_meta_title" value="<?php echo $config_meta_title; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title" class="form-control" />
                                <?php if ($error_meta_title) { ?>
                                <div class="text-danger"><?php echo $error_meta_title; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-meta-description"><?php echo $entry_meta_description; ?></label>
                            <div class="col-sm-10">
                                <textarea name="config_meta_description" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description" class="form-control"><?php echo $config_meta_description; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-meta-keyword"><?php echo $entry_meta_keyword; ?></label>
                            <div class="col-sm-10">
                                <textarea name="config_meta_keyword" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword" class="form-control"><?php echo $config_meta_keyword; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-template"><?php echo $entry_template; ?></label>
                            <div class="col-sm-10">
                                <select name="config_template" id="input-template" class="form-control">
                                    <?php foreach ($templates as $template) { ?>
                                    <?php if ($template == $config_template) { ?>
                                    <option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $template; ?>"><?php echo $template; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                                <br />
                                <img src="" alt="" id="template" class="img-thumbnail" /></div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-layout"><?php echo $entry_layout; ?></label>
                            <div class="col-sm-10">
                                <select name="config_layout_id" id="input-layout" class="form-control">
                                    <?php foreach ($layouts as $layout) { ?>
                                    <?php if ($layout['layout_id'] == $config_layout_id) { ?>
                                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-form-info" id="tab-local">
                        <p class="title">
                            <span>归属地信息</span><span class="red">*</span>
                        </p>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
                            <div class="col-sm-10">
                                <select name="config_country_id" id="input-country" class="form-control">
                                    <?php foreach ($countries as $country) { ?>
                                    <?php if ($country['country_id'] == $config_country_id) { ?>
                                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-zone"><?php echo $entry_zone; ?></label>
                            <div class="col-sm-10">
                                <select name="config_zone_id" id="input-zone" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-city">城市</label>
                            <div class="col-sm-10">
                                <select name="config_city_id" id="input-city" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-area">区县</label>
                            <div class="col-sm-10">
                                <select name="config_area_id" id="input-area" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-language"><?php echo $entry_language; ?></label>
                            <div class="col-sm-10">
                                <select name="config_language" id="input-language" class="form-control">
                                    <?php foreach ($languages as $language) { ?>
                                    <?php if ($language['code'] == $config_language) { ?>
                                    <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-currency"><span data-toggle="tooltip" title="<?php echo $help_currency; ?>"><?php echo $entry_currency; ?></span></label>
                            <div class="col-sm-10">
                                <select name="config_currency" id="input-currency" class="form-control">
                                    <?php foreach ($currencies as $currency) { ?>
                                    <?php if ($currency['code'] == $config_currency) { ?>
                                    <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-form-info" id="tab-option" style="display:none">
                        <fieldset>
                            <legend><?php echo $text_items; ?></legend>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-catalog-limit"><span data-toggle="tooltip" title="<?php echo $help_product_limit; ?>"><?php echo $entry_product_limit; ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_product_limit" value="<?php echo $config_product_limit; ?>" placeholder="<?php echo $entry_product_limit; ?>" id="input-catalog-limit" class="form-control" />
                                    <?php if ($error_product_limit) { ?>
                                    <div class="text-danger"><?php echo $error_product_limit; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-list-description-limit"><span data-toggle="tooltip" title="<?php echo $help_product_description_length; ?>"><?php echo $entry_product_description_length; ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_product_description_length" value="<?php echo $config_product_description_length; ?>" placeholder="<?php echo $entry_product_description_length; ?>" id="input-list-description-limit" class="form-control" />
                                    <?php if ($error_product_description_length) { ?>
                                    <div class="text-danger"><?php echo $error_product_description_length; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend><?php echo $text_tax; ?></legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_tax; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_tax) { ?>
                                        <input type="radio" name="config_tax" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_tax" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_tax) { ?>
                                        <input type="radio" name="config_tax" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_tax" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-tax-default"><span data-toggle="tooltip" title="<?php echo $help_tax_default; ?>"><?php echo $entry_tax_default; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="config_tax_default" id="input-tax-default" class="form-control">
                                        <option value=""><?php echo $text_none; ?></option>
                                        <?php  if ($config_tax_default == 'shipping') { ?>
                                        <option value="shipping" selected="selected"><?php echo $text_shipping; ?></option>
                                        <?php } else { ?>
                                        <option value="shipping"><?php echo $text_shipping; ?></option>
                                        <?php } ?>
                                        <?php  if ($config_tax_default == 'payment') { ?>
                                        <option value="payment" selected="selected"><?php echo $text_payment; ?></option>
                                        <?php } else { ?>
                                        <option value="payment"><?php echo $text_payment; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-tax-customer"><span data-toggle="tooltip" title="<?php echo $help_tax_customer; ?>"><?php echo $entry_tax_customer; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="config_tax_customer" id="input-tax-customer" class="form-control">
                                        <option value=""><?php echo $text_none; ?></option>
                                        <?php  if ($config_tax_customer == 'shipping') { ?>
                                        <option value="shipping" selected="selected"><?php echo $text_shipping; ?></option>
                                        <?php } else { ?>
                                        <option value="shipping"><?php echo $text_shipping; ?></option>
                                        <?php } ?>
                                        <?php  if ($config_tax_customer == 'payment') { ?>
                                        <option value="payment" selected="selected"><?php echo $text_payment; ?></option>
                                        <?php } else { ?>
                                        <option value="payment"><?php echo $text_payment; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend><?php echo $text_account; ?></legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-customer-group"><span data-toggle="tooltip" title="<?php echo $help_customer_group; ?>"><?php echo $entry_customer_group; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="config_customer_group_id" id="input-customer-group" class="form-control">
                                        <?php foreach ($customer_groups as $customer_group) { ?>
                                        <?php if ($customer_group['customer_group_id'] == $config_customer_group_id) { ?>
                                        <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_customer_group_display; ?>"><?php echo $entry_customer_group_display; ?></span></label>
                                <div class="col-sm-10">
                                    <?php foreach ($customer_groups as $customer_group) { ?>
                                    <div class="checkbox">
                                        <label>
                                            <?php if (in_array($customer_group['customer_group_id'], $config_customer_group_display)) { ?>
                                            <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                                            <?php echo $customer_group['name']; ?>
                                            <?php } else { ?>
                                            <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                                            <?php echo $customer_group['name']; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                    <?php if ($error_customer_group_display) { ?>
                                    <div class="text-danger"><?php echo $error_customer_group_display; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_customer_price; ?>"><?php echo $entry_customer_price; ?></span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_customer_price) { ?>
                                        <input type="radio" name="config_customer_price" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_customer_price" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_customer_price) { ?>
                                        <input type="radio" name="config_customer_price" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_customer_price" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-account"><span data-toggle="tooltip" title="<?php echo $help_account; ?>"><?php echo $entry_account; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="config_account_id" id="input-account" class="form-control">
                                        <option value="0"><?php echo $text_none; ?></option>
                                        <?php foreach ($informations as $information) { ?>
                                        <?php if ($information['information_id'] == $config_account_id) { ?>
                                        <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend><?php echo $text_checkout; ?></legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_cart_weight; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_cart_weight) { ?>
                                        <input type="radio" name="config_cart_weight" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_cart_weight" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_cart_weight) { ?>
                                        <input type="radio" name="config_cart_weight" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_cart_weight" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_checkout_guest; ?>"><?php echo $entry_checkout_guest; ?></span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_checkout_guest) { ?>
                                        <input type="radio" name="config_checkout_guest" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_checkout_guest" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_checkout_guest) { ?>
                                        <input type="radio" name="config_checkout_guest" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_checkout_guest" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-checkout"><span data-toggle="tooltip" title="<?php echo $help_checkout; ?>"><?php echo $entry_checkout; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="config_checkout_id" id="input-checkout" class="form-control">
                                        <option value="0"><?php echo $text_none; ?></option>
                                        <?php foreach ($informations as $information) { ?>
                                        <?php if ($information['information_id'] == $config_checkout_id) { ?>
                                        <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-order-status"><span data-toggle="tooltip" title="<?php echo $help_order_status; ?>"><?php echo $entry_order_status; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="config_order_status_id" id="input-order-status" class="form-control">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                        <?php if ($order_status['order_status_id'] == $config_order_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend><?php echo $text_stock; ?></legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_stock_display; ?>"><?php echo $entry_stock_display; ?></span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_stock_display) { ?>
                                        <input type="radio" name="config_stock_display" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_stock_display" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_stock_display) { ?>
                                        <input type="radio" name="config_stock_display" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_stock_display" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_stock_checkout; ?>"><?php echo $entry_stock_checkout; ?></span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_stock_checkout) { ?>
                                        <input type="radio" name="config_stock_checkout" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_stock_checkout" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_stock_checkout) { ?>
                                        <input type="radio" name="config_stock_checkout" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_stock_checkout" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="box-form-info" id="tab-image" style="display:none">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                            <div class="col-sm-10"><a href="javascript:void(0);" id="thumb-image" data-toggle="init-image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                <input type="hidden" name="config_image" value="<?php echo $config_image; ?>" id="input-image" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-logo"><span data-toggle="tooltip" title="建议尺寸：50px*50px"><?php echo $entry_logo; ?></span></label>
                            <div class="col-sm-10"><a href="javascript:void(0);" id="thumb-logo" data-toggle="init-image" class="img-thumbnail"><img src="<?php echo $logo; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                <input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="input-logo" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-icon"><span data-toggle="tooltip" title="<?php echo $help_icon; ?>"><?php echo $entry_icon; ?></span></label>
                            <div class="col-sm-10"><a href="" id="thumb-icon" data-toggle="init-image" class="img-thumbnail"><img src="<?php echo $icon; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                <input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="input-icon" />
                            </div>
                        </div>
                        <div class="form-group required" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-image-category-width"><?php echo $entry_image_category; ?></label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_category_width" value="<?php echo $config_image_category_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-category-width" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_category_height" value="<?php echo $config_image_category_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_image_category) { ?>
                                <div class="text-danger"><?php echo $error_image_category; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-image-thumb-width"><?php echo $entry_image_thumb; ?></label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_thumb_width" value="<?php echo $config_image_thumb_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-thumb-width" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_thumb_height" value="<?php echo $config_image_thumb_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_image_thumb) { ?>
                                <div class="text-danger"><?php echo $error_image_thumb; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-image-popup-width"><?php echo $entry_image_popup; ?></label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_popup_width" value="<?php echo $config_image_popup_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-popup-width" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_popup_height" value="<?php echo $config_image_popup_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_image_popup) { ?>
                                <div class="text-danger"><?php echo $error_image_popup; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-image-product-width"><?php echo $entry_image_product; ?></label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_product_width" value="<?php echo $config_image_product_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-product-width" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_product_height" value="<?php echo $config_image_product_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_image_product) { ?>
                                <div class="text-danger"><?php echo $error_image_product; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-image-additional-width"><?php echo $entry_image_additional; ?></label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_additional_width" value="<?php echo $config_image_additional_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-additional-width" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_additional_height" value="<?php echo $config_image_additional_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_image_additional) { ?>
                                <div class="text-danger"><?php echo $error_image_additional; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-image-related-width"><?php echo $entry_image_related; ?></label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_related_width" value="<?php echo $config_image_related_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-related-width" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_related_height" value="<?php echo $config_image_related_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_image_related) { ?>
                                <div class="text-danger"><?php echo $error_image_related; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-image-compare-width"><?php echo $entry_image_compare; ?></label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_compare_width" value="<?php echo $config_image_compare_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-compare-width" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_compare_height" value="<?php echo $config_image_compare_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_image_compare) { ?>
                                <div class="text-danger"><?php echo $error_image_compare; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-image-wishlist-width"><?php echo $entry_image_wishlist; ?></label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_wishlist_width" value="<?php echo $config_image_wishlist_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-wishlist-width" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_wishlist_height" value="<?php echo $config_image_wishlist_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_image_wishlist) { ?>
                                <div class="text-danger"><?php echo $error_image_wishlist; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-image-cart-width"><?php echo $entry_image_cart; ?></label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_cart_width" value="<?php echo $config_image_cart_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-cart-width" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_cart_height" value="<?php echo $config_image_cart_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_image_cart) { ?>
                                <div class="text-danger"><?php echo $error_image_cart; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required" style="display: none;">
                            <label class="col-sm-2 control-label" for="input-image-location"><?php echo $entry_image_location; ?></label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_location_width" value="<?php echo $config_image_location_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-location" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="config_image_location_height" value="<?php echo $config_image_location_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_image_location) { ?>
                                <div class="text-danger"><?php echo $error_image_location; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="box-form-info" id="tab-server" style="display:none">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_secure; ?>"><?php echo $entry_secure; ?></span></label>
                            <div class="col-sm-10">
                                <label class="radio-inline">
                                    <?php if ($config_secure) { ?>
                                    <input type="radio" name="config_secure" value="1" checked="checked" />
                                    <?php echo $text_yes; ?>
                                    <?php } else { ?>
                                    <input type="radio" name="config_secure" value="1" />
                                    <?php echo $text_yes; ?>
                                    <?php } ?>
                                </label>
                                <label class="radio-inline">
                                    <?php if (!$config_secure) { ?>
                                    <input type="radio" name="config_secure" value="0" checked="checked" />
                                    <?php echo $text_no; ?>
                                    <?php } else { ?>
                                    <input type="radio" name="config_secure" value="0" />
                                    <?php echo $text_no; ?>
                                    <?php } ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="box-form-info" id="tab-cert">
                        <p class="title">
                            <span>认证信息</span><span class="red">*</span>
                        </p>
                        <?php if ($cert_approve == 9) { ?>
                        <div class="alert alert-success"><i class="fa fa-check-circle"></i>
                            认证信息已审核通过！
                        </div>
                        <?php } ?>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-cert-type">认证类型</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <?php if ($config_cert_type) { ?>
                                            <input type="radio" name="config_cert_type" value="1" checked="checked" style="margin-top:-1px"/> 公司
                                            <?php } else { ?>
                                            <input type="radio" name="config_cert_type" value="1" style="margin-top:-1px"/> 公司
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if ($config_cert_type) { ?>
                                            <input type="radio" name="config_cert_type" value="0" style="margin-top:-1px"/> 个体
                                            <?php } else { ?>
                                            <input type="radio" name="config_cert_type" value="0" checked="checked" style="margin-top:-1px"/> 个体
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"><strong>店铺法人信息</strong></label>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-legal-name">姓名</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_legal_name" value="<?php echo $config_legal_name; ?>" placeholder="必须与身份证上姓名一致" id="input-legal-name" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_legal_name) { ?>
                                <div class="text-danger"><?php echo $error_legal_name; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-legal-phone">手机</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_legal_phone" value="<?php echo $config_legal_phone; ?>" placeholder="手机" id="input-legal-phone" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_legal_phone) { ?>
                                <div class="text-danger"><?php echo $error_legal_phone; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-legal-address">联系地址</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_legal_address" value="<?php echo $config_legal_address; ?>" placeholder="联系地址" id="input-legal-address" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_legal_address) { ?>
                                <div class="text-danger"><?php echo $error_legal_address; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-legal-email">联系邮箱</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_legal_email" value="<?php echo $config_legal_email; ?>" placeholder="联系邮箱" id="input-legal-email" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_legal_email) { ?>
                                <div class="text-danger"><?php echo $error_legal_email; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-legal-qq">联系QQ</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_legal_qq" value="<?php echo $config_legal_qq; ?>" placeholder="联系QQ" id="input-legal-qq" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_legal_qq) { ?>
                                <div class="text-danger"><?php echo $error_legal_qq; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-legal-business">经营类目</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_legal_business" value="<?php echo $config_legal_business; ?>" placeholder="经营类目" id="input-legal-business" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_legal_business) { ?>
                                <div class="text-danger"><?php echo $error_legal_business; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <strong>法人代表身份证</strong>
                            </label>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-legal-id">身份证号码</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_legal_id" value="<?php echo $config_legal_id; ?>" placeholder="一个身份证号码只能开一家店" id="input-legal-id" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_legal_id) { ?>
                                <div class="text-danger"><?php echo $error_legal_id; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="legal-image-front">手持身份证正面照片</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="javascript:void(0);" id="legal-image-front" data-toggle="init-image" class="img-thumbnail"><img src="<?php echo $legal_image_front; ?>" alt="" title="彩色照片" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="config_legal_front" value="<?php echo $config_legal_front; ?>" id="input-legal-front" />
                                    </div>
                                </div>
                                <?php if ($error_legal_front) { ?>
                                <div class="text-danger"><?php echo $error_legal_front; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="legal-image-back">手持身份证背面照片</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="javascript:void(0);" id="legal-image-back" data-toggle="init-image" class="img-thumbnail"><img src="<?php echo $legal_image_back; ?>" alt="" title="彩色照片" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="config_legal_back" value="<?php echo $config_legal_back; ?>" id="input-legal-back" />
                                    </div>
                                </div>
                                <?php if ($error_legal_back) { ?>
                                <div class="text-danger"><?php echo $error_legal_back; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group company-cert">
                            <label class="col-sm-2 control-label">
                                <strong>企业相关证件</strong>
                            </label>
                        </div>
                        <div class="form-group company-cert required">
                            <label class="col-sm-2 control-label" for="input-company-name">公司名称</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="config_company_name" value="<?php echo $config_company_name; ?>" placeholder="必须与营业执照上的企业名称一致" id="input-company-name" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($error_company_name) { ?>
                                <div class="text-danger"><?php echo $error_company_name; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group company-cert required">
                            <label class="col-sm-2 control-label" for="image-business-license">公司营业执照</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="javascript:void(0);" id="image-business-license" data-toggle="init-image" class="img-thumbnail"><img src="<?php echo $thumb_business_license; ?>" alt="" title="彩色扫描件或照片" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="config_business_license" value="<?php echo $config_business_license; ?>" id="input-business-license" />
                                    </div>
                                </div>
                                <?php if ($error_business_license) { ?>
                                <div class="text-danger"><?php echo $error_business_license; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group company-cert required">
                            <label class="col-sm-2 control-label" for="image-tax-license">税务登记证副本</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="javascript:void(0);" id="image-tax-license" data-toggle="init-image" class="img-thumbnail"><img src="<?php echo $thumb_tax_license; ?>" alt="" title="彩色扫描件或照片" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="config_tax_license" value="<?php echo $config_tax_license; ?>" id="input-tax-license" />
                                    </div>
                                </div>
                                <?php if ($error_tax_license) { ?>
                                <div class="text-danger"><?php echo $error_tax_license; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group company-cert required">
                            <label class="col-sm-2 control-label" for="image-organization-license">组织机构代码证</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="javascript:void(0);" id="image-organization-license" data-toggle="init-image" class="img-thumbnail"><img src="<?php echo $thumb_organization_license; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="config_organization_license" value="<?php echo $config_organization_license; ?>" id="input-organization-license" />
                                    </div>
                                </div>
                                <?php if ($error_organization_license) { ?>
                                <div class="text-danger"><?php echo $error_organization_license; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group company-cert required">
                            <label class="col-sm-2 control-label" for="image-bank-license">银行开户许可证</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="javascript:void(0);" id="image-bank-license" data-toggle="init-image" class="img-thumbnail"><img src="<?php echo $thumb_bank_license; ?>" alt="" title="彩色扫描件或照片" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="config_bank_license" value="<?php echo $config_bank_license; ?>" id="input-bank-license" />
                                    </div>
                                </div>
                                <?php if ($error_bank_license) { ?>
                                <div class="text-danger"><?php echo $error_bank_license; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if ($cert_approve == 3 || $cert_approve == 0) { ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-10">
                                <label class="radio-inline">
                                    <input type="checkbox" name="config_cert_approve" value="1" style="margin-top:-2px;"/>
                                    我已经阅读并同意<<珠宝街商家服务协议>>
                                </label>
                                <?php if ($error_cert_approve) { ?>
                                <div class="text-danger"><?php echo $error_cert_approve; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
            </form>
        </div>

        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right" style="margin-right: -20px;">
                    <button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;<?php echo $button_save; ?></button>
                    <!--<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;<?php echo $button_cancel; ?></a>-->
                </div>
            </div>
        </div>
        <script type="text/javascript"><!--
            $('select[name=\'config_template\']').on('change', function() {
                $.ajax({
                    url: 'index.php?route=seller/shop/template&template=' + encodeURIComponent(this.value),
                    dataType: 'html',
                    beforeSend: function() {
                        $('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
                    },
                    complete: function() {
                        $('.fa-spin').remove();
                    },
                    success: function(html) {
                        $('.fa-spin').remove();

                        $('#template').attr('src', html);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            });

            $('select[name=\'config_template\']').trigger('change');

            $('input[name="config_cert_type"]').on('change', function(){
                if ($('input[name="config_cert_type"]:checked').val() == '1') {
                    $('.form-group.company-cert').slideDown();
                } else {
                    $('.form-group.company-cert').slideUp();
                }
            });
            $('input[name="config_cert_type"]').trigger('change');
            //--></script>
        <script type="text/javascript"><!--
            $('select[name=\'config_country_id\']').on('change', function() {
                $.ajax({
                    url: 'index.php?route=seller/shop/country&country_id=' + this.value,
                    dataType: 'json',
                    beforeSend: function() {
                        $('select[name=\'config_country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
                    },
                    complete: function() {
                        $('.fa-spin').remove();
                    },
                    success: function(json) {
                        $('.fa-spin').remove();

                        var html = '<option value=""><?php echo $text_select; ?></option>';

                        if (json['zone'] && json['zone'] != '') {
                            for (i = 0; i < json['zone'].length; i++) {
                                html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                                if (json['zone'][i]['zone_id'] == '<?php echo $config_zone_id; ?>') {
                                    html += ' selected="selected"';
                                }

                                html += '>' + json['zone'][i]['name'] + '</option>';
                            }
                        } else {
                            html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                        }

                        $('select[name=\'config_zone_id\']').html(html);
                        setTimeout(function(){
                            $('select[name=\'config_zone_id\']').trigger('change');
                        }, 300);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            });

            $('select[name=\'config_zone_id\']').on('change', function() {
                $.ajax({
                    url: 'index.php?route=seller/shop/zone&zone_id=' + this.value,
                    dataType: 'json',
                    beforeSend: function() {
                        $('select[name=\'config_zone_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
                    },
                    complete: function() {
                        $('.fa-spin').remove();
                    },
                    success: function(json) {
                        var html = '<option value=""><?php echo $text_select; ?></option>';

                        if (json['city'] && json['city'] != '') {
                            for (var i = 0; i < json['city'].length; i++) {
                                html += '<option value="' + json['city'][i]['id'] + '"';

                                if (json['city'][i]['id'] == '<?php echo $config_city_id; ?>') {
                                    html += ' selected="selected"';
                                }

                                html += '>' + json['city'][i]['name'] + '</option>';
                            }
                        } else {
                            html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                        }

                        $('select[name=\'config_city_id\']').html(html);
                        setTimeout(function(){
                            $('select[name=\'config_city_id\']').trigger('change');
                        }, 300);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            });

            $('select[name=\'config_city_id\']').on('change', function() {
                $.ajax({
                    url: 'index.php?route=seller/shop/city&city_id=' + this.value,
                    dataType: 'json',
                    beforeSend: function() {
                        $('select[name=\'config_city_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
                    },
                    complete: function() {
                        $('.fa-spin').remove();
                    },
                    success: function(json) {
                        var html = '<option value=""><?php echo $text_select; ?></option>';

                        if (json['area'] && json['area'] != '') {
                            for (var i = 0; i < json['area'].length; i++) {
                                html += '<option value="' + json['area'][i]['id'] + '"';

                                if (json['area'][i]['id'] == '<?php echo $config_area_id; ?>') {
                                    html += ' selected="selected"';
                                }

                                html += '>' + json['area'][i]['name'] + '</option>';
                            }
                        } else {
                            html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                        }

                        $('select[name=\'config_area_id\']').html(html);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            });

            $('select[name=\'config_country_id\']').trigger('change');
            //--></script>
        <script type="text/javascript"><!--
            $(document).delegate('a[data-toggle=\'init-image\']', 'click', function(e) {
                e.preventDefault();
                var node = this;

                $('#form-upload').remove();

                $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

                $('#form-upload input[name=\'file\']').trigger('click');

                if (typeof timer != 'undefined') {
                    clearInterval(timer);
                }

                timer = setInterval(function() {
                    if ($('#form-upload input[name=\'file\']').val() != '') {
                        clearInterval(timer);

                        $.ajax({
                            url: 'index.php?route=tool/upload/image',
                            type: 'post',
                            dataType: 'json',
                            data: new FormData($('#form-upload')[0]),
                            cache: false,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                $(node).parent().find('.text-danger').remove();
                                $(node).parent().find('input').after('<div class="text-danger">Loading</div>');
                            },
                            complete: function() {
                                $(node).parent().find('.text-danger').remove();
                            },
                            success: function(json) {
                                $(node).parent().find('.text-danger').remove();

                                if (json['error']) {
                                    $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                                }

                                if (json['success']) {
                                    //alert(json['success']);

                                    $(node).parent().find('input').attr('value', json['code']);
                                    $(node).parent().find('img').attr('src', json['src']);
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    }
                }, 500);
            });
            //--></script>

        <?php } ?>
    </div>
<?php echo $footer; ?>