<input id="fileupload" value="bla" type="file" data-url={{URL::to("admin/images")}} size="45" name="upload" accept="image/gif, image/jpeg, image/png" class="button tiny secondary" style="display:none;" multiple>
<div class="row">
	<div class="small-12 columns">
		<a id="add_album" class="button secondary tiny"><i class="fi-page-add"></i> Добави Албум</a>
		<a id="refresh" class="button tiny"><i class="fi-refresh"></i> Опресни</a>
		<a id="upload" class="button success tiny"><i class="fi-upload"></i> Качи Снимки</a>
		@if($editor)
		<a id="delete_all" class="button alert tiny"> <i class="fi-trash"></i> Изтрий Избраните</a>
		{{Form::checkbox('checkall', null, false, array('class' => 'checkall'))}}
		@endif
	</div>
</div>
<div class="row">
	<div class="medium-3 large-2 columns" id="albums">
		<div class="loading"></div>
		<ul class="side-nav"></ul>
	</div>
	<div class="medium-9 large-10 columns" id="albumsImages">
		<div class="loading"></div>
		<ul class="small-block-grid-2 medium-block-grid-4 large-block-grid-6 images"></ul>
	</div>
</div>
<input type="hidden" id="album_id" value="{{$album}}">
@include('admin.albums.ajax.scripts')