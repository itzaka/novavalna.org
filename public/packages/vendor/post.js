// Parse video url to check video type
function parseVideoURL(url) {
	if (url == '') {
		var provider = 'nomedia', id;
	}		
	else {
		var provider = url.match(/(http|https):\/\/(:?www.)?(\w*)/)[3],
        id;
	    if(provider == "youtube") {
	    	var url = $.url(url); // jQuery version
	    	id = url.param('v');
	    } else if (provider == "vimeo") {

	        id = url.match(/(http|https)?:\/\/(?:www.)?(\w*).com\/(\d*)/)[3];
	    } else {
	    	var extension = url.substr( (url.lastIndexOf('.') +1) );
	    	if(extension == 'mp4' || extension == 'mov' || extension == 'flv' || extension == 'm4v')
	    		provider = 'video';	
	    	else if(extension == 'mp3' || extension == 'ogg' || extension == 'wav')
	    		provider = 'audio';
	    	else
	        	var provider = 'nomedia', id; 
    	}
	}
    
    return {
        provider : provider,
        id : id
    }
}

// Checks video type and triggers the coresponding video type function
var getmedia = function() {
	setTimeout(function () {
		var link = parseVideoURL($('#media_url').val());
		if (link.provider == 'youtube')
			getyoutube(link.id);
		if (link.provider == 'vimeo')
			getvimeo(link.id);
		if (link.provider == 'video')
			getvideo();
		if (link.provider == 'audio')
			getaudio();
		if (link.provider == 'nomedia')
			nomedia();
    }, 100);
  }
$( "#media_url" ).on('paste', getmedia);
$( "#media_refresh" ).on('click', getmedia);
$('#form').submit(function( event ) {
	var link = parseVideoURL($('#media_url').val());
	if (link.provider == 'youtube')
		$('#type').val('youtube');
	if (link.provider == 'vimeo')
		$('#type').val('vimeo');
	if (link.provider == 'video')
		$('#type').val('video');
	if (link.provider == 'audio')
		$('#type').val('audio');
	if (link.provider == 'nomedia')
		$('#type').val('text');
});
$( "#image" ).on('paste', function() {
	setTimeout(function () {
		ajaxImageDownload($('#image').val())
    }, 100);
 });

// Video type functions to populate the video player and the video inputs
function getyoutube(id) {
$.getJSON( "https://gdata.youtube.com/feeds/api/videos/"+id+"?v=2&alt=json", function( data ) {
	if ($('#title').val() == '') {
		$('#title').val(data.entry.title.$t);
	}
	if (typeof(data.entry.yt$recorded) !== 'undefined' && $('#recorded_at').val() == '') {
		$('#recorded_at').val(data.entry.yt$recorded.$t);
	}
	if (CKEDITOR.instances['content'].getData() == '') {
		CKEDITOR.instances['content'].setData(data.entry.media$group.media$description.$t)
	}
	if ($('#image').val() == '') {
		if(typeof(data.entry.yt$hd) != 'undefined')
			ajaxImageDownload('https://i1.ytimg.com/vi/'+id+'/maxresdefault.jpg');
		else
			ajaxImageDownload('https://i1.ytimg.com/vi/'+id+'/sddefault.jpg');
	}

});
	//$('#type').val('youtube');
	$('#player').html('<div class="flex-video widescreen vimeo"><iframe src="http://www.youtube.com/embed/'+id+'?showinfo=0&modestbranding=1&nologo=1&rel=0&title=&autohide=1&wmode=transparent" frameborder="0" width="400" height="225" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>')
}

function getvimeo(id) {
$.getJSON( "http://vimeo.com/api/v2/video/"+id+".json", function( data ) {
	if ($('#title').val() == '') {
		$('#title').val(data[0].title);
	}
	if ($('#recorded_at').val() == '') {
		$('#recorded_at').val(data[0].upload_date);
	}
	if ($('#image').val() == '') {
		ajaxImageDownload(data[0].thumbnail_large);
	}
	if (CKEDITOR.instances['content'].getData() == '') {
		CKEDITOR.instances['content'].setData(data[0].description)
	}
});
	//$('#type').val('vimeo');
	$('#player').html('<iframe src="//player.vimeo.com/video/'+id+'?title=0&amp;byline=0&amp;portrait=0" width="400" height="225" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>')

}
function getvideo() {
	$('#player').html('<iframe src="/embed?file='+$('#media_url').val()+'&poster='+$('#image').val()+'&html5=true&autohide=true" width="400" height="225" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>')
	//$('#type').val('video');
}
function getaudio() {
	$('#player').html('<iframe src="/embed?file='+$('#media_url').val()+'&poster='+$('#image').val()+'&html5=true" width="400" height="225" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>')
	//$('#type').val('audio');
}
function nomedia() {
	$('#player').html('');
	//$('#type').val('text');
	 $("#loading").show();

	$.get( '/parse?url='+ $('#media_url').val(), function( data ) {
		var content = $(data).find(".entry-content, .entry").html();
		if (CKEDITOR.instances['content'].getData() == '') {
			CKEDITOR.instances['content'].setData(content)
		}
		$('#title').val($(data).find(".entry-title").text());
		 	$("#loading").hide();

	});
}
function ajaxImageDownload(image){
 	$("#loading").show();
	$.ajax({
		type: "POST",
		url:'/admin/images',
  		data: { type: 'download',image: image, album:7 },
  		dataType: "json",
  		success: function (data, status)
		{
			if(data.success == true)
			{
				$('input#image').val(data.image);
				$('img#image').attr('src', '/' + data.path + data.image);
				$("#loading").hide();
			}
		}
	});
}