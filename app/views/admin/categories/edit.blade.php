@extends('admin.layouts.master')


@section('content')
<section id="page">
	<h1 class="columns">Редакция на категория <strong>{{$category->title}}</strong></h1>
	{{Form::open(array('url' => 'admin/categories/'.$category->id, 'method' => 'put', 'data-abide' => 'data-abide'))}}
	<div class="large-9 medium-8 large-push-3 medium-push-4 columns">
		<div class="row">
			<div class="medium-12 columns">
				{{Form::text('title', $category->title, array('id' => 'title', 'placeholder'=>'Заглавие', 'required' => 'required'))}}
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
				{{Form::select('language', $languages, $category->language->id, array('disabled' => 'disabled'))}}
			</div>
			<div class="medium-5 columns">
				{{Form::label('order', 'Позиция')}}
				{{Form::text('order', 999, array('placeholder'=>'Позиция', 'required' => 'required'))}}
				<small class="error">Моля добавете позиция</small>
			</div>
		</div>
		<div>
			{{Form::label('type', 'Секция')}}
			{{Form::select('type', $types, $category->type->id, array('disabled' => 'disabled'))}}
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
