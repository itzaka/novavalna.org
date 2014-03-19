<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="{{URL::asset('packages/ckeditor/ckeditor.js')}}"></script>
<script src="{{URL::asset('packages/ajax-file-upload/vendor/jquery.ui.widget.js')}}"></script>
<script src="{{URL::asset('packages/ajax-file-upload/jquery.iframe-transport.js')}}"></script>
<script src="{{URL::asset('packages/ajax-file-upload/jquery.fileupload.js')}}"></script>
<script src="{{asset('packages/vendor/spin.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('packages/datatables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript"> $(document).ready(function() {
        var oTable = $('#table').dataTable({
            "aaSorting": [[ 0, "desc" ]],
            "aoColumnDefs": [
                        { "bVisible": false, "aTargets": [ 0 ] },
                        { "sClass" : "hide-for-small", "aTargets": [ 2 ]},
                        { "sClass" : "hide-for-small center", "aTargets": [ 3 ]},
                        { "sClass" : "hide-for-small center", "aTargets": [ 4 ]},
                        { "sClass" : "hide-for-small center", "aTargets": [ 5 ]},
                        { "sClass" : "hide-for-small date center", "aTargets": [ 6 ]},
                        @if($editor){ "sClass" : "center", "aTargets": [ 7 ]} @endif
                    ],
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                $(nRow).attr('id', aData[0]);
                if (aData[5] == "Не")
                    $(nRow).addClass('unpublished');
                $(nRow[5]).attr('id', aData[0]);
                return nRow;
            },
            "oLanguage": {
                    "sSearch": "Търси:",
                    "sInfo": "Показва: _START_ до _END_ (от _TOTAL_)",
                    "oPaginate": {
                    "sNext": "›",
                    "sLast": "&raquo;",
                    "sPrevious": "‹",
                    "sFirst": "&laquo;",
                        }
                },
            "sPaginationType": "full_numbers",
        });
        
        $(".user").on( 'click', function () {
            /* Filter on the column (the index) of this element */
            oTable.fnFilter( $(this).data('value'), 2 );
            $('#filter #user').html('<strong>Автор:</strong> ' + $(this).data('value') + '<a class="remove-user dark"> <i class="fi-x"></i></a> ');
            $(".remove-user").on( 'click', function () {
                oTable.fnFilter( '', 2 );
                $('#filter #user').html('');
            });
        });
    } );
    $.fn.dataTableExt.oStdClasses.sLength = "small-4 left tiny";
    $.fn.dataTableExt.oStdClasses.sFilter = "left small-8 right tiny";
    $.fn.dataTableExt.oStdClasses.sInfo = "small-4 left";
    $.fn.dataTableExt.oStdClasses.sPaging = "small-8 right table-paginate";

</script>
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
            $('#table').dataTable().fnAddData( [
            data.result.id,
            '<a class="fancybox" rel="portfolio" href="{{asset("'+data.result.path+data.result.image+'")}}"><img width="50" src="{{asset("'+data.result.path+'thumbs/'+data.result.image+'")}}"></a>',
            '<a class="user" data-value="'+data.result.user+'">'+data.result.user+'</a>',
            '<input style="margin:0;" type="text" data-id="'+data.result.id+'" value="" class="image" id="img-caption-'+data.result.id+'" placeholder="Caption">',
            '<input style="margin:0;" type="text" data-id="'+data.result.id+'" value="" class="image" id="img-order-'+data.result.id+'" placeholder="Поз."><input name="images[]" type="hidden"  value="'+data.result.id+'">',
            'Не',
            '<?php $date = new Date();?>{{$date->format('Y M dS')}}',
            @if($editor)'<input name="select[]" type="checkbox" value="'+data.result.id+'">' @endif] );

            
            $(".user").on( 'click', function () {
                /* Filter on the column (the index) of this element */
                $('#table').dataTable().fnFilter( $(this).data('value'), 2 );
                $('#filter #user').html('<strong>Автор:</strong> ' + $(this).data('value') + '<a class="remove-user dark"> <i class="fi-x"></i></a> ');
                $(".remove-user").on( 'click', function () {
                    $('#table').dataTable().fnFilter( '', 2 );
                    $('#filter #user').html('');
                });
            });

            $('#loading').spin(false);
			$( ".image" ).change(function() {
			    updateImage($( this ).data('id'));
			});
            $('.image').keypress(function(event) { return event.keyCode != 13; });
        },
        progressall: function (e, data) {
            $('#loading').spin('large');
    	}
    });
});


