<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
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
            <div class="container-fluid">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php } ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-category">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-right"><?php echo $column_return_id; ?></td>
                                        <td class="text-right"><?php echo $column_order_id; ?></td>
                                        <td class="text-left"><?php echo $column_customer; ?></td>
                                        <td class="text-left">商品</td>
                                        <td class="text-left">型号</td>
                                        <td class="text-left">数量</td>
                                        <td class="text-left"><?php echo $column_status; ?></td>
                                        <td class="text-left"><?php echo $column_date_added; ?></td>
                                        <td></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($returns) { ?>
                                    <?php foreach ($returns as $return) { ?>
                                    <tr>
                                        <td class="text-right">#<?php echo $return['return_id']; ?></td>
                                        <td class="text-right">#<?php echo $return['order_id']; ?></td>
                                        <td class="text-left"><?php echo $return['name']; ?></td>
                                        <td class="text-left"><a href="<?php echo $return['href_product']; ?>" target="_blank"><?php echo $return['product']; ?></a></td>
                                        <td class="text-left"><?php echo $return['model']; ?></td>
                                        <td class="text-left"><?php echo $return['quantity']; ?></td>
                                        <td class="text-left"><?php echo $return['status']; ?></td>
                                        <td class="text-left"><?php echo $return['date_added']; ?></td>
                                        <td><a href="<?php echo $return['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
                                    </tr>
                                    <?php } ?>
                                    <?php } else { ?>
                                    <tr>
                                        <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                            <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>