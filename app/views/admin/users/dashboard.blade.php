@extends('admin.layouts.master')


@section('content')
<section id="page">
	<div class="small-12 columns">
		<h3>Привет, {{$user->first_name}} {{$user->last_name}}</h3>
		@if(Session::get('message'))
		<div data-alert class="alert-box warning radius">
  			{{Session::get('message')}}
  			<a href="#" class="close">&times;</a>
		</div>
		@endif
	</div>
	@if(Entrust::can('create_posts'))
	<section>
		<div class="columns">
			<h3>Публикации</h3>
		</div>
		@if(Entrust::can('create_about'))
		<div class="large-3 medium-4 small-6 columns">
			<div class="panel @if($about_total) callout @endif">
				<h3>За нас</h3>
				<p class="subheader"><a href="{{url('admin/posts?type=about')}}">Мои публикации ( {{$about_total}} )</a></p>
			</div>
		</div>
		@endif
		@if(Entrust::can('create_news'))
		<div class="large-3 medium-4 small-6 columns">
			<div class="panel @if($news_total) callout @endif">
				<h3>Новини <small></small></h3>
				<p class="subheader"><a href="{{url('admin/posts?type=news')}}">Мои публикации ( {{$news_total}} )</a></p>
			</div>
		</div>
		@endif
		@if(Entrust::can('create_events'))
		<div class="large-3 medium-4 small-6 columns">
			<div class="panel @if($events_total) callout @endif">
				<h3>Събития</h3>
				<p class="subheader"><a href="{{url('admin/posts?type=events')}}">Мои публикации ( {{$events_total}} )</a></p>
			</div>
		</div>
		@endif
		@if(Entrust::can('create_activities'))
		<div class="large-3 medium-4 small-6 columns">
			<div class="panel @if($activities_total) callout @endif">
				<h3>Дейности</h3>
				<p class="subheader"><a href="{{url('admin/posts?type=activities')}}">Мои публикации ( {{$activities_total}} )</a></p>
			</div>
		</div>
		@endif
		@if(Entrust::can('create_summercamp'))
		<div class="large-3 medium-4 small-6 columns">
			<div class="panel @if($summer_camp_total) callout @endif">
				<h3>Летен лагер</h3>
				<p class="subheader"><a href="{{url('admin/posts?type=summer-camp')}}">Мои публикации ( {{$summer_camp_total}} )</a></p>
			</div>
		</div>
		@endif
		@if(Entrust::can('create_vlog'))
		<div class="large-3 medium-4 small-6 left columns">
			<div class="panel @if($vlog_total) callout @endif">
				<h3>Видео блог</h3>
				<p class="subheader"><a href="{{url('admin/posts?type=vlog')}}">Мои публикации ( {{$vlog_total}} )</a></p>
			</div>
		</div>
		@endif
		<div class="row"></div>
	</section>
	@endif
	@if(Entrust::can('create_categories'))
	<section>		
		<div class="columns">
			<h3>Категории</h3>
			<p class="subheader"><a href="{{URL::to('admin/categories')}}">Добави категории</a></p>
		</div>
		<div class="row"></div>
	</section>
	@endif
	<!-- IMAGES -->
	<section>
		@if(Entrust::can('create_photos'))
		<div class="medium-4 columns">
			<h3>Снимки</h3>
			<p class="subheader"><a href="{{URL::to('admin/categories')}}">Добави снимки</a></p>
		</div>
		@endif
		@if(Entrust::can('create_banners'))
		<div class="medium-4 columns">
			<h3>Банери</h3>
			<p class="subheader"><a href="{{URL::to('admin/banners')}}">Мои банери ( {{$banners_total}} )</a></p>
		</div>
		@endif
		@if(Entrust::can('create_polls'))
		<div class="medium-4 columns">
			<h3>Анкети</h3>
			<p class="subheader"><a href="{{URL::to('admin/polls')}}">Мои анкети ( {{$polls_total}} )</a></p>
		</div>
		@endif
		<div class="row"></div>
	</section>
	@if(Entrust::can('manage_users'))
	<section>		
		<div class="columns">
			<h3>Потребители <small>( {{$users_total}} )</small></h3>
				<table>
					<tfoot>
						<tr>
							<td colspan="3">Последно регистрирани</td>
						</tr>
					</tfoot>
					<tbody>
						@foreach($users as $user)
						<tr id="{{$user->id}}">
							<td>
								<a class="dark" href="{{URL::to('admin/users/'.$user->id.'/edit')}}">{{$user->first_name}} {{$user->last_name}}</a>
							</td>
							<td>({{$user->username}})</td>
							<td class="date"><?php $date = new Date($user->created_at);?>{{$date->format('Y M dS')}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
		</div>
		<div class="row"></div>
	</section>
	@endif
</section>
@stop
