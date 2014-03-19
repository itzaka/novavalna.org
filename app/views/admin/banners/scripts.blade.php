<script src="{{URL::asset('packages/ajax-file-upload/vendor/jquery.ui.widget.js')}}"></script>
<script src="{{URL::asset('packages/ajax-file-upload/jquery.iframe-transport.js')}}"></script>
<script src="{{URL::asset('packages/ajax-file-upload/jquery.fileupload.js')}}"></script>
<script src="{{asset('packages/vendor/spin.min.js')}}"></script>
<script type="text/javascript" src="{{asset('fancybox/js/jquery.mousewheel-3.0.6.pack.js')}}"></script>
<script type="text/javascript" src="{{asset('fancybox/js/jquery.fancybox.js?v=2.1.5')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('fancybox/css/jquery.fancybox.css?v=2.1.5')}}" media="screen" />
<link rel="stylesheet" type="text/css" href="{{asset('fancybox/css/jquery.fancybox-buttons.css?v=1.0.5')}}" />
<script type="text/javascript" src="{{asset('fancybox/js/jquery.fancybox-buttons.js?v=1.0.5')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".various").fancybox({
        fitToView   : false,
        width       : '90%',
        height      : '80%',
        autoSize    : false,
        closeClick  : false,
        openEffect  : 'none',
        closeEffect : 'none'
    });
});
</script>