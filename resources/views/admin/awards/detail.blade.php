@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 body-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('admin.awards.detail') }}
                </div>

                {!! Form::open(['class' => 'form-horizontal', 'id' => 'formDialog']) !!}
                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('description', $award['description'], ['class' => 'col-md-12']) !!}
                    </div>

                    <div>
                        <table class="table table-bordered" style="margin-top: 1%" id="tblData">
                            <thead>
                            <tr>
                                <th>{{ trans('admin.player') }}</th>
                                <th>{{ trans('admin.league') }}</th>
                                <th>{{ trans('admin.season') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($bestPlayers) > 0)
                                @foreach($bestPlayers as $bestPlayer)
                                    <tr>
                                        <td>{{ $bestPlayer->player->name }}</td>
                                        <td>{{ $bestPlayer->leagueSeason->league->name }}</td>
                                        <td>{{ $bestPlayer->leagueSeason->season->start }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="pull-right">
                        {!! link_to_route('admin.awards.index', trans('app.button.ok'), '', ['class' => 'btn btn-default']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
