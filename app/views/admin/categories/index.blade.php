@extends('admin.layouts.master')


@section('content')
<section id="page">
	<div class="columns">
		<div class="right">
			<a href="{{URL::to('admin/categories/create')}}@if($type)?type={{$type->slug}}@endif" class="button tiny"><i class="fi-plus"></i> Нова категория</a>
		</div>
		@if($type)
			<h1>Категории <small>/ {{$type->title}} /</small> <br /><small><a href="{{URL::to('admin/categories')}}">покажи всички</a></small></h1>
		@else
			<h1>Категории</h1>
		@endif
		@if(Session::get('message'))
		<div data-alert class="alert-box warning radius">
  			{{Session::get('message')}}
  			<a href="#" class="close">&times;</a>
		</div>
		@endif
		@if ($categories->count() == 0)
			{{Lang::get('basic.empty.categories')}}
		@else
		<div id="filter">
			<div id="user">
			</div>
			<div id="section"></div>
		</div>
		<table id="table" class="small-12">
			<thead>
				<tr>
					<th class="hide">ID</th>
					<th>Заглавие</th>
					<th class="hide-for-small">Секция</th>
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

			@foreach($categories as $category)
			<?php $date = new Date($category->created_at);?>
			<tr id="{{$category->id}}"  @if($category->public == false) class="unpublished" @endif>
				<td class="hide">{{$category->id}}</td>
				<td>
					@if($editor || $category->public == false)
					<a href="{{URL::to('admin/categories/'.$category->id.'/edit')}}">{{$category->title}}</a>
					@else
					{{$category->title}}
					@endif
				</td>
				<td class="hide-for-small">
					<a class="section" data-value="{{$category->type->title}}">{{$category->type->title}}</a>
				</td>
				@if($editor)
				<td>
					<a class="user" data-value="{{$category->user->first_name}} {{$category->user->last_name}}">{{$category->user->first_name}} {{$category->user->last_name}}</a>					
				</td>
				@endif
				<td class="hide-for-small" align="center">{{$category->order}}</td>
				<td class="hide-for-small" align="center">@if($category->public) Да @else Не @endif</td>
				<td class="hide-for-small">{{$category->language->title}}</td>
				<td class="hide-for-small date"><?php $date = new Date($category->created_at);?>{{$date->format('Y M dS')}}</td>
				@if($editor)
				<td class="hide-for-small date"><?php $date = new Date($category->updated_at);?>{{$date->format('Y M dS')}}</td>
				@endif
				<td align="center">
					@if($editor || $category->public == false)
					<a href="{{URL::to('admin/categories/'.$category->id.'/edit')}}"  class='dark'><i class="fi-pencil"></i></a>
					@elseif($editor)
					<a onclick="deletecategory({{$category->id}})" href="javascript:void(0)" class='dark'><i class="fi-trash"></i></a>
					@else
					-
					@endif
				</td>

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
function deletecategory(id) {
  if(confirm('Сигурни ли сте, че желаете да изтриете тази категория?'))
  $.ajax({
    url: 'categories/'+id,
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
			oTable.fnFilter( $(this).data('value'), 3 );
			$('#filter #user').html('<strong>Автор:</strong> ' + $(this).data('value') + '<a class="remove-user dark"> <i class="fi-x"></i></a> ');
			$(".remove-user").on( 'click', function () {
				oTable.fnFilter( '', 3 );
				$('#filter #user').html('');
			});
		});
		$(".section").on( 'click', function () {
			/* Filter on the column (the index) of this element */
			oTable.fnFilter( $(this).data('value'), 2 );
			$('#filter #section').html('<strong>Секция:</strong> ' + $(this).data('value') + '<a class="remove-section dark"> <i class="fi-x"></i></a> ');
			$(".remove-section").on( 'click', function () {
				oTable.fnFilter( '', 2 );
				$('#filter #section').html('');
			});
		} );

	} );
	$.fn.dataTableExt.oStdClasses.sLength = "small-4 left tiny";
	$.fn.dataTableExt.oStdClasses.sFilter = "left small-8 right tiny";
	$.fn.dataTableExt.oStdClasses.sInfo = "small-4 left";
	$.fn.dataTableExt.oStdClasses.sPaging = "small-8 right table-paginate";

</script>

<script type="text/javascript" src="{{asset('fancybox/js/jquery.mousewheel-3.0.6.pack.js')}}"></script>
<script type="text/javascript" src="{{asset('fancybox/js/jquery.fancybox.js?v=2.1.5')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('fancybox/css/jquery.fancybox.css?v=2.1.5')}}" media="screen" />
<link rel="stylesheet" type="text/css" href="{{asset('fancybox/css/jquery.fancybox-buttons.css?v=1.0.5')}}" />
<script type="text/javascript" src="{{asset('fancybox/js/jquery.fancybox-buttons.js?v=1.0.5')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".various").fancybox({
        fitToView   : false,
        width       : '90%',
        height      : '80%',
        autoSize    : false,
        closeClick  : false,
        openEffect  : 'none',
        closeEffect : 'none'
    });
});
</script>
@stop