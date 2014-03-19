@extends('admin.layouts.master')

@section('content')
<section id="page">
	{{Form::open(array('url' => 'admin/roles', 'data-abide' => 'data-abide'))}}
	<h1 class="columns">Добавяне на роля</h1>
	<div class="large-9 medium-8 medium-push-4 large-push-3 columns">
		<div class="row">
			<div class="medium-12 columns">
				{{Form::label('name', 'Име на роля')}}
				{{Form::text('name', null, array('id'=>'name', 'placeholder'=>'Заглавие', 'required' => 'required'))}}
				<small class="error">Моля добавете Име на роля</small>
			</div>
		</div>
		<br />
		<div class="row">
			<div class="large-12 columns"> <label>Назначени Права</label></div>
			@foreach($permissions as $permission)
				<div class="medium-4 small-6 columns end">
					<label>{{Form::checkbox('permissions[]', $permission->id)}} {{$permission->display_name}}</label>
				</div>
			@endforeach
		</div>

	</div>
	<div class="large-3 medium-4 medium-pull-8 large-pull-9 columns">
		<div class="row collapse">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin/roles')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>
	</div>
	{{Form::close()}}
</section>
@stop
@section('scripts')
@stop