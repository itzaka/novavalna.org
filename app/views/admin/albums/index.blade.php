@extends('admin.layouts.master')


@section('content')
<section id="page">
	<div class="columns">
		<div class="right">
			<a href="{{URL::to('admin/albums/create')}}" class="button tiny"><i class="fi-plus"></i> Нов албум</a>
		</div>
			<h1>Албуми </h1>
		@if ($albums->count() == 0)
			{{Lang::get('basic.empty.albums')}}
		@else
		<table id="table" class="small-12">
			<thead>
				<tr>
					<th class="hide">ID</th>
					<th>Заглавие</th>
					<th class="hide-for-small" width="120">Добавен</th>
					<th class="hide-for-small" width="120">Променен</th>
					<th width="90">Actions</th>
				</tr>
			</thead>

			@foreach($albums as $album)
			<tr id="{{$album->id}}">
				<td class="hide"> {{$album->id}}</td>
				<td>
					<a href="{{URL::to('admin/albums/'.$album->id.'/edit')}}">{{$album->title}}</a> <span data-tooltip class="radius secondary label right " title="{{$album->images->count()}} снимки">{{$album->images->count()}}</span>
				</td>
				<td class="hide-for-small date" align="center"><?php $date = new Date($album->created_at);?>{{$date->format('Y M dS')}}</td>
				<td class="hide-for-small date" align="center"><?php $date = new Date($album->updated_at);?>{{$date->format('Y M dS')}}</td>
				<td align="center">
					<a href="{{URL::to('admin/albums/'.$album->id.'/edit')}}" class='dark tip-top' title="Промени"><i class="fi-pencil"></i></a>
					@if ($album->system == false && $editor)
						<a onclick="deletealbum({{$album->id}})" href="javascript:void(0)" class='dark tip-top' title="Изтрий"><i class="fi-trash"></i></a>
					@endif
				</td>
			</tr>
			@endforeach
		</table>
		@endif

	</div><div class="row"></div>
</section>
@stop
@section('scripts')
<script type="text/javascript">
function deletealbum(id) {
  if(confirm('Сигурни ли сте, че желаете да изтриете този албум?'))
  $.ajax({
    url: 'albums/'+id,
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
	$.fn.dataTableExt.oStdClasses.sLength = "small-4 left tiny";
	$.fn.dataTableExt.oStdClasses.sFilter = "left small-8 right tiny";
	$.fn.dataTableExt.oStdClasses.sInfo = "small-4 left";
	$.fn.dataTableExt.oStdClasses.sPaging = "small-8 right table-paginate";

</script>
@stop