// DELETE
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
            if(result.success){
                var oTable = $('#table').dataTable();
                oTable.fnDeleteRow( document.getElementById( id ) );
            }
               
            else
                alert(result.message);
            $('#loading').spin(false);

        }
    });
}
function deleteImages() {
    var len = $("input[name='select[]']:checked").length;
    if(len > 0) {
        if(confirm('Сигурни ли сте, че желаете да изтриете избраните ('+len+') снимки?'))
            $("input[name='select[]']").each(function () {
                if (this.checked) {
                    deleteImage($(this).val());
                }
            });
    }
}

// UPDATE
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
// CHANGE ALBUM
$( "#changeAlbum" ).on('click', function() {
    changeAlbumImages();
});

function changeAlbumImage(id) {
  $.ajax({
    url: '{{URL::to("admin/images/'+id+'")}}',
    data: { album: $('#selectedAlbum').val()},
    type: 'PUT',
    dataType: 'json',
    success: function(result) {
        if(result.success) {
            var oTable = $('#table').dataTable();
            oTable.fnDeleteRow( document.getElementById( id ) );
        }
        else
            alert(result.message);
    }
  });
}
function changeAlbumImages() {
    var len = $("input[name='select[]']:checked").length;
    if(len > 0) {
        if(confirm('Сигурни ли сте, че желаете да преместите избраните ('+len+') снимки?'))
            $("input[name='select[]']").each(function () {
                if (this.checked) {
                    changeAlbumImage($(this).val());
                }
            });
    }
}
// PUBLISH
function publishImage(id) {
  $.ajax({
    url: '{{URL::to("admin/images/'+id+'")}}',
    data: { public: 1},
    type: 'PUT',
    dataType: 'json',
    success: function(result) {
        if(result.success) {
            $('tr#'+id).removeClass('unpublished');
            $('tr#'+id+' td:nth-child(5)').html('Да');
        }
            
        else
            alert(result.message);
    }
  });
}
function publishImages() {
    var len = $("input[name='select[]']:checked").length;
    if(len > 0) {
        if(confirm('Сигурни ли сте, че желаете да публикувате избраните ('+len+') снимки?'))
            $("input[name='select[]']").each(function () {
                if (this.checked) {
                    publishImage($(this).val());
                    $(this).prop('checked', false);
                    $('.checkall').prop('checked', false);
                }
            });
    }
}
// UNPUBLISH
function unPublishImage(id) {
  $.ajax({
    url: '{{URL::to("admin/images/'+id+'")}}',
    data: { public: 0},
    type: 'PUT',
    dataType: 'json',
    success: function(result) {
        if(result.success) {
            $('tr#'+id).addClass('unpublished');
            $('tr#'+id+' td:nth-child(5)').html('Не');
        }
           

        else
            alert(result.message);
    }
  });
}
function unPublishImages() {
    var len = $("input[name='select[]']:checked").length;
    if(len > 0) {
        if(confirm('Сигурни ли сте, че желаете да отпубликувате избраните ('+len+') снимки?'))
            $("input[name='select[]']").each(function () {
                if (this.checked) {
                    unPublishImage($(this).val());
                    $(this).prop('checked', false);
                    $('.checkall').prop('checked', false);
                }
            });
    }
}
// OTHER
$(function () {
    $('.checkall').on('click', function () {
        $("input[name='select[]']").prop('checked', this.checked);
    });
});
$('.image').keypress(function(event) { return event.keyCode != 13; });
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