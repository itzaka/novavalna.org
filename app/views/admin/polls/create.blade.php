@extends('admin.layouts.master')


@section('content')
<section id="page">
	<h1 class="columns">Добавяне на Анкета</h1>
	{{Form::open(array('url' => 'admin/polls', 'data-abide' => 'data-abide'))}}
	<div class="large-9 medium-8 large-push-3 medium-push-4 columns">
		<div class="row">
			<div class="medium-12 columns">
				{{Form::label('title', 'Въпрос')}}
				{{Form::text('title', null, array('id' => 'title', 'placeholder'=>'Въпрос', 'required' => 'required'))}}
				<small class="error">Моля добавете Въпрос</small>
			</div>
		</div>
		<div class="row">
			<div class="medium-12 columns">
				{{Form::label('answers', 'Отговори')}}
				<table id="answers-table" class="small-12">
					<tr>
						<td>
							{{Form::text('answers[]', null, array('id' => 'answers', 'placeholder'=>'Отговор', 'required' => 'required'))}}
							<small class="error">Моля добавете отговор</small>
						</td>
						<td>
							<a class='dark remove right'><i class="fi-x"></i></a>
						</td>
					</tr>
				</table>
			</div>
			<div class="medium-4 columns">
				<a class="button add tiny right"><i class="fi-plus"></i> Добави отговор</a>
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
