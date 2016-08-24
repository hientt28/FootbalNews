@extends('layouts.app')

@section('content')
    <div class="container page-content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="container-fluid">
                    <div class="row page-title-row">
                        <div class="col-md-12 col-md-offset-1">
                            <h3>{{ trans('team.detail') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables">
                                        <tr>
                                            <td>{{ trans('team.logo') }}</td>
                                            <td>{!! Html::image($team->logo, null, ['class' => 'imgDetails']) !!}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ trans('team.name') }}</td>
                                            <td><h3>{{ $team->name }}</h3></td>
                                        </tr>
                                        <tr>
                                            <td>{{ trans('team.country') }}</td>
                                            <td>{{ $team->country->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ trans('team.desciption') }}</td>
                                            <td>{{ $team->description }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop