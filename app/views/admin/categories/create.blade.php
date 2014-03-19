@extends('admin.layouts.master')


@section('content')
<section id="page">
	<h1 class="columns">Добавяне на категория</h1>
	{{Form::open(array('url' => 'admin/categories', 'data-abide' => 'data-abide'))}}
	<div class="large-9 medium-8 large-push-3 medium-push-4 columns">
		<div class="row">
			<div class="medium-12 columns">
				{{Form::text('title', null, array('id' => 'title', 'placeholder'=>'Заглавие', 'required' => 'required'))}}
				<small class="error">Моля добавете заглавие</small>
			</div>
		</div>
	</div>
	<div class="large-3 medium-4 large-pull-9 medium-pull-8 columns">
		<div class="row collapse hide-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin/categories')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>
		<hr>
		<div class="row collapse">
			<div class="medium-6 columns">
				{{Form::label('language', 'Език')}}
				@if ($language)
					{{Form::select('language', $languages, $language->id)}}
				@else
					{{Form::select('language', $languages)}}
				@endif
			</div>
			<div class="medium-5 columns">
				{{Form::label('order', 'Позиция')}}
				{{Form::text('order', 999, array('placeholder'=>'Позиция', 'required' => 'required'))}}
				<small class="error">Моля добавете позиция</small>
			</div>
		</div>
		<div>
			{{Form::label('type', 'Секция')}}
			@if ($type)
				{{Form::select('type', $types, $type->id, array('required', 'required'))}}
			@else
				{{Form::select('type', $types, null, array('required', 'required'))}}
			@endif
			<small class="error">Моля изберете секция</small>
		</div>
		<div class="row collapse show-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin/categories')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>
	</div>
	{{Form::close()}}

</section>
@stop
