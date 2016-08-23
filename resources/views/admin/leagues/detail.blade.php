@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 body-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('admin.leagues.detail') }}
                </div>

                {!! Form::open(['class' => 'form-horizontal', 'id' => 'formDialog']) !!}
                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('logo', trans('admin.leagues.logo'), ['class' => 'col-md-2']) !!}
                        <div class="col-sm-10">
                            {!! Html::image($league->logo, null, ['class' => 'img-responsive img']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name', trans('admin.name'), ['class' => 'col-md-2']) !!}
                        <div class="col-sm-4">
                            {!! Form::text('name', $league->name, [
                                'class' => 'form-control',
                                'disabled' => 'disabled'
                            ]) !!}
                        </div>

                        {!! Form::label('country', trans('admin.leagues.country'), ['class' => 'col-md-2']) !!}
                        <div class="col-sm-4">
                            {!! Form::text('country', $league->country->name, [
                                'class' => 'form-control',
                                'disabled' => 'disabled'
                            ]) !!}
                        </div>
                    </div>

                    <div>
                        <table class="table table-bordered" style="margin-top: 1%" id="tblData">
                            <thead>
                            <tr>
                                <th>{{ trans('admin.season') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($leagueSeasons) > 0)
                                @foreach($leagueSeasons as $leagueSeason)
                                    <tr>
                                        <td>{{ $leagueSeason->season->start }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="pull-right">
                        {!! link_to_route('admin.leagues.index', trans('app.button.ok'), '', ['class' => 'btn btn-default']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
