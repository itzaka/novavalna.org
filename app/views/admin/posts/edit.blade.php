@extends('admin.layouts.master')

@section('content')
<section id="page">
	{{Form::open(array('url' => 'admin/posts/'.$post->id, 'method' => 'put', 'data-abide' => 'data-abide'))}}
	<h1 class="columns">Редакция на <strong>{{$post->title}}</strong></h1>
	<div class="large-9 medium-8 medium-push-4 large-push-3 columns">
		<div class="row">
			<div class="medium-12 columns">
				{{Form::text('title', $post->title, array('id'=>'title', 'placeholder'=>'Заглавие', 'required' => 'required'))}}
				<small class="error">Моля добавете заглавие</small>
			</div>
		</div>
		@if($post->type->slug=='vlog')
		<div class="row">
			<div class="large-12 columns">
				<div id="player" class="flex-video widescreen vimeo"><iframe src="{{Functions::youtube($post->media_url)}}?showinfo=0&modestbranding=1&nologo=1&rel=0&title=&autohide=1&wmode=transparent" frameborder="0" width="800" height="450" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>
			</div>
		</div>
		<br />
		@endif
		<div class="row">
			<div class="large-12 columns">
				{{Form::textarea('content', $post->content, array('id' => 'content', 'required' => 'required'))}}
			</div>
		</div>
		<br />
		@if($post->type->slug!='vlog')
		<div class="row">
			<dl class="accordion large-12 columns" data-accordion>
  				<dd>
  					<a href="#panel1"><i class="fi-photo"></i> Прикачи галерия</a>
					<div id="panel1" class="content row @if($post->albums()->count() > 0) active @endif">
						<div class="large-12 columns"> <label>Албуми</label></div>
						
						@foreach($albums as $album)
							@if(in_array($album->id, $post->albums()->lists('id')))
								<?php $true=true;?>
							@else
								<?php $true=false;?>
							@endif
							<div class="medium-4 small-6 columns end">
								<label>{{Form::checkbox('albums[]', $album->id, $true)}} {{$album->title}}</label>
							</div>
						@endforeach
					</div>
				</dd>
			</dl>
		</div>
		@endif
	</div>
	<div class="large-3 medium-4 medium-pull-8 large-pull-9 columns">
			<div class="row collapse hide-for-small">
				<div class="medium-7 medium-push-5 columns">
					{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
				</div>
				<div class="medium-5 medium-pull-7 columns">
					<a href="{{URL::to('admin/posts?type='.$post->type->slug)}}" class="button tiny secondary expand">Откажи</a>
				</div>
			</div>
			<hr>
			@if($editor)
			<div class="row">
				<div class="medium-12 columns">
					<?php $status = $post->public ? true : false;?>
					<label>{{Form::checkbox('public', 1, $status)}} Публикуване</label>
				</div>
			</div>
			<div class="row">
				<div class="medium-12 columns">
					<label>
						Добавена: <?php $date = new Date($post->created_at);?>{{$date->format('Y M dS H:i:s')}}
					</label>
				</div>
			</div>
			<div class="row">
				<div class="medium-12 columns">
					<label>
						Променена: <?php $date = new Date($post->updated_at);?>{{$date->format('Y M dS H:i:s')}}
					</label>
				</div>
			</div>
			<br>
			@endif
			<div class="row collapse">
				<div class="medium-6 columns">
					{{Form::label('language', 'Език')}}
					@if ($post->language)
						{{Form::select('language', $languages, $post->language->id)}}
					@else
						{{Form::select('language', $languages)}}
					@endif
				</div>
				<div class="medium-5 columns">
					{{Form::label('order', 'Позиция')}}
					{{Form::text('order', $post->order, array('placeholder'=>'Позиция', 'required' => 'required'))}}
					<small class="error">Моля добавете позиция</small>
				</div>
			</div>
			<div class="row">
				<div class="columns">
					{{Form::label('category', 'Категория')}}
					@if ($post->category)
						{{Form::select('category', $categories, $post->category->id)}}
					@else
						{{Form::select('category', $categories)}}
					@endif
				</div>
			</div>
			@if($post->type->slug=='events')
			<div class="row">
				<div class="columns">
					{{Form::label('date', 'Дата')}}
					{{Form::text('date', $post->date, array('placeholder'=>'ГГГГ-ММ-ДД', 'pattern' => 'date'))}}
					<small class="error">Моля добавете валидна дата</small>
				</div>
			</div>
			@endif
			@if($post->type->slug=='vlog')
			<div class="row">
				<div class="columns">
					{{Form::label('media_url', 'Връзка към медия')}}<br />
					{{Form::text('media_url', $post->media_url, array('placeholder'=>'Връзка към YouTube, Vimeo, Blogger, Wordpress, видео (.mp4, .mov, .flv, .mp3, .ogg)'))}}
					<small class="error">Моля добавете валидна дата</small>
				</div>
			</div>
			@endif

			<div class="row">
				<div class="columns">
					{{Form::label('image', 'Обложка')}}
					<i class="fi-x-circle remove" style="cursor:pointer; position:absolute; right:14px; top:20px; width:1em; height:1em; background:#fff; text-align:center; line-height:1em; font-size:1.5em;)"></i>
					<a class="various fancybox.ajax" href="{{URL::to('admin/images?field=image&album='.$post->type->album->id)}}"><img id="image" style="cursor:pointer" src="@if($post->image) {{asset(Config::get('image.path').$post->image)}} @else {{asset('images/photo.jpg')}} @endif" /></a>
					{{Form::hidden('image', $post->image, array('placeholder'=>'Обложка'))}}
				</div>
			</div>
			<br />
			<div class="row collapse show-for-small">
				<div class="medium-7 medium-push-5 columns">
					{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
				</div>
				<div class="medium-5 medium-pull-7 columns">
					<a href="{{URL::to('admin/posts?type='.$post->type->slug)}}" class="button tiny secondary expand">Откажи</a>
				</div>
			</div>

	</div>
	{{Form::hidden('type', $post->type->id, array('id'=>'type'))}}
	{{Form::close()}}
</section>
@stop
@section('scripts')
@include('admin.posts.scripts')
@stop