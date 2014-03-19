@extends('admin.layouts.master')


@section('content')
<section id="page">
			<h1 class="entry-title">{{$post->title}}</h1>
			@if($post->type->slug != 'about')
				<?php $date= new Date($post->created_at);?>
				<div class="entry-date">{{$date->format('F dS, Y')}}</div>
				@if($post->category)<div class="entry-category">Категория: <a href="?category={{$post->category->slug}}">{{$post->category->title}}</a></div>@endif
			@endif
			<div class="entry-content">
				@if($post->image)<img src="{{$post->image}}" class="medium-6 left">@endif
				{{$post->content}}
			</div>
			<div class="row"></div>
		</div>
		<div class="arrow"></div>
</section>

@stop