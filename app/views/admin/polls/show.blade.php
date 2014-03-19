@extends('admin.layouts.master')


@section('content')
<section id="page">
	<h1 class="columns">Резултати от анкета <strong>{{$poll->title}}</strong></h1>
	<div id="answers" class="large-12 columns" style="height:600px; position:relative;"></div>
	<div class="large-12 columns" style="height:100px;"></div>
</section>
@stop
@section('scripts')
<script src="{{asset('packages/jqplot/jquery.jqplot.min.js')}}"></script>
<script src="{{asset('packages/jqplot/jqplot.pieRenderer.min.js')}}"></script>
<script src="{{asset('packages/jqplot/jqplot.barRenderer.min.js')}}"></script>
<script src="{{asset('packages/jqplot/jqplot.categoryAxisRenderer.min.js')}}"></script>
<script src="{{asset('packages/jqplot/jqplot.canvasAxisTickRenderer.min.js')}}"></script>
<script src="{{asset('packages/jqplot/jqplot.canvasTextRenderer.min.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/jqplot/jqplot.highlighter.min.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{asset('packages/jqplot/jquery.jqplot.min.css')}}" media="screen" />

<script type="text/javascript">
$(document).ready(function(){
	data = [
		@foreach($poll->answers as $answer) ['{{$answer->title}}', {{$answer->votes}}], @endforeach
	];
	var total = 0;
    $(data).map(function(){total += this[1];})
    myLabels = $.makeArray($(data).map(function(){return '<span style="font-size:15px; margin:0; padding:0;">' + this[0] + '</span><br /><span style="font-size:11px;">' + this[1] + " гласа (" + Math.round(this[1]/total * 100) + "%) </span>";}));
    var opts =  {
    	seriesColors: [  "#668dc2", "#8aa3cc", "#a3b5d4", "#b9c6dd", "#ccd5e6", "#33567f", "#3a6190", "#416c9f", "#4774ab", "#4c7db7" ],
        gridPadding: {top:0, bottom:38, left:0, right:0},
        seriesDefaults:{
			shadow: false,
            renderer:$.jqplot.PieRenderer, 
            trendline:{ show:false }, 
            rendererOptions: { padding: 8, showDataLabels: true, dataLabels: myLabels, sliceMargin: 4,}
        },
		highlighter: {
		  show: true,
			formatString:'%s - %P',
		  tooltipLocation:'n', 
		  sizeAdjust: 14,
		  useAxesFormatters:false
		},
		grid: {
		    drawGridLines: true,        // wether to draw lines across the grid or not.
		    gridLineColor: '#fff',   // *Color of the grid lines.
		    background: 'transparent',      // CSS color spec for background color of grid.
		    borderColor: 'none',     // CSS color spec for border around grid.
		    borderWidth: 0,           // pixel width of border around grid.
		    shadow: true,               // draw a shadow for grid.
		    shadowAngle: 45,            // angle of the shadow.  Clockwise from x axis.
		    shadowOffset: 0,          // offset from the line of the shadow.
		    shadowWidth: 0,             // width of the stroke for the shadow.
		    shadowDepth: 0,             // Number of strokes to make when drawing shadow.
		                                // Each stroke offset by shadowOffset from the last.
		    shadowAlpha: 0.0,          // Opacity of the shadow
		    renderer: $.jqplot.CanvasGridRenderer,  // renderer to use to draw the grid.
		    rendererOptions: {}         // options to pass to the renderer.  Note, the default
		                                // CanvasGridRenderer takes no additional options.
		},
		legend:{ show: true , placement: 'outside', location: 's', marginTop: '40px', labels: myLabels, rendererOptions: {  numberRows: 1 } },
    };
	plot1 = jQuery.jqplot ('answers', [data], opts);
});	
</script>
@stop
