@extends('admin.layouts.master')


@section('content')
<section id="page">
	<div class="columns">
		<div class="right">
			<a href="{{URL::to('admin/posts/create?type='.$type->slug)}}" class="button tiny"><i class="fi-plus"></i> Нова публикация</a>
		</div>

		<h1>{{$type->title}}</h1>
		@if ($posts->count() == 0)
			{{Lang::get('basic.empty.'.$type->slug)}}
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
			<div id="category"></div>
		</div>
		<table id="table" class="small-12">
			<thead>
				<tr>
					<th class="hide">ID</th>
					<th>Заглавие</th>
					<th>Категория</th>
					@if($editor)
					<th>Автор</th>
					@endif
					<th class="hide-for-small" width="50">Подр.</th>
					<th class="hide-for-small" width="50">Публ.</th>
					<th class="hide-for-small" width="50">Език</th>
					<th class="hide-for-small" width="120">Добавена</th>
					@if($editor)
					<th class="hide-for-small" width="120">Променена</th>
					@endif
					<th width="90">Actions</th>
				</tr>
			</thead>

			@foreach($posts as $post)
			<?php $date = new Date($post->created_at);?>
			<tr id="{{$post->id}}" @if($post->public == false) class="unpublished" @endif>
				<td class="hide">{{$post->id}}</td>
				<td>
					@if($editor || $post->public == false)
					<a href="{{URL::to('admin/posts/'.$post->id.'/edit')}}">{{$post->title}}</a>
					@else
					{{$post->title}}
					@endif
				</td>
				<td>@if($post->category)<a class="category" data-value="{{$post->category->title}}">{{$post->category->title}}</a>@endif</td>
				@if($editor)
				<td>
					<a class="user" data-value="{{$post->user->first_name}} {{$post->user->last_name}}">{{$post->user->first_name}} {{$post->user->last_name}}</a>					
				</td>
				@endif
				<td class="hide-for-small" align="center">{{$post->order}}</td>
				<td class="hide-for-small" align="center">@if($post->public) Да @else Не @endif</td>
				<td class="hide-for-small" align="center">{{$post->language->title}}</td>
				<td class="hide-for-small date"><?php $date = new Date($post->created_at);?>{{$date->format('Y M dS')}}</td>
				@if($editor)
				<td class="hide-for-small date"><?php $date = new Date($post->updated_at);?>{{$date->format('Y M dS')}}</td>
				@endif
				<td align="center">
					@if($editor || $post->public == false)
					<a href="{{URL::to('admin/posts/'.$post->id.'/edit')}}"  class='dark'><i class="fi-pencil"></i></a>
					<a onclick="deletePost({{$post->id}})" href="javascript:void(0)" class="dark"><i class="fi-trash"></i></a>
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
function deletePost(id) {
  if(confirm('Сигурни ли сте, че желаете да изтриете тази страница?'))
  $.ajax({
    url: 'posts/'+id,
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
<script type="text/javascript">	
/* Custom filtering function which will filter data in column four between two values */

	$(document).ready(function() {
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
			oTable.fnFilter( $(this).data('value'), 3 );
			$('#filter #user').html('<strong>Автор:</strong> ' + $(this).data('value') + '<a class="remove-user dark"> <i class="fi-x"></i></a> ');
			$(".remove-user").on( 'click', function () {
				oTable.fnFilter( '', 3 );
				$('#filter #user').html('');
			});
		});
		$(".category").on( 'click', function () {
			/* Filter on the column (the index) of this element */
			oTable.fnFilter( $(this).data('value'), 2 );
			$('#filter #category').html('<strong>Категория:</strong> ' + $(this).data('value') + '<a class="remove-category dark"> <i class="fi-x"></i></a> ');
			$(".remove-category").on( 'click', function () {
				oTable.fnFilter( '', 2 );
				$('#filter #category').html('');
			});
		} );
	} );
	$.fn.dataTableExt.oStdClasses.sLength = "small-4 left tiny";
	$.fn.dataTableExt.oStdClasses.sFilter = "left small-8 right tiny";
	$.fn.dataTableExt.oStdClasses.sInfo = "small-4 left";
	$.fn.dataTableExt.oStdClasses.sPaging = "small-8 right table-paginate";

</script>
@stop