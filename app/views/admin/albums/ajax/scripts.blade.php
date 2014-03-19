<script>
$(document).ready(function() {
	
	function getImages(album) {
		$('#albumsImages ul.images').html('');
		$('#albumsImages .loading').spin('small');
		$.ajax({
		    url: '{{URL::to("admin/albums/'+album+'")}}',
		    type: 'GET',
		    dataType: 'json',
		    success: function(result) {
		    	$.each(result.images, function(i, data){
		    		@if($editor)
					$('#albumsImages ul.images').append('<li id="'+data.id+'"><img src="{{asset(Config::get("image.path")."thumbs/'+data.url+'")}}" class="th small-12 image" data-url="'+data.url+'"></a><div class="options"><input name="select[]" type="checkbox" value="'+data.id+'">  <a class="dark" href="#" data-dropdown="drop-'+data.id+'"><i class="fi-pencil"></i></a>  <a class="dark delete" data-id="'+data.id+'"><i class="fi-trash"></i></a></div><input name="images[]" type="hidden"  value="'+data.id+'"></li><div id="drop-'+data.id+'" data-dropdown-content class="f-dropdown content small" ><div class="row collapse"><div class="small-8 columns"><input style="margin:0;" type="text" data-id="'+data.id+'" value="'+data.caption+'" class="image_info" id="img-caption-'+data.id+'" placeholder="Caption"></div><div class="small-4 columns"><input style="margin:0;" type="text" data-id="'+data.id+'" value="'+data.order+'" class="image_info" id="img-order-'+data.id+'" placeholder="Поз."></div></div></div>');
		    		@else
					$('#albumsImages ul.images').append('<li id="'+data.id+'"><img src="{{asset(Config::get("image.path")."thumbs/'+data.url+'")}}" class="th small-12 image" data-url="'+data.url+'"></a></li>');
    				@endif
    			});
				$(document).foundation();
				$('#albumsImages .loading').spin(false);
				$('.image').on('dblclick', function() {
				    putImage($(this).data('url'));
				});
				$('.delete').click(function() {
					confirmDeleteImage($(this).data('id'));
				});
				$( ".image_info" ).change(function() {
					updateImage($( this ).data('id'));
				});
				$(function () {
				    $('#fileupload').fileupload({
				        dataType: 'json',
				        type: 'POST',
				        url: '{{URL::to("admin/images?album='+album+'")}}',
				        done: function (e, data) {
							@if($editor)
							$('#albumsImages ul.images').append('<li id="'+data.result.id+'"><img src="{{asset(Config::get("image.path")."thumbs/'+data.result.url+'")}}" class="th small-12 image" data-url="'+data.result.url+'"></a><div class="options"><input name="select[]" type="checkbox" value="'+data.result.id+'"> <a class="dark" href="#" data-dropdown="drop-'+data.result.id+'"><i class="fi-pencil"></i></a> <a class="dark delete" data-id="'+data.result.id+'"><i class="fi-trash"></i></a></div><input name="images[]" type="hidden"  value="'+data.result.id+'"></li><div id="drop-'+data.result.id+'" data-dropdown-content class="f-dropdown content small" ><div class="row collapse"><div class="small-8 columns"><input style="margin:0;" type="text" data-id="'+data.result.id+'" value="" class="image_info" id="img-caption-'+data.result.id+'" placeholder="Caption"></div><div class="small-4 columns"><input style="margin:0;" type="text" data-id="'+data.result.id+'" value="999" class="image_info" id="img-order-'+data.result.id+'" placeholder="Поз."></div></div></div>');
				        	@else
							$('#albumsImages ul.images').append('<li id="'+data.result.id+'"><img src="{{asset(Config::get("image.path")."thumbs/'+data.result.url+'")}}" class="th small-12 image" data-url="'+data.result.url+'"></a></li>');
				        	@endif
				        	$(document).foundation();
				        	$('.image').on('dblclick', function() {
								putImage($(this).data('url'));
							});
							$('.delete').on('click', function() {
								confirmDeleteImage($(this).data('id'));
							});
							$( ".image_info" ).on('change', function() {
								updateImage($( this ).data('id'));
							});
							$('#albumsImages .loading').spin(false);
				        },
				        progressall: function (e, data) {
				            $('#albumsImages .loading').spin('small');
				    	}
				    });
				});
				$('#albums ul.side-nav li').removeClass('active');
				$('#albums ul.side-nav li#'+album).addClass('active');
				$('#album_id').val(album);
		    }
	  	});
	}
	function getAlbums () {
		$('#albums ul.side-nav').html('');
		$('#albums .loading').spin('small');
		$.ajax({
		    url: '{{URL::to("admin/albums")}}',
		    type: 'GET',
		    dataType: 'json',
		    success: function(result) {
		    	$.each(result, function(i, data){
		    		if(data.system == false)
						$('#albums ul.side-nav').append('<li id="'+data.id+'" style="position:relative;"> <a data-id="'+data.id+'" class="album">'+data.title+'</a> <div style="position:absolute; right:0; bottom:0;"><a class="dark delete_album right" style="margin-left:0.7em;" data-id="'+data.id+'"> <i class="fi-trash"></i></a> <a class="dark right" href="#" data-dropdown="album-drop-'+data.id+'"><i class="fi-pencil"></i></a> </div></li> <div id="album-drop-'+data.id+'" data-dropdown-content class="f-dropdown content small" ><div class="small-12"><input style="margin:0;" type="text" data-id="'+data.id+'" value="'+data.title+'" class="album_info" id="album-title-'+data.id+'" placeholder="Заглавие"></div></div>');
					else
						$('#albums ul.side-nav').append('<li id="'+data.id+'" style="position:relative;"> <a data-id="'+data.id+'" class="album">'+data.title+'</a> </li>');
    			});
				$(document).foundation();
				$('.album').click(function(){
					getImages($(this).data('id'));
				});
				$( ".album_info" ).change(function() {
					updateAlbum($( this ).data('id'));
				});
				$('.delete_album').click(function() {
					deleteAlbum($(this).data('id'));
				});
				$('#albums .loading').spin(false);
		    }
	  	});
	}

	$('#refresh').on('click', function(){
		refresh();
	});
	$('#add_album').on('click', function(){
		addAlbum();
	});
	$('#upload').on('click', function(){
		$('#fileupload').click();
	    return false;
	});
	$('#delete_all').on('click', function(){
		deleteImages();
	});

	function refresh() {
		getAlbums();
		getImages($('#album_id').val());
	}
	refresh();

	function putImage(url) {
		$('img#{{Request::get("field")}}').attr('src', '{{asset(Config::get("image.path")."covers/'+url+'")}}');
		$('input#{{Request::get("field")}}').val(url);
		$.fancybox.close();
	}
	function updateImage(id) {
		$.ajax({
		    url: '{{URL::to("admin/images/'+id+'")}}',
		    data: { caption: $('#img-caption-' +id).val(), order: $('#img-order-' +id).val()},
		    type: 'PUT',
		    dataType: 'json',
		    success: function(result) {
		    	if(!result.success)
			    	alert(result.message);
		    }
	  });
	}
	function confirmDeleteImage(id) {
	    if(confirm('Сигурни ли сте, че желаете да изтриете тази снимка?'))
	        deleteImage(id);
	}
	function deleteImage(id) {
	    $('#albumsImages .loading').spin('small');
	    $.ajax({
	        url: '{{URL::to("admin/images/'+id+'")}}',
	        type: 'DELETE',
	        dataType: 'json',
	        success: function(result) {
	            if(result.success)
	                $('#albumsImages #' + id).remove();
	            else 
			    	alert(result.message);
	            $('#albumsImages .loading').spin(false);
	        }
	    });
	}
	function deleteImages() {
	    var len = $("input[name='select[]']:checked").length;
	    if(len > 0) {
	        if(confirm('Сигурни ли сте, че желаете да изтриете тeзи '+len+' снимки?')) {
	            $("input[name='select[]']").each(function () {
	                if (this.checked) {
	                    deleteImage($(this).val());
	                }
	            });
				$('.checkall').prop('checked', false);
	        }
	    }
	}

	function updateAlbum(id) {
		$.ajax({
		    url: '{{URL::to("admin/albums/'+id+'")}}',
		    data: { title: $('#album-title-' +id).val()},
		    type: 'PUT',
		    dataType: 'json',
		    success: function(result) {
		    	if(result.success) {
			    	getAlbums();
					getImages(result.id);
				}
				else
			    	alert(result.message);

		    }
	  });
	}
	function addAlbum(){
		$.ajax({
		    url: '{{URL::to("admin/albums")}}',
		    type: 'POST',
		    dataType: 'json',
		    success: function(result) {
		    	if(result.success) {
		    		getAlbums();
					getImages(result.id);
		    	}
		    	else
			    	alert(result.message);
		    }
		});
	}
	function deleteAlbum(id) {
		if(confirm('Сигурни ли сте, че желаете да изтриете този албум?'))
			$.ajax({
			    url: '{{URL::to("admin/albums/'+id+'")}}',
			    type: 'DELETE',
			    dataType: 'json',
			    success: function(result) {
			    	if(result.success) {
			    		$('#albums #' + id).remove();
			    		getImages(1);
			    	}
			    	else 
			    		alert(result.message);
			    }
			});
	}
	$(function () {
	    $('.checkall').on('click', function () {
	        $("input[name='select[]']").prop('checked', this.checked);
	    });
	});
})
</script>