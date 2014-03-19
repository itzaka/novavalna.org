@extends('admin.layouts.master')

@section('content')
<section id="page">
	{{Form::open(array('url' => 'admin/users/'.$user->id, 'method' => 'put', 'data-abide' => 'data-abide'))}}
	<h1 class="columns">Редакция на потребител <strong>{{$user->first_name}} {{$user->last_name}}</strong></h1>
	<div class="large-9 medium-8 medium-push-4 large-push-3 columns">
		<div class="row">
			<div class="columns">
				<label>Име</label>
				{{$user->first_name}} {{$user->last_name}}
			</div>
		</div>
		<br>
		<div class="row">
			<div class="columns">
				<label>Email</label>
				{{$user->email}} 
			</div>
		</div>
	</div>
	<div class="large-3 medium-4 medium-pull-8 large-pull-9 columns">
		<div class="row collapse hide-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin/users')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="large-12 columns"> <label>Назначени Роли</label></div>
			@foreach($roles as $role)
				@if(in_array($role->id, $user->roles()->lists('role_id')))
					<?php $true=true;?>
				@else
					<?php $true=false;?>
				@endif
				<div class="medium-6 small-6 columns end">
					<label>{{Form::checkbox('roles[]', $role->id, $true)}} {{$role->name}}</label>
				</div>
			@endforeach
		</div>
		<div class="row collapse show-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin/users')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>

	</div>
	{{Form::close()}}
</section>
@stop
@section('scripts')
@stop