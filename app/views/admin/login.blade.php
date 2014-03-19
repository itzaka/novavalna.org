@extends('layouts.master')
@section('content')
<!-- Primary left -->
<div id="primary-ful-width">
	<!-- Blank page -->
    <div class="blank-page-container">
		<h5>Регистрация</h5>
		<hr>
    	<div class="col1"></div>
		<div class="col4">
			{{ Confide::makeSignupForm()->render() }}
		</div>
		<div class="col1"></div>

    	<div class="col5 right last">
    		<a href="{{url('user/login/fb')}}" class="button large blue"><i class="icon-facebook"></i> Вход чрез facebook профил</a>
    	</div>
		<div class="clear"></div>
	</div>
</div>
@stop