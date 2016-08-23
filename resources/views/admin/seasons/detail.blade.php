@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 body-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('admin.seasons.detail') }}
                </div>

                {!! Form::open(['class' => 'form-horizontal', 'id' => 'formDialog']) !!}
                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('start', trans('admin.season'), ['class' => 'col-md-2']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('start', $season['start'], [
                                'class' => 'form-control',
                                'disabled' => 'disabled'
                            ]) !!}
                        </div>
                    </div>

                    <div>
                        <table class="table table-bordered" style="margin-top: 1%" id="tblData">
                            <thead>
                            <tr>
                                <th>{{ trans('admin.league') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($leagueSeasons) > 0)
                                @foreach($leagueSeasons as $leagueSeason)
                                    <tr>
                                        <td>{{ $leagueSeason->league->name }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="pull-right">
                        {!! link_to_route('admin.seasons.index', trans('app.button.ok'), '', ['class' => 'btn btn-default']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
