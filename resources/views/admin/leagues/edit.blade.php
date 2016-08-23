@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
@stop

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 body-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('admin.leagues.edit') }}
                </div>

                {!! Form::open([
                    'route' => ['admin.leagues.update', $league['id']],
                    'method' => 'PUT',
                    'class' => 'form-horizontal',
                    'id' => 'formDialog',
                    'files' => true
                ]) !!}
                <div class="panel-body">
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        {!! Form::label('name', trans('admin.name'), ['class' => 'col-md-2']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('name', $league['name'], [
                                'class' => 'form-control',
                                'placeholder' => trans('common.placeholder.name')
                            ]) !!}

                            @if ($errors->has('name'))
                                <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('logo', trans('admin.leagues.logo'), ['class' => 'col-md-2']) !!}
                        <div class="col-sm-10">
                            {!! Html::image($league->logo, null, ['class' => 'img-responsive img logo_league_edit']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                    <div class="col-sm-3 col-lg-offset-2">
                        {!! Form::file('logo', ['class' => 'form-control']) !!}
                    </div>
                    </div>

                    <div class="form-group {{ $errors->has('country_id') ? ' has-error' : '' }}">
                        {!! Form::label('country_id', trans('admin.leagues.country'), ['class' => 'col-md-2']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('country_id', $countries, $league['country_id'], [
                                'class' => 'form-control',
                                'placeholder' => trans('placeholder.select_country')
                            ]) !!}

                            @if ($errors->has('country_id'))
                                <span class="help-block"><strong>{{ $errors->first('country_id') }}</strong></span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('description', trans('admin.description'), ['class' => 'col-md-2']) !!}
                        <div class="col-sm-10">
                            {!! Form::textarea('description', $league['description'], [
                                'class' => 'form-control',
                                'rows' => '2',
                                'placeholder' => trans('common.placeholder.description')
                            ]) !!}
                        </div>
                    </div>

                    <div class="pull-right">
                        {!! link_to_route(
                            'admin.leagues.index',
                            trans('app.button.cancel'),
                            '',
                            ['class' => 'btn btn-default']
                        ) !!}

                        {!! Form::button(
                            trans('app.button.save'),
                            ['class' => 'btn btn-primary', 'type' => 'submit']
                        ) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
