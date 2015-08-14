<!-- <div id="search" class="input-group">
  <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_search; ?>" class="form-control input-lg input_br" />
  <span class="input-group-btn">
    <button type="button" class="btn btn-default btn-lg btn_ys btn_sk"><i style=" color:#fff;" class="fa fa-search"></i></button>
  </span>
</div> -->

<form action="" id="searchForm" name="searchForm">
	<div id="search" class="form-control">
		<div class="search-tab">
			<span class="cur" data-type="1">宝贝</span>
			<input type="hidden" value="" name="search-type">
			<div class="search-list">
				<ul>
					<li data-type="">宝贝</li>
					<li data-type="shop">商家</li>
				</ul>
			</div>
			<span class="caret-icon"><i class="fa fa-caret-up"></i></span>
		</div>
		<div class="search-input">
			<input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_search; ?>">
		</div>
		<div class="search-submit">
			<a href="javascript:void(0)"><i class="fa fa-search"></i></a>
		</div>
	</div>
</form>
<script>
	$(function(){
		$('#search .search-tab').hover(function(){
			$(this).find('.search-list').show();
			$(this).find('.caret-icon .fa').addClass('fa-caret-down').removeClass('fa-caret-up');
		},function(){
			$(this).find('.search-list').hide();
			$(this).find('.caret-icon .fa').addClass('fa-caret-up').removeClass('fa-caret-down');
		});
		$('#search .search-list ul li').click(function(){

			var actText=$(this).html();
			$('#search .search-list').hide();
			$(this).parent().find('li:first').before($(this));
			var searchType = $(this).attr('data-type');
			$('#search .cur').html(actText).attr('data-type',searchType);
			$('#search input[name="search-type"]').val(searchType);

		});
		$('#search .search-submit a').click(function(){
			//$('#searchForm').submit();
            var url = $('base').attr('href') + 'index.php?route=product/search';

            var type = $('header input[name=\'search-type\']').val();
            if (type) {
                url += '&type=' + encodeURIComponent(type);
            }

            var value = $('header input[name=\'search\']').val();

            if (value) {
                url += '&search=' + encodeURIComponent(value);
            }

            location = url;
		});
		$('#search .search-submit a').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$(this).trigger('click');
		}
	});
	});
</script>