@extends('admin.layouts.master')


@section('content')
<section id="page">
	<div class="columns">
		<div class="right">
			<a href="{{URL::to('admin/polls/create')}}" class="button tiny"><i class="fi-plus"></i> Нова анкета</a>
		</div>
			<h1>Анкети </h1>
		@if ($polls->count() == 0)
			{{Lang::get('basic.empty.polls')}}
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

			@foreach($polls as $poll)
			<?php $date = new Date($poll->created_at);?>
			<tr id="{{$poll->id}}" @if($poll->public == false) class="unpublished" @endif>
				<td class="hide">{{$poll->id}}</td>
				<td>
					@if($editor || $poll->public == false)
					<a href="{{URL::to('admin/polls/'.$poll->id.'/edit')}}">{{$poll->title}}</a><span data-tooltip class="radius secondary label right " title="{{$poll->votes}} гласа">{{$poll->votes}}</span>
					@else
					<a href="{{URL::to('admin/polls/'.$poll->id)}}">{{$poll->title}}</a><span data-tooltip class="radius secondary label right " title="{{$poll->votes}} гласа">{{$poll->votes}}</span>
					@endif
				</td>
				@if($editor)
				<td>
					<a class="user" data-value="{{$poll->user->first_name}} {{$poll->user->last_name}}">{{$poll->user->first_name}} {{$poll->user->last_name}}</a>					
				</td>
				@endif
				<td class="hide-for-small" align='center'>{{$poll->order}}</td>
				<td class="hide-for-small" align="center">@if($poll->public) Да @else Не @endif</td>
				<td class="hide-for-small">{{$poll->language->title}}</td>
				<td class="hide-for-small date"><?php $date = new Date($poll->created_at);?>{{$date->format('Y M dS')}}</td>
				@if($editor)
				<td class="hide-for-small date"><?php $date = new Date($poll->updated_at);?>{{$date->format('Y M dS')}}</td>
				@endif
				<td align='center'>
					<a href="{{URL::to('admin/polls/'.$poll->id)}}" class='dark'><i class="fi-graph-pie"></i></a>
					@if($editor || $poll->public == false)
					<a href="{{URL::to('admin/polls/'.$poll->id.'/edit')}}"  class='dark'><i class="fi-pencil"></i></a>
					<a onclick="deletepoll({{$poll->id}})" href="javascript:void(0)" class='dark'><i class="fi-trash"></i></a>
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
function deletepoll(id) {
  if(confirm('Сигурни ли сте, че желаете да изтриете тази анкета?'))
  $.ajax({
    url: 'polls/'+id,
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
	    	"aaSorting": [[ 0, "asc" ]],
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
	$.fn.dataTableExt.oStdClasses.sLength = "small-4 tiny left";
	$.fn.dataTableExt.oStdClasses.sFilter = "left small-8 tiny right";
	$.fn.dataTableExt.oStdClasses.sInfo = "small-4 left";
	$.fn.dataTableExt.oStdClasses.sPaging = "small-8 right table-paginate";

</script>
@stop