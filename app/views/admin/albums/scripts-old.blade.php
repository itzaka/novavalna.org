<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="{{URL::asset('packages/ckeditor/ckeditor.js')}}"></script>
<script src="{{URL::asset('packages/ajax-file-upload/vendor/jquery.ui.widget.js')}}"></script>
<script src="{{URL::asset('packages/ajax-file-upload/jquery.iframe-transport.js')}}"></script>
<script src="{{URL::asset('packages/ajax-file-upload/jquery.fileupload.js')}}"></script>
<script src="{{asset('packages/vendor/spin.min.js')}}"></script>

<script type="text/javascript">
$('#image').click(function(){
	$('#imageupload').click();
    return false;
});
$(function () {
    $('#imageupload').fileupload({
        dataType: 'json',
        type: 'POST',
       done: function (e, data) {
			$('input#image').val(data.result.image);
			$('img#image').attr('src', data.result.image);
			$("#loading").hide();
        },
        progressall: function (e, data) {
			$("#loading").show();
    	}
    });
});

$('.fi-x-circle.remove').click(function(){
	$('img#image').attr('src', '{{asset("images/photo.jpg")}}');
	$('input#image').val('');
});
</script>
<script>
// Images

$('#images').click(function(){
	$('#fileupload').click();
    return false;
});
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        type: 'POST',
        done: function (e, data) {
            @if($editor)
        	$('ul.images').append('<li id="'+data.result.id+'"><a class="fancybox" rel="portfolio" href="{{asset("'+data.result.path+data.result.image+'")}}"><img src="{{asset("'+data.result.path+'thumbs/'+data.result.image+'")}}" class="th small-12 unpublished"></a><div class="options"><input name="select[]" type="checkbox" value="'+data.result.id+'"><a class="dark" href="#" data-dropdown="drop-'+data.result.id+'"><i class="fi-pencil"></i></a><a onclick="confirmDeleteImage('+data.result.id+')" href="javascript:void(0)" class="dark"><i class="fi-trash"></i></a></div><div id="drop-'+data.result.id+'" data-dropdown-content class="f-dropdown content small" ><div class="row collapse"><div class="small-8 columns"><input style="margin:0;" type="text" data-id="'+data.result.id+'" value="" class="image" id="img-caption-'+data.result.id+'" placeholder="Caption"></div><div class="small-4 columns"><input style="margin:0;" type="text" data-id="'+data.result.id+'" value="" class="image" id="img-order-'+data.result.id+'" placeholder="Поз."></div></div></div><input name="images[]" type="hidden"  value="'+data.result.id+'"></li>');
			@else
            $('ul.images').append('<li id="'+data.result.id+'"><a class="fancybox" rel="portfolio" href="{{asset("'+data.result.path+data.result.image+'")}}"><img src="{{asset("'+data.result.path+'thumbs/'+data.result.image+'")}}" class="th small-12 unpublished"></a></li>');
            @endif
            $('#loading').spin(false);
			$( ".image" ).change(function() {
			    updateImage($( this ).data('id'));
			});
            $(document).foundation();
        },
        progressall: function (e, data) {
            $('#loading').spin('large');
    	}
    });
});
function confirmDeleteImage(id) {
    if(confirm('Сигурни ли сте, че желаете да изтриете тази снимка?'))
        deleteImage(id);
}
function deleteImage(id) {
    $('#loading').spin('large');
    $.ajax({
        url: '{{URL::to("admin/images/'+id+'")}}',
        type: 'DELETE',
        dataType: 'json',
        success: function(result) {
            if(result.success)
                $('#' + id).remove();
            else
                alert(result.message);
            $('#loading').spin(false);
        }
    });
}
function publishImage(id) {
  $.ajax({
    url: '{{URL::to("admin/images/'+id+'")}}',
    data: { public: 1},
    type: 'PUT',
    dataType: 'json',
    success: function(result) {
        if(result.success)
            $('#'+id+' a img').removeClass('unpublished');
        else
            alert(result.message);
    }
  });
}
function unPublishImage(id) {
  $.ajax({
    url: '{{URL::to("admin/images/'+id+'")}}',
    data: { public: 0},
    type: 'PUT',
    dataType: 'json',
    success: function(result) {
        if(result.success)
            $('#'+id+' a img').addClass('unpublished');
        else
            alert(result.message);
    }
  });
}
$( ".image" ).change(function() {
    updateImage($( this ).data('id'));
  });

function updateImage(id) {
  $.ajax({
    url: '{{URL::to("admin/images/'+id+'")}}',
    data: { caption: $('#img-caption-' +id).val(), order: $('#img-order-' +id).val()},
    type: 'PUT',
    dataType: 'json',
    success: function(result) {
        if(result.success == false)
            alert(result.message);
    }
  });
}
function deleteImages() {
    var len = $("input[name='select[]']:checked").length;
    if(len > 0) {
        if(confirm('Сигурни ли сте, че желаете да изтриете тeзи '+len+' снимки?'))
            $("input[name='select[]']").each(function () {
                if (this.checked) {
                    deleteImage($(this).val());
                }
            });
    }
}
function publishImages() {
    var len = $("input[name='select[]']:checked").length;
    if(len > 0) {
        if(confirm('Сигурни ли сте, че желаете да публикувате тeзи '+len+' снимки?'))
            $("input[name='select[]']").each(function () {
                if (this.checked) {
                    publishImage($(this).val());
                    $(this).prop('checked', false);
                    $('.checkall').prop('checked', false);
                }
            });
    }
}
function unPublishImages() {
    var len = $("input[name='select[]']:checked").length;
    if(len > 0) {
        if(confirm('Сигурни ли сте, че желаете да отпубликувате тeзи '+len+' снимки?'))
            $("input[name='select[]']").each(function () {
                if (this.checked) {
                    unPublishImage($(this).val());
                    $(this).prop('checked', false);
                    $('.checkall').prop('checked', false);
                }
            });
    }
}
$(function () {
    $('.checkall').on('click', function () {
        $("input[name='select[]']").prop('checked', this.checked);
    });
});

</script>

<script type="text/javascript" src="{{asset('fancybox/js/jquery.mousewheel-3.0.6.pack.js')}}"></script>
<script type="text/javascript" src="{{asset('fancybox/js/jquery.fancybox.js?v=2.1.5')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('fancybox/css/jquery.fancybox.css?v=2.1.5')}}" media="screen" />
<link rel="stylesheet" type="text/css" href="{{asset('fancybox/css/jquery.fancybox-buttons.css?v=1.0.5')}}" />
<script type="text/javascript" src="{{asset('fancybox/js/jquery.fancybox-buttons.js?v=1.0.5')}}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		/*
		 *  Simple image gallery. Uses default settings
		 */

		$('.fancybox').fancybox();
	});
</script>
