@extends('layouts.app')

@section('content')
    <div class="container page-content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <section>
                    <div class="row page-title-row">
                        <div class="col-md-8">
                            <h3> {{ trans('player.player') }} <small>&raquo; {{ trans('player.list') }}</small></h3>
                            <p><a href="{{ route('admin.players.create') }}"
                                  class="btn btn-primary" role="button"><i class="fa fa-plus-circle"></i></a></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="dataTable_wrapper">
                                        <div id="pop_div"></div>
                                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                                            <thead>
                                            <tr>
                                                <th>{{ trans('player.name') }}</th>
                                                <th>{{ trans('player.position') }}</th>
                                                <th>{{ trans('player.team') }}</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($players as $player)
                                                <tr>
                                                    <td><a href="{{ route('admin.players.show', $player->id) }}">{{ $player->name }}</a></td>
                                                    <td>{{ $player->position->name }}</td>
                                                    <td>{{ $player->team->name }}</td>
                                                    <td><a href="{{ route('admin.players.edit',  $player->id) }}" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i></a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['admin.players.destroy', $player->id], 'class' => 'form-horizontal']) !!}
                                                        {!! Form::button( '<i class="fa fa-trash"></i>',['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                                        {!! Form::close() !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {{ $players->render() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@stop