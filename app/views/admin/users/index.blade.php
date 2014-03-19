@extends('admin.layouts.master')


@section('content')
<section id="page">
	<div class="columns">
		<h1>Потребители </h1>
		@if ($users->count() == 0)
			{{Lang::get('basic.empty.users')}}
		@else
		<table id="table" class="small-12">
			<thead>
				<tr>
					<th class="hide">ID</th>
					<th>Име</th>
					<th>Потребителско Име</th>
					<th>Email</th>
					<th>Роли</th>
					<th class="hide-for-small" width="120">Добавен</th>
					<th class="hide-for-small" width="120">Променен</th>
					<th width="90">-</th>
				</tr>
			</thead>

			@foreach($users as $user)
			<?php $date = new Date($user->created_at);?>
			<tr id="{{$user->id}}">
				<td class="hide">{{$user->id}}</td>
				<td>
					<a href="{{URL::to('admin/users/'.$user->id.'/edit')}}">{{$user->first_name}} {{$user->last_name}}</a>
				</td>
				<td>{{$user->username}}</td>
				<td>{{$user->email}}</td>
				<td class="hide-for-small" align='center'>@foreach($user->roles as $role) <span>{{$role->name}}</span> @endforeach</td>
				<td class="hide-for-small date"><?php $date = new Date($user->created_at);?>{{$date->format('Y M dS')}}</td>
				<td class="hide-for-small date"><?php $date = new Date($user->updated_at);?>{{$date->format('Y M dS')}}</td>
				<td align='center'>
					<a href="{{URL::to('admin/users/'.$user->id.'/edit')}}"  class='dark'><i class="fi-pencil"></i></a>
					<a onclick="deleteuser({{$user->id}})" href="javascript:void(0)" class='dark'><i class="fi-trash"></i></a>
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
function deleteuser(id) {
  if(confirm('Сигурни ли сте, че желаете да изтриете тази анкета?'))
  $.ajax({
    url: 'users/'+id,
    type: 'DELETE',
    dataType: 'json',
    success: function(result) {
    	if(result.success)
    		$('#' + id).remove();
    }
  });
}
</script>

<script type="text/javascript" src="{{URL::asset('packages/datatables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript">	$(document).ready(function() {
	    $('#table').dataTable({
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
	
	} );
	$.fn.dataTableExt.oStdClasses.sLength = "small-4 tiny left";
	$.fn.dataTableExt.oStdClasses.sFilter = "left small-8 tiny right";
	$.fn.dataTableExt.oStdClasses.sInfo = "small-4 left";
	$.fn.dataTableExt.oStdClasses.sPaging = "small-8 right table-paginate";

</script>
@stop