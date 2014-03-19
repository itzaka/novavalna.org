@extends('admin.layouts.master')


@section('content')
<section id="page">
	<h1 class="columns">Редакция на анкета <strong>{{$poll->title}}</strong></h1>
	{{Form::open(array('url' => 'admin/polls/'.$poll->id, 'method' => 'put', 'data-abide' => 'data-abide'))}}
	<div class="large-9 medium-8 large-push-3 medium-push-4 columns">
		<div class="row">
			<div class="medium-12 columns">
				{{Form::label('title', 'Въпрос')}}
				{{Form::text('title', $poll->title, array('id' => 'title', 'placeholder'=>'Въпрос', 'required' => 'required'))}}
				<small class="error">Моля добавете Въпрос</small>
			</div>
		</div>
		<div class="row">
			<div class="medium-12 columns">
				{{Form::label('answers', 'Отговори')}}
				<table id="answers-table" class="small-12">
					@foreach($poll->answers as $answer)
					<tr id="{{$answer->id}}">
						<td>
							{{Form::text('answers[]', $answer->title, array('class' => 'update-ajax', 'id' => 'answer-'.$answer->id, 'placeholder'=>'Отговор', 'required' => 'required'))}}
							<small class="error">Моля добавете отговор</small>
						</td>
						<td>
							<a class='dark vote'><i class="fi-check"></i></a>
							<a class='dark remove-ajax'><i class="fi-x"></i></a>
						</td>
					</tr>
					@endforeach
				</table>
			</div>
			<div class="medium-4 columns">
				<a class="button add-ajax tiny right"><i class="fi-plus"></i> Добави отговор</a>
			</div>
		</div>

	</div>
	<div class="large-3 medium-4 large-pull-9 medium-pull-8 columns">
		<div class="row collapse hide-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin/polls')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>
		<hr>
		@if($editor)
		<div class="row">
			<div class="medium-12 columns">
				<?php $status = $poll->public ? true : false;?>
				<label>{{Form::checkbox('public', 1, $status)}} Публикуване</label>
			</div>
		</div>
		<div class="row">
			<div class="medium-12 columns">
				<label>
					Добавена: <?php $date = new Date($poll->created_at);?>{{$date->format('Y M dS H:i:s')}}
				</label>
			</div>
		</div>
		<div class="row">
			<div class="medium-12 columns">
				<label>
					Променена: <?php $date = new Date($poll->updated_at);?>{{$date->format('Y M dS H:i:s')}}
				</label>
			</div>
		</div>
		<br>
		@endif

		<div class="row collapse">
			<div class="medium-6 columns">
				{{Form::label('language', 'Език')}}
				{{Form::select('language', $languages)}}
			</div>
			<div class="medium-5 columns">
				{{Form::label('order', 'Подредба')}}
				{{Form::text('order', 999, array('placeholder'=>'Подредба', 'required' => 'required'))}}
				<small class="error">Моля добавете Подредба</small>
			</div>
		</div>
		<div class="row collapse show-for-small">
			<div class="medium-7 medium-push-5 columns">
				{{Form::submit('Запази', array('class'=>'button tiny expand'))}}
			</div>
			<div class="medium-5 medium-pull-7 columns">
				<a href="{{URL::to('admin/polls')}}" class="button tiny secondary expand">Откажи</a>
			</div>
		</div>
	</div>
	{{Form::close()}}

</section>
@stop
@section('scripts')
	@include('admin.polls.scripts')

@stop
