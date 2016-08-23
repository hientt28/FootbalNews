@extends('layouts.app')

@section('content')
    <div class="container page-content">
        <div class="row">
            <div class="col-md-8 col-md-offset-1">
                <div class="container-fluid">
                    <div class="row page-title-row">
                        <div class="col-md-12">
                            <h3>{{ trans('player.player') }} <small>&raquo; {{ trans('common.edit') }}</small></h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">{{ trans('common.edit') }}</h3>
                            </div>
                            <div class="panel-body">
                                <div>
                                    @include('layouts.partials.error')
                                    @include('layouts.partials.success')
                                </div>
                                {!! Form::model($players, ['method' => 'PUT', 'route' => ['admin.players.update', $players->id], 'class' => 'form-horizontal', 'files'=> true]) !!}
                                @include('admin.player._form')
                                <div class="form-group, col-md-11">
                                    {!! Form::button('<i class="fa fa-plus-circle"></i>&nbsp;' . trans('common.edit'), ['type' => 'submit', 'class' => 'btn btn-primary btn-md']) !!}
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop