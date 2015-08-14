<script src="catalog/view/theme/zbj/js/plupload/plupload.full.min.js" type="text/javascript"></script>
<div class="">
  <div class="">
    <div class="modal-header">
      <h4 class="modal-title"><?php echo $heading_title; ?></h4>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-sm-5">
            <a href="<?php echo $parent; ?>" data-toggle="tooltip" title="<?php echo $button_parent; ?>" id="button-parent" class="btn btn-default"><i class="fa fa-level-up"></i></a>
            <a href="<?php echo $refresh; ?>" data-toggle="tooltip" title="<?php echo $button_refresh; ?>" id="button-refresh" class="btn btn-default"><i class="fa fa-refresh"></i></a>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_upload; ?>" id="button-upload" class="btn btn-primary"><i class="fa fa-upload"></i></button>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_folder; ?>" id="button-folder" class="btn btn-default"><i class="fa fa-folder"></i></button>
          <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" id="button-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
            <button type="button" data-toggle="tooltip" title="文件夹重命名" id="button-name" class="btn btn-primary"><i class="fa fa-pencil"></i></button>
            <button type="button" data-toggle="tooltip" title="文件夹重命名" id="button-renamefolder" class="btn btn-primary" style="display: none;"><i class="fa fa-pencil"></i></button>
        </div>
        <div class="col-sm-7">
          <div class="input-group">
            <input type="text" name="search" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_search; ?>" class="form-control">
            <span class="input-group-btn">
            <button type="button" data-toggle="tooltip" title="<?php echo $button_search; ?>" id="button-search" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </span></div>
        </div>
      </div>

        <div class="row">
            <div id="filelist" style="margin-top: 10px; display: none;">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
            <div id="upload-container"></div>
            <pre id="upload-console" style="display: none;"></pre>
        </div>

      <hr />
      <?php foreach (array_chunk($images, 4) as $image) { ?>
      <div class="row">
        <?php foreach ($image as $image) { ?>
        <div class="col-sm-3 text-center">
          <?php if ($image['type'] == 'directory') { ?>
          <div class="text-center"><a href="<?php echo $image['href']; ?>" class="directory" style="vertical-align: middle; color: #f69;"><i class="fa fa-folder fa-5x" style="font-size: 5em;"></i></a></div>
          <label>
            <input type="checkbox" name="path[]" value="<?php echo $image['path']; ?>" />
            <?php echo $image['name']; ?></label>
          <?php } ?>
          <?php if ($image['type'] == 'image') { ?>
          <a href="<?php echo $image['href']; ?>" target="_blank" class="thumbnail"><img src="<?php echo $image['thumb']; ?>" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" /></a>
          <label>
            <input type="checkbox" name="path[]" value="<?php echo $image['path']; ?>" />
            <?php echo $image['name']; ?></label>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <br />
      <?php } ?>
    </div>
    <div class="modal-footer"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
/*$('a.thumbnail').on('click', function(e) {
	e.preventDefault();

	<?php if ($thumb) { ?>
	$('#<?php echo $thumb; ?>').find('img').attr('src', $(this).find('img').attr('src'));
	<?php } ?>
	
	<?php if ($target) { ?>
	$('#<?php echo $target; ?>').attr('value', $(this).parent().find('input').attr('value'));
	<?php } else { ?>
	var range, sel = document.getSelection(); 
	
	if (sel.rangeCount) { 
		var img = document.createElement('img');
		img.src = $(this).attr('href');
	
		range = sel.getRangeAt(0); 
		range.insertNode(img); 
	}
	<?php } ?>

	$('#modal-image').modal('hide');
});*/

$('a.directory').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('.pagination a').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('#button-parent').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('#button-refresh').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-image').load($(this).attr('href'));
});

$('#modal-image input[name=\'search\']').on('keydown', function(e) {
	if (e.which == 13) {
		$('#button-search').trigger('click');
	}
});

