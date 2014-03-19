@extends('admin.layouts.master')


@section('content')
<section id="page">
	<div class="columns">
		<div class="right">
			<a href="{{URL::to('admin/banners/create')}}" class="button tiny"><i class="fi-plus"></i> Нов банер</a>
		</div>
			<h1>Банери </h1>
		@if ($banners->count() == 0)
			{{Lang::get('basic.empty.banners')}}
		@else

		@if(Session::get('message'))
		<div data-alert class="alert-box warning radius">
  			{{Session::get('message')}}
  			<a href="#" class="close">&times;</a>
		</div>
		@endif
		<div id="filter">
			<div id="user">
			</div>
			<div id="position"></div>
		</div>
		<table id="table" class="small-12">
			<thead>
				<tr>
					<th class="hide">ID</th>
					<th class="hide-for-small" width="70">Снимка</th>
					<th>URL</th>
					<th class="hide-for-small">Caption</th>
					<th class="hide-for-small" width="70">Позиция</th>
					@if($editor)
					<th>Автор</th>
					@endif
					<th class="hide-for-small" width="50">Подр.</th>
					<th class="hide-for-small" width="50">Публ.</th>
					<th class="hide-for-small" width="50">Език</th>
					<th class="hide-for-small" width="120">Добавен</th>
					@if($editor)
					<th class="hide-for-small" width="120">Променен</th>
					@endif
					<th width="90">Actions</th>
				</tr>
			</thead>

			@foreach($banners as $banner)
			<?php $date = new Date($banner->created_at);?>
			<tr id="{{$banner->id}}"  @if($banner->public == false) class="unpublished" @endif>
				<td class="hide">{{$banner->id}}</td>
				<td class="hide-for-small">
					@if($editor || $banner->public == false)
					<a href="{{URL::to('admin/banners/'.$banner->id.'/edit')}}"><img src="{{asset(Config::get('image.path').'thumbs/'.$banner->image)}}" width="50"></a>
					@else
					<img src="{{asset(Config::get('image.path').'thumbs/'.$banner->image)}}" width="50">
					@endif
				</td>
				<td>
					@if($editor || $banner->public == false)
					<a href="{{URL::to('admin/banners/'.$banner->id.'/edit')}}">{{$banner->url}}</a> <span data-tooltip class="radius secondary label right " title="{{$banner->clicks}} Кликания">{{$banner->clicks}}</span>
					@else
					{{$banner->url}} <span data-tooltip class="radius secondary label right " title="{{$banner->clicks}} Кликания">{{$banner->clicks}}</span>
					@endif
				</td>
				
				<td class="hide-for-small">{{$banner->caption}}</td>
				<td class="hide-for-small" align='center'><a class="position" data-value="{{$banner->position->title}}">{{$banner->position->title}}</a></td>
				@if($editor)
				<td>
					<a class="user" data-value="{{$banner->user->first_name}} {{$banner->user->last_name}}">{{$banner->user->first_name}} {{$banner->user->last_name}}</a>					
				</td>
				@endif
				<td class="hide-for-small" align='center'>{{$banner->order}}</td>
				<td class="hide-for-small" align="center">@if($banner->public) Да @else Не @endif</td>
				<td class="hide-for-small" align='center'>{{$banner->language->title}}</td>
				<td class="hide-for-small date" align='center'><?php $date = new Date($banner->created_at);?>{{$date->format('Y M dS')}}</td>
				@if($editor)
				<td class="hide-for-small date" align='center'><?php $date = new Date($banner->updated_at);?>{{$date->format('Y M dS')}}</td>
				@endif
				<td align='center'>
					@if($editor || $banner->public == false)
					<a href="{{URL::to('admin/banners/'.$banner->id.'/edit')}}"  class='dark'><i class="fi-pencil"></i></a>
					<a onclick="deletebanner({{$banner->id}})" href="javascript:void(0)" class='dark'><i class="fi-trash"></i></a>
					@else
					-
					@endif
				</td>
			</tr>
			@endforeach
		</table>
		@endif
	</div>
</section>
@stop
@section('scripts')
<script type="text/javascript">
function deletebanner(id) {
  if(confirm('Сигурни ли сте, че желаете да изтриете този банер?'))
  $.ajax({
    url: 'banners/'+id,
    type: 'DELETE',
    dataType: 'json',
    success: function(result) {
    	if(result.success)
    		$('#' + id).remove();
		else 
			alert(result.message);
    }
  });
}
</script>

<script type="text/javascript" src="{{URL::asset('packages/datatables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript">	$(document).ready(function() {
	    var oTable = $('#table').dataTable({
	    	"aaSorting": [[ 0, "desc" ]],
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
			oTable.fnFilter( $(this).data('value'), 5 );
			$('#filter #user').html('<strong>Автор:</strong> ' + $(this).data('value') + '<a class="remove-user dark"> <i class="fi-x"></i></a> ');
			$(".remove-user").on( 'click', function () {
				oTable.fnFilter( '', 5 );
				$('#filter #user').html('');
			});
		});
		$(".position").on( 'click', function () {
			/* Filter on the column (the index) of this element */
			oTable.fnFilter( $(this).data('value'), 4 );
			$('#filter #position').html('<strong>Категория:</strong> ' + $(this).data('value') + '<a class="remove-position dark"> <i class="fi-x"></i></a> ');
			$(".remove-position").on( 'click', function () {
				oTable.fnFilter( '', 4 );
				$('#filter #position').html('');
			});
		} );

	} );
	$.fn.dataTableExt.oStdClasses.sLength = "small-4 left tiny";
	$.fn.dataTableExt.oStdClasses.sFilter = "left small-8 right tiny";
	$.fn.dataTableExt.oStdClasses.sInfo = "small-4 left";
	$.fn.dataTableExt.oStdClasses.sPaging = "small-8 right table-paginate";

</script>
@stop