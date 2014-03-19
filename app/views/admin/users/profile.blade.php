@extends('admin.layouts.master')

@section('content')
<section id="page">
	{{Form::open(array('url' => 'admin/profile', 'method' => 'put', 'data-abide' => 'data-abide'))}}
	<h1 class="columns">Потребителски профил</h1>
	<div class="large-9 medium-8 medium-push-4 large-push-3 columns">
		@foreach($errors->all() as $message)
		<div data-alert class="alert-box warning radius">
  			{{$message}}
  			<a href="#" class="close">&times;</a>
		</div>
		@endforeach	
		<div class="row">
			<div class="medium-6 columns">
				{{Form::label('first_name', 'Име')}}
				{{Form::text('first_name', $user->first_name, array('id'=>'first_name', 'placeholder'=>'Име', 'required' => 'required'))}}
				<small class="error">Моля добавете Име</small>
			</div>
			<div class="medium-6 columns">
				{{Form::label('first_name', 'Фамилия')}}
				{{Form::text('last_name', $user->last_name, array('id'=>'last_name', 'placeholder'=>'Фамилия', 'required' => 'required'))}}
				<small class="error">Моля добавете Фамилия</small>
			</div>
		</div>
		<div class="row">
			<div class="medium-6 columns">
				{{Form::label('username', 'Потребителско име')}}
				{{Form::text('username', $user->username, array('id'=>'email', 'placeholder'=>'Потребителско име', 'required' => 'required'))}}
				<small class="error">Моля добавете Потербителско име</small>
			</div>
		</div>
		<div class="row">
			<div class="medium-6 columns">
				{{Form::label('email', 'Email')}}
				{{Form::email('email', $user->email, array('id'=>'email', 'placeholder'=>'Email', 'required' => 'required'))}}
				<small class="error">Моля добавете Email</small>
			</div>
		</div>
		<hr>
		<h5>Смяна на парола</h5>
		<div class="row">
			<div class="medium-6 columns">
				{{Form::label('password', 'Стара парола')}}
				{{Form::password('password', null)}}
				<small class="error">Паролата трябва да съдържа поне  1 главна буква, 1 цифра и 1 символ и да е дълга поне 8 бувки</small>
			</div>
			<div class="medium-6 columns">
				{{Form::label('newpassword', 'Нова парола')}}
				{{Form::password('newpassword', null)}}
				<small class="error">Паролата трябва да съдържа поне  1 главна буква, 1 цифра и 1 символ и да е дълга поне 8 бувки</small>
			</div>

		</div>

	</div>
	<div class="large-3 medium-4 medium-pull-8 large-pull-9 columns">
		<div class="row collapse hide-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>
		<hr>
		<div class="small-12">
			<h5>Потребителски роли </h5>
			<div class="columns">
			@foreach($user->roles as $role)
				<label>{{$role->name}}</label>
			@endforeach
			</div>
		</div>
		<div class="row collapse show-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>

	</div>
	{{Form::close()}}
</section>
@stop
@section('scripts')
@stop