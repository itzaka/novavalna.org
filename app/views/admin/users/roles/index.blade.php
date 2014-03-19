@extends('admin.layouts.master')


@section('content')
<section id="page">
	<div class="columns">
		<div class="right">
			<a href="{{URL::to('admin/roles/create')}}" class="button tiny"><i class="fi-plus"></i> Нова роля</a>
		</div>
			<h1>Роли </h1>
		@if ($roles->count() == 0)
			{{Lang::get('basic.empty.roles')}}
		@else
		<table id="table" class="small-12">
			<thead>
				<tr>
					<th class="hide">ID</th>
					<th>Име</th>
					<th class="hide-for-small" width="120">Добавена</th>
					<th class="hide-for-small" width="120">Променена</th>
					<th width="90">-</th>
				</tr>
			</thead>

			@foreach($roles as $role)
			<?php $date = new Date($role->created_at);?>
			<tr id="{{$role->id}}">
				<td class="hide">{{$role->id}}</td>
				<td>
					<a href="{{URL::to('admin/roles/'.$role->id.'/edit')}}">{{$role->name}}</a>
				</td>
				<td class="hide-for-small date"><?php $date = new Date($role->created_at);?>{{$date->format('Y M dS')}}</td>
				<td class="hide-for-small date"><?php $date = new Date($role->updated_at);?>{{$date->format('Y M dS')}}</td>
				<td align='center'>
					<a href="{{URL::to('admin/roles/'.$role->id.'/edit')}}"  class='dark'><i class="fi-pencil"></i></a>
					<a onclick="deleterole({{$role->id}})" href="javascript:void(0)" class='dark'><i class="fi-trash"></i></a>
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
function deleterole(id) {
  if(confirm('Сигурни ли сте, че желаете да изтриете тази анкета?'))
  $.ajax({
    url: 'roles/'+id,
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