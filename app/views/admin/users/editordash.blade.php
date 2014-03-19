@extends('admin.layouts.master')


@section('content')
<section id="page">
		<div class="small-12 columns">
			<h3>Привет, {{$user->first_name}} {{$user->last_name}}</h3>
			@if(Session::get('message'))
			<div data-alert class="alert-box warning radius">
	  			{{Session::get('message')}}
	  			<a href="#" class="close">&times;</a>
			</div>
			@endif
		</div>
	<section>
		<div class="columns">
			<h3>Публикации <small>( {{$posts_total}} )</small></h3>
		</div>
		<div class="large-3 medium-4 small-6 columns">
			<div class="panel @if($about_unpublished) callout @endif">
				<h3>За нас <small>( {{$about_total}} )</small></h3>
				@if($about_unpublished)
					<p class="subheader"><a href="{{url('admin/posts?type=about')}}">Имате {{($about_unpublished == 1) ? $about_unpublished . ' неодобрена публикация' : $about_unpublished . ' неодобрени публикации'}}</a></p>
				@else
					<p class="subheader">Нямате неодобрени публикации</p>
				@endif
			</div>
		</div>
		<div class="large-3 medium-4 small-6 columns">
			<div class="panel @if($news_unpublished) callout @endif">
				<h3>Новини <small>( {{$news_total}} )</small></h3>
				@if($news_unpublished)
					<p class="subheader"><a href="{{url('admin/posts?type=news')}}">Имате {{($news_unpublished == 1) ? $news_unpublished . ' неодобрена публикация' : $news_unpublished . ' неодобрени публикации'}}</a></p>
				@else
					<p class="subheader">Нямате неодобрени публикации</p>
				@endif
			</div>
		</div>
		<div class="large-3 medium-4 small-6 columns">
			<div class="panel @if($events_unpublished) callout @endif">
				<h3>Събития <small>( {{$events_total}} )</small></h3>
				@if($events_unpublished)
					<p class="subheader"><a href="{{url('admin/posts?type=events')}}">Имате {{($events_unpublished == 1) ? $events_unpublished . ' неодобрена публикация' : $events_unpublished . ' неодобрени публикации'}}</a></p>
				@else
					<p class="subheader">Нямате неодобрени публикации</p>
				@endif
			</div>
		</div>
		<div class="large-3 medium-4 small-6 columns">
			<div class="panel @if($activities_unpublished) callout @endif">
				<h3>Дейности <small>( {{$activities_total}} )</small></h3>
				@if($activities_unpublished)
					<p class="subheader"><a href="{{url('admin/posts?type=activities')}}">Имате {{($activities_unpublished == 1) ? $activities_unpublished . ' неодобрена публикация' : $activities_unpublished . ' неодобрени публикации'}}</a></p>
				@else
					<p class="subheader">Нямате неодобрени публикации</p>
				@endif
			</div>
		</div>
		<div class="large-3 medium-4 small-6 columns">
			<div class="panel @if($summer_camp_unpublished) callout @endif">
				<h3>Летен лагер <small>( {{$summer_camp_total}} )</small></h3>
				@if($summer_camp_unpublished)
					<p class="subheader"><a href="{{url('admin/posts?type=activities')}}">Имате {{($summer_camp_unpublished == 1) ? $summer_camp_unpublished . ' неодобрена публикация' : $summer_camp_unpublished . ' неодобрени публикации'}}</a></p>
				@else
					<p class="subheader">Нямате неодобрени публикации</p>
				@endif
			</div>
		</div>
		<div class="large-3 medium-4 small-6 left columns">
			<div class="panel @if($vlog_unpublished) callout @endif">
				<h3>Видео блог <small>( {{$vlog_total}} )</small></h3>
				@if($vlog_unpublished)
					<p class="subheader"><a href="{{url('admin/posts?type=activities')}}">Имате {{($vlog_unpublished == 1) ? $vlog_unpublished . ' неодобрена публикация' : $vlog_unpublished . ' неодобрени публикации'}}</a></p>
				@else
					<p class="subheader">Нямате неодобрени публикации</p>
				@endif
			</div>
		</div>
		<div class="row"></div>
	</section>
	<section>		
		<div class="columns">
			<h3>Категории <small>( {{$categories_total}} )</small></h3>
			@if($categories_unpublished)
				<p class="subheader"><a href="{{URL::to('admin/categories')}}">Имате {{($categories_unpublished == 1) ? $categories_unpublished . ' неодобрена категория' : $categories_unpublished . ' неодобрени категории'}}</a></p>
			@else
				<p class="subheader">Нямате неодобрени категории</p>
			@endif
		</div>
		<div class="row"></div>
	</section>
	<!-- IMAGES -->
	<section>
		<div class="columns">
			<h3>Снимки <small>( {{$images_total}} )</small></h3>
			@if($images_unpublished)
				<p class="subheader">Имате {{($images_unpublished == 1) ? $images_unpublished . ' неодобрена снимка' : $images_unpublished . ' неодобрени снимки'}}</p>
			@else
				<p class="subheader">Нямате неодобрени снимки</p>
			@endif
		</div>
		@if($images_unpublished)
		<div class="large-9 medium-8 large-push-3 medium-push-4 columns">
			<div id="filter">
				<div id="user"></div>
			</div>

			<div class="row">
				<div class="columns">
					<div id="loading"></div>
					<table id="table" class="small-12">
						<thead>
							<tr>
								<th class="hide">ID</th>
								<th width="50">Снимка</th>
								<th class="hide-for-small">Потребител</th>
								<th class="hide-for-small">Албум</th>
								<th class="hide-for-small" width="120">Добавена</th>
								<th width="20">-</th>
							</tr>
						</thead>
						<tbody>

						@foreach($images as $image)
							<tr id="{{$image->id}}" class="unpublished">
								<td class="hide"> {{$image->id}}</td>
								<td>
									<a class="fancybox" rel="portfolio" title="{{$image->caption}}" href="{{asset(Config::get('image.path').$image->url)}}"><img class=" @if($image->public == false) unpublished @endif" width="50" src="{{asset(Config::get('image.path').'/thumbs/'.$image->url)}}"></a>
								</td>
								<td class="hide-for-small"><a class="user" data-value="{{$image->user->first_name}} {{$image->user->last_name}}">{{$image->user->first_name}} {{$image->user->last_name}}</a></td>
								<td class="hide-for-small"><a href="{{URL::to('admin/albums/'.$image->album->id.'/edit')}}">{{$image->album->title}}</a></td>
								<td class="hide-for-small date" align="center"><?php $date = new Date($image->created_at);?>{{$date->format('Y M dS')}}</td>
								<td align="center">
									{{Form::checkbox('select[]', $image->id)}}
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="large-3 medium-4 large-pull-9 medium-pull-8 columns">
			
			<div class="row collapse">
				<div class="small-4 columns">
					<a class="button  small expand" onclick="publishImages()" href="javascript:void(0)"> <i class="fi-check"></i></a> 
				</div>
				<div class="small-4 columns">
					<a class="button alert small expand" onclick="deleteImages()" href="javascript:void(0)"> <i class="fi-trash"></i></a> 
				</div>

				<div class="small-2 columns">
					{{Form::checkbox('checkall', null, false, array('class' => 'checkall'))}}
				</div>
			</div>

		</div>
		@endif
		<div class="row"></div>
	</section>
	<!-- END IMAGES -->

	<section>		
		<div class="medium-6 columns">
			<h3>Банери <small>( {{$banners_total}} )</small></h3>
			@if($banners_unpublished)
				<p class="subheader"><a href="{{URL::to('admin/banners')}}">Имате {{($banners_unpublished == 1) ? $banners_unpublished . ' неодобрен банер' : $banners_unpublished . ' неодобрени банери'}}</a></p>
			@else
				<p class="subheader">Нямате неодобрени банери</p>
			@endif
		</div>
		<div class="medium-6 columns">
			<h3>Анкети <small>( {{$polls_total}} )</small></h3>
			@if($polls_unpublished)
				<p class="subheader"><a href="{{URL::to('admin/polls')}}">Имате {{($polls_unpublished == 1) ? $polls_unpublished . ' неодобрена анкета' : $polls_unpublished . ' неодобрени анкети'}}</a></p>
			@else
				<p class="subheader">Нямате неодобрени анкети</p>
			@endif
		</div>
		<div class="row"></div>
	</section>
	<section>		
		<div class="columns">
			<h3>Потребители <small>( {{$users_total}} )</small></h3>
				<table>
					<tfoot>
						<tr>
							<td colspan="3">Последно регистрирани</td>
						</tr>
					</tfoot>
					<tbody>
						@foreach($users as $user)
						<tr id="{{$user->id}}">
							<td>
								<a class="dark" href="{{URL::to('admin/users/'.$user->id.'/edit')}}">{{$user->first_name}} {{$user->last_name}}</a>
							</td>
							<td>({{$user->username}})</td>
							<td class="date"><?php $date = new Date($user->created_at);?>{{$date->format('Y M dS')}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
		</div>
		<div class="row"></div>
	</section>

</section>
@stop
@section('scripts')
<script src="{{asset('packages/vendor/spin.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('packages/datatables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript"> $(document).ready(function() {
        var oTable = $('#table').dataTable({
            "aaSorting": [[ 0, "desc" ]],
            "aoColumnDefs": [
                        { "bVisible": false, "aTargets": [ 0 ] },
                        { "sClass" : "hide-for-small", "aTargets": [ 2 ]},
                        { "sClass" : "hide-for-small", "aTargets": [ 3 ]},
                        { "sClass" : "hide-for-small date center", "aTargets": [ 4 ]},
                        { "sClass" : "center", "aTargets": [ 5 ]} 
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
            var oTable = $('#table').dataTable();
            oTable.fnDeleteRow( document.getElementById( id ) );
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

// OTHER
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
@stop
