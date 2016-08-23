@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
@stop

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 body-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('admin.awards.edit') }}
                </div>

                {!! Form::open(['route' => ['admin.awards.update', $award['id']], 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formDialog']) !!}
                <div class="panel-body">
                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        {!! Form::label('description', trans('admin.description'), ['class' => 'col-md-2']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('description', $award['description'], [
                                'class' => 'form-control',
                                'placeholder' => trans('placeholder.description')
                            ]) !!}

                            @if ($errors->has('description'))
                                <span class="help-block"><strong>{{ $errors->first('description') }}</strong></span>
                            @endif
                        </div>
                    </div>

                    <div class="pull-right">
                        {!! link_to_route('admin.awards.index', trans('app.button.cancel'), '', ['class' => 'btn btn-default']) !!}

                        {!! Form::button(trans('app.button.save'), ['class' => 'btn btn-primary', 'type' => 'submit']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
