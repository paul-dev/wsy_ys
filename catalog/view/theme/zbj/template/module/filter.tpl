<div class="panel panel-default">
  <div class="panel-heading">
      <?php echo $heading_title; ?>
      <span style="float: right; cursor: pointer;" id="filter-label">展开</span>
  </div>
  <div class="list-group">
    <?php
    $_i = 0;
    foreach ($filter_groups as $filter_group) {
    $_i++;
    ?>
    <div class="list-group-item"<?php if ($_i > 3) echo ' style="display:none;"'; ?>>
        <!--<a class="list-group-item"><?php echo $filter_group['name']; ?></a>-->
      <div id="filter-group<?php echo $filter_group['filter_group_id']; ?>">
          <label><strong><?php echo $filter_group['name']; ?>: </strong></label>
          <?php foreach ($filter_group['filter'] as $filter) { ?>
        <!--<div class="checkbox">-->
          <label>
            <?php if (in_array($filter['filter_id'], $filter_category)) { ?>
              &nbsp;<input type="checkbox" name="filter[]" value="<?php echo $filter['filter_id']; ?>" checked="checked" />
            <?php echo $filter['name']; ?>&nbsp;&nbsp;
            <?php } else { ?>
              &nbsp;<input type="checkbox" name="filter[]" value="<?php echo $filter['filter_id']; ?>" />
            <?php echo $filter['name']; ?>&nbsp;&nbsp;
            <?php } ?>
          </label>
        <!--</div>-->
        <?php } ?>
      </div>
    </div>
    <?php } ?>
  </div>
  <div class="panel-footer text-right">
      <button type="button" id="button-filter" class="btn btn-primary"><?php echo $button_filter; ?></button>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	filter = [];
	
	$('input[name^=\'filter\']:checked').each(function(element) {
		filter.push(this.value);
	});
	
	location = '<?php echo $action; ?>&filter=' + filter.join(',');
});
    $('#filter-label').on('click', function(){
        $(this).closest('.panel').find('.list-group .list-group-item').each(function(i, e){
            if (i > 2) {
                $(e).slideToggle('fast');
            }
        });
        if ($(this).text() == '展开') {
            $(this).text('收起');
        } else {
            $(this).text('展开');
        }
    });
//--></script> 
