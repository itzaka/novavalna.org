<ul class="small-block-grid-2 medium-block-grid-4 large-block-grid-6 images">
	@foreach($images as $image)
	<li><img class="th small-12 image" src="{{asset(Config::get('image.path').'/thumbs/'.$image->url)}}" data-url="{{$image->url}}"></li>
	@endforeach
</ul>

<script>
$(document).ready(function() {

$('.image').on('dblclick', function() {
	$('img#image').attr('src', '{{asset(Config::get("image.path")."/covers/'+$(this).data('url')+'")}}');
	$('input#image').val($(this).data('url'));
	$('#imageManager').foundation('reveal', 'close');
});
})
</script>