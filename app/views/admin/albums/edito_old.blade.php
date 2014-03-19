@extends('admin.layouts.master')


@section('content')
<input id="fileupload" type="file" size="45" data-url="{{ URL::asset('admin/images?album='.$album->id)}}" name="upload" accept="image/gif, image/jpeg, image/png" class="button tiny secondary" style="display:none;" multiple>

<section id="page">
	<h1 class="columns">Снимки от <strong>{{$album->title}}</strong></h1>
	{{Form::open(array('url' => 'admin/albums/'.$album->id, 'method' => 'put', 'data-abide' => 'data-abide'))}}
	<div class="large-9 medium-8 large-push-3 medium-push-4 columns">
		<div class="row">
			<div class="medium-12 columns">
				@if($album->system == true or $editor == false)
				{{Form::text('title', $album->title, array('id' => 'title', 'placeholder'=>'Заглавие', 'required' => 'required', 'readonly' => 'readonly'))}}
				@else
				{{Form::text('title', $album->title, array('id' => 'title', 'placeholder'=>'Заглавие', 'required' => 'required'))}}
				@endif
				<small class="error">Моля добавете заглавие</small>
			</div>
		</div>
		<h5>Снимки</h5>
		<div class="row">
			<div class="medium-12 columns">
				<div id="loading"></div>
				<ul class="small-block-grid-2 medium-block-grid-3 large-block-grid-4 images">
					@foreach($images as $image)
						<li id="{{$image->id}}">
							<a class="fancybox" rel="portfolio" title="{{$image->user->first_name}} {{$image->user->last_name}}" href="{{asset(Config::get('image.path').$image->url)}}"><img class="th small-12 @if($image->public == false) unpublished @endif" src="{{asset(Config::get('image.path').'/thumbs/'.$image->url)}}"></a>
							@if($editor == true)
							<div class="options">
								{{Form::checkbox('select[]', $image->id)}}
								<a class="dark" href="#" data-dropdown="drop-{{$image->id}}"><i class="fi-pencil"></i></a>
								<!--<a onclick="confirmDeleteImage({{$image->id}})" href="javascript:void(0)" class="dark"><i class="fi-trash"></i></a>-->
							</div>
							@endif
							<div id="drop-{{$image->id}}" data-dropdown-content class="f-dropdown content small">
								<div class="row collapse">
									<div class="small-8 columns">
										<input style="margin:0;" type="text" data-id="{{$image->id}}" value="{{$image->caption}}" class="image" id="img-caption-{{$image->id}}" placeholder="Caption">
									</div>
									<div class="small-4 columns">
										<input style="margin:0;" type="text" data-id="{{$image->id}}" value="{{$image->order}}" class="image" id="img-order-{{$image->id}}" placeholder="Поз.">
									</div>
								</div>
							</div>
						</li>
					@endforeach
				</ul>
				<div class="margintop-20px"></div>
				<div class="row"></div>
				<div class="margintop-20px"></div>
			</div>
		</div>
		
	</div>
	<div class="large-3 medium-4 large-pull-9 medium-pull-8 columns">
		<div class="row collapse hide-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin/albums')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>
		<hr>
		<div class="row collapse">
			<div class="medium-12 columns">
				<a class="button secondary small expand success" id="images"> <i class="fi-upload"></i> добави снимки</a> 
			</div>
		</div>
		@if($editor == true)
		<div class="row collapse">
			<div class="small-3 columns">
				<a class="button  small expand" onclick="publishImages()" href="javascript:void(0)"> <i class="fi-check"></i></a> 
			</div>
			<div class="small-3 columns">
				<a class="button secondary small expand" onclick="unPublishImages()" href="javascript:void(0)"> <i class="fi-x"></i></a> 
			</div>
			<div class="small-3 columns">
				<a class="button alert small expand" onclick="deleteImages()" href="javascript:void(0)"> <i class="fi-trash"></i></a> 
			</div>

			<div class="small-2 columns">
				{{Form::checkbox('checkall', null, false, array('class' => 'checkall'))}}
			</div>
		</div>
		@endif
		<div class="row collapse show-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin/albums')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>
	</div>
	{{Form::close()}}

</section>
@stop
@section('scripts')
	@include('admin.albums.scripts')
@stop
