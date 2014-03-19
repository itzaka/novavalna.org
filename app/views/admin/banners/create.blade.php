@extends('admin.layouts.master')


@section('content')
<section id="page">
	<h1 class="columns">Добавяне на Банер</h1>
	{{Form::open(array('url' => 'admin/banners', 'data-abide' => 'data-abide'))}}
	<div class="large-9 medium-8 large-push-3 medium-push-4 columns">
		<div class="row">
			<div class="medium-12 columns">
				{{Form::label('url', 'Връзка')}}
				{{Form::url('url', null, array('id' => 'url', 'placeholder'=>'Връзка', 'required' => 'required'))}}
				<small class="error">Моля добавете валидна връзка</small>
			</div>
		</div>
		<div class="row">
			<div class="medium-12 columns">
				{{Form::label('caption', 'Caption')}}
				{{Form::textarea('caption', null, array('id' => 'caption', 'placeholder'=>'Caption'))}}
			</div>
		</div>

	</div>
	<div class="large-3 medium-4 large-pull-9 medium-pull-8 columns">
		<div class="row collapse hide-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin/banners')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>
		<hr>
		<div class="row collapse">
			<div class="medium-6 columns">
				{{Form::label('language', 'Език')}}
				{{Form::select('language', $languages)}}
			</div>
			<div class="medium-5 columns">
				{{Form::label('order', 'Подредба')}}
				{{Form::text('order', 999, array('placeholder'=>'Подредба', 'required' => 'required'))}}
				<small class="error">Моля добавете Подредба</small>
			</div>
		</div>
		<div class="row">
			<div class="columns">
				{{Form::label('position', 'Позиция')}}
				{{Form::select('position', $positions)}}
			</div>
		</div>
		<div class="row">
			<div class="columns">
				{{Form::label('image', 'Картинка')}}
				<a class="various fancybox.ajax" href="{{URL::to('admin/images?field=image')}}"><img id="image" style="cursor:pointer" src="{{asset('images/photo.jpg')}}" /></a>
				{{Form::hidden('image', null, array('placeholder'=>'Обложка'))}}
			</div>
		</div>
		<br />
		<div class="row collapse show-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin/banners')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>
	</div>
	{{Form::close()}}

</section>
@stop
@section('scripts')
	@include('admin.banners.scripts')

@stop