$('#modal-image #button-search').on('click', function(e) {
	var url = 'index.php?route=seller/filemanager&directory=<?php echo $directory; ?>';
		
	var filter_name = $('#modal-image input[name=\'search\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
							
	<?php if ($thumb) { ?>
	url += '&thumb=' + '<?php echo $thumb; ?>';
	<?php } ?>
	
	<?php if ($target) { ?>
	url += '&target=' + '<?php echo $target; ?>';
	<?php } ?>

    <?php if ($pop) { ?>
        url += '&pop=' + '<?php echo $pop; ?>';
    <?php } ?>
			
	$('#modal-image').load(url);
});
//--></script> 
<script type="text/javascript"><!--
/*$('#button-upload').on('click', function() {
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value="" /></form>');
	
	$('#form-upload input[name=\'file\']').trigger('click');
	
	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}
	
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);
			
			$.ajax({
				url: 'index.php?route=seller/filemanager/upload&directory=<?php echo $directory; ?>',
				type: 'post',		
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,		
				beforeSend: function() {
					$('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
					$('#button-upload').prop('disabled', true);
				},
				complete: function() {
					$('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
					$('#button-upload').prop('disabled', false);
				},
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}
					
					if (json['success']) {
						alert(json['success']);
						
						$('#button-refresh').trigger('click');
					}
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});	
		}
	}, 500);
});*/

$('#button-folder').popover({
	html: true,
	placement: 'bottom',
	trigger: 'click',
	title: '<?php echo $entry_folder; ?>',
	content: function() {
		html  = '<div class="input-group">';
		html += '  <input type="text" name="folder" value="" placeholder="<?php echo $entry_folder; ?>" class="form-control">';
		html += '  <span class="input-group-btn"><button type="button" title="<?php echo $button_folder; ?>" id="button-create" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></span>';
		html += '</div>';
		
		return html;	
	}
});

$('#button-folder').on('shown.bs.popover', function() {
	$('#button-create').on('click', function() {
		$.ajax({
			url: 'index.php?route=seller/filemanager/folder&directory=<?php echo $directory; ?>',
			type: 'post',		
			dataType: 'json',
			data: 'folder=' + encodeURIComponent($('input[name=\'folder\']').val()),
			beforeSend: function() {
				$('#button-create').prop('disabled', true);
			},
			complete: function() {
				$('#button-create').prop('disabled', false);
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					alert(json['success']);
										
					$('#button-refresh').trigger('click');
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});	
});

$('#modal-image #button-delete').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		$.ajax({
			url: 'index.php?route=seller/filemanager/delete',
			type: 'post',		
			dataType: 'json',
			data: $('input[name^=\'path\']:checked'),
			beforeSend: function() {
				$('#button-delete').prop('disabled', true);
			},	
			complete: function() {
				$('#button-delete').prop('disabled', false);
			},		
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					alert(json['success']);
					
					$('#button-refresh').trigger('click');
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});

    $('#button-name').popover({
        html: true,
        placement: 'bottom',
        trigger: 'click',
        title: '<?php echo $entry_folder; ?>',
        content: function() {
            var html  = '<div class="input-group">';
            html += '  <input type="text" name="folder_name" value="" placeholder="<?php echo $entry_folder; ?>" class="form-control">';
            html += '  <span class="input-group-btn"><button type="button" title="<?php echo $button_folder; ?>" id="button-rename" class="btn btn-primary"><i class="fa fa-pencil-square"></i></button></span>';
            html += '</div>';

            return html;
        }
    });

    $('#button-name').on('shown.bs.popover', function() {
        $('#button-rename').on('click', function() {
            if ($('input[name^=\'path\']:checked').length == 1) {
                var _check = $('input[name^=\'path\']:checked').parent().parent().find('a.thumbnail').length;
                if (_check > 0) {
                    alert('只能重命名文件夹！');
                    return false;
                }

                if ($('input[name=\'folder_name\']').val() == '') {
                    alert('请输入新文件夹名称！');
                    return false;
                } else {
                    $.ajax({
                        url: 'index.php?route=seller/filemanager/rename',
                        type: 'post',
                        dataType: 'json',
                        data: 'folder='+$('input[name^=\'path\']:checked').val()+'&name=' + encodeURIComponent($('input[name=\'folder_name\']').val()),
                        beforeSend: function() {
                            $('#button-rename').prop('disabled', true);
                        },
                        complete: function() {
                            $('#button-rename').prop('disabled', false);
                        },
                        success: function(json) {
                            if (json['error']) {
                                alert(json['error']);
                            }

                            if (json['success']) {
                                alert(json['success']);

                                $('#button-refresh').trigger('click');
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            } else if ($('input[name^=\'path\']:checked').length == 0) {
                alert('请先选择一个文件夹！');
                return false;
            } else {
                alert('每次只能重命名一个文件夹！');
                return false;
            }
        });
    });

    $(document).ready(function(){
        $('.tooltip.in').remove();
    });
//--></script>
<script type="text/javascript">
    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'button-upload', // you can pass an id...
        container: document.getElementById('upload-container'), // ... or DOM Element itself
        url : 'index.php?route=seller/filemanager/upload&directory=<?php echo $directory; ?>',
        flash_swf_url : 'catalog/view/theme/zbj/js/plupload/Moxie.swf',
        silverlight_xap_url : 'catalog/view/theme/zbj/js/plupload/Moxie.xap',

        filters : {
            max_file_size : '10mb',
            mime_types: [
                {title : "Image files", extensions : "jpg,jpeg,gif,png"}
            ]
        },

        init: {
            PostInit: function() {
                document.getElementById('filelist').innerHTML = '';
                //$('#upload-console').hide();
                //$('#filelist').show();

                /*document.getElementById('uploadfiles').onclick = function() {
                    uploader.start();
                    return false;
                };*/
            },

            FilesAdded: function(up, files) {
                $('#filelist').show();

                plupload.each(files, function(file) {
                    document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                });

                $('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                $('#button-upload').prop('disabled', true);

                uploader.start();
            },

            UploadProgress: function(up, file) {
                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
            },

            UploadComplete: function(up, files) {
                // Called when all files are either uploaded or failed
                $('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
                $('#button-upload').prop('disabled', false);

                $('#button-refresh').trigger('click');
            },

            Error: function(up, err) {
                document.getElementById('upload-console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                $('#upload-console').show();
            }
        }
    });

    uploader.init();
</script>