@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 body-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('admin.seasons.list') }}
                </div>

                <div class="panel-body">
                    <div>
                        <div class="col-md-6">
                            {!! Html::decode(link_to_route(
                                'admin.seasons.create',
                                '<i class="fa fa-plus fa-fw"></i> ' . trans('app.button.create'),
                                '',
                                ['class' => 'btn btn-success']
                            )) !!}

                            <a id="btn_del_season" class="btn btn-danger">
                                <i class="fa fa-trash-o fa-fw"></i> {{ trans('app.button.delete_multi') }}
                            </a>
                        </div>

                        <div class="col-md-6">
                            {!! Form::open(['url' => 'admin/seasons', 'method' => 'GET']) !!}

                            <div class="col-md-8 col-md-offset-1">
                                {!! Form::text('search', old('search'),
                                ['id' => 'search', 'class' => 'form-control', 'placeholder' => trans('placeholder.search')]) !!}
                            </div>

                            {!! Form::button('<i class="fa fa-search fa-fw"></i> ' . @trans('app.button.search'),
                            ['type' => 'submit', 'class' => 'btn btn-primary col-md-3']) !!}

                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div id="data_grid" class="dataTable_wrapper data_list">
                        @include('admin.partials.list_season', ['listSeasons' => $listSeasons])
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
