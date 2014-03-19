<input id="fileupload" type="file" size="45" name="upload" accept="image/gif, image/jpeg, image/png" class="button tiny secondary" style="display:none;" multiple>
<div class="row">
	<div class="small-12 columns">
		<a id="refresh" class="button tiny"><i class="fi-refresh"></i> Опресни</a>
		<a id="upload" class="button success tiny"><i class="fi-upload"></i> Качи Снимки</a>
		@if($editor)
		<a id="delete_all" class="button alert tiny"> <i class="fi-trash"></i> Изтрий Избраните</a>
		{{Form::checkbox('checkall', null, false, array('class' => 'checkall'))}}
		@endif
	</div>
</div>
<div class="row">
	<div class="medium-12 columns" id="albumsImages">
		<div class="loading"></div>
		<ul class="small-block-grid-2 medium-block-grid-5 large-block-grid-7 images"></ul>
	</div>
</div>
<input type="hidden" id="album_id" value="{{$album}}">
@include('admin.albums.ajax.scripts')