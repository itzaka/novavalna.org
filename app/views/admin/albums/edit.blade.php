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
		<div id="filter">
			<div id="user"></div>
		</div>

		<div class="row">
			<div class="columns">
				<div id="loading"></div>
				<table id="table" class="small-12">
					<thead>
						<tr>
							<th class="hide">ID</th>
							<th width="50">Снимка</th>
							<th class="hide-for-small">Потребител</th>
							<th class="hide-for-small">Caption</th>
							<th class="hide-for-small" width="50">Подредба</th>
							<th class="hide-for-small" width="50">Публ.</th>
							<th class="hide-for-small" width="120">Добавена</th>
							@if($editor)
							<th width="20">-</th>
							@endif
						</tr>
					</thead>
					<tbody>

					@foreach($images as $image)
						<tr id="{{$image->id}}">
							<td class="hide"> {{$image->id}}</td>
							<td>
								<a class="fancybox" rel="portfolio" title="{{$image->caption}}" href="{{asset(Config::get('image.path').$image->url)}}"><img class=" @if($image->public == false) unpublished @endif" width="50" src="{{asset(Config::get('image.path').'/thumbs/'.$image->url)}}"></a>
							</td>
							<td class="hide-for-small"><a class="user" data-value="{{$image->user->first_name}} {{$image->user->last_name}}">{{$image->user->first_name}} {{$image->user->last_name}}</a></td>
							<td class="hide-for-small" align="center"><input style="margin:0;" type="text" data-id="{{$image->id}}" value="{{$image->caption}}" class="image" id="img-caption-{{$image->id}}" placeholder="Caption"></td>
							<td class="hide-for-small" align="center"><input style="margin:0;" type="text" data-id="{{$image->id}}" value="{{$image->order}}" class="image" id="img-order-{{$image->id}}" placeholder="Поз."></td>
							<td class="hide-for-small" align="center">@if($image->public) Да @else Не @endif</td>
							<td class="hide-for-small date" align="center"><?php $date = new Date($image->created_at);?>{{$date->format('Y M dS')}}</td>
							@if($editor)
							<td align="center">
								{{Form::checkbox('select[]', $image->id)}}
							</td>
							@endif
						</tr>
					@endforeach
					</tbody>
				</table>
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
		<div class="row collapse">
			<div class="columns">
				<label for="selectedAlbum">Прехвърляне в албум:</label>
			</div>
			<div class="small-9 columns">
				{{Form::select('albums', $albums, $album->id, array('id' => 'selectedAlbum'))}}
			</div>
			<div class="small-3 columns">
				<a id="changeAlbum"><span class="postfix"><i class="fi-arrow-right"></i></span></a>
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
