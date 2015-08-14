<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-10'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>" style="width: 80%; padding-left: 20px;"><?php echo $content_top; ?>
      <h1 style="margin-top: 0px;"><?php echo $heading_title; ?></h1>
      <table class="list table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" colspan="2"><?php echo $text_return_detail; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left" style="width: 50%;"><b><?php echo $text_return_id; ?></b> #<?php echo $return_id; ?><br />
              <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
            <td class="text-left" style="width: 50%;"><b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
              <b><?php echo $text_date_ordered; ?></b> <?php echo $date_ordered; ?></td>
          </tr>
        </tbody>
      </table>
      <h2><?php echo $text_product; ?></h2>
      <table class="list table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_product; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_model; ?></td>
            <td class="text-right" style="width: 33.3%;"><?php echo $column_quantity; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $product; ?></td>
            <td class="text-left"><?php echo $model; ?></td>
            <td class="text-right"><?php echo $quantity; ?></td>
          </tr>
        </tbody>
      </table>
      <table class="list table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_reason; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_opened; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $reason; ?></td>
            <td class="text-left"><?php echo $opened; ?></td>
            <td class="text-left"><?php echo $action; ?></td>
          </tr>
        </tbody>
      </table>
      <?php if ($comment) { ?>
      <table class="list table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $text_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $comment; ?></td>
          </tr>
        </tbody>
      </table>
      <?php } ?>
      <?php if ($histories) { ?>
      <h2><?php echo $text_history; ?></h2>
      <table class="list table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_date_added; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_status; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($histories as $history) { ?>
          <tr>
            <td class="text-left"><?php echo $history['date_added']; ?></td>
            <td class="text-left"><?php echo $history['status']; ?></td>
            <td class="text-left"><?php echo $history['comment']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } ?>

        <div class="tab-pane" id="tab-history">
            <div id="history"></div>
            <br />
            <form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data" id="form-return" class="form-horizontal">
            <fieldset>
                <legend>退换操作</legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-return-status">状态</label>
                    <div class="col-sm-10">
                        <select name="return_status_id" id="input-return-status" class="form-control">
                            <?php foreach ($return_statuses as $return_status) { ?>
                            <?php if ($return_status['return_status_id'] == $return_status_id) { ?>
                            <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-history-comment">备注</label>
                    <div class="col-sm-10">
                        <textarea name="comment" rows="8" id="input-history-comment" class="form-control"></textarea>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> 确认操作</button>
                </div>
            </fieldset>
            </form>
        </div>

      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>