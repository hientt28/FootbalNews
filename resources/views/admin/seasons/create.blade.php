@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 body-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('admin.seasons.create') }}
                </div>

                {!! Form::open([
                    'url' => 'admin/seasons',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'id' => 'formDialog'
                ]) !!}
                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('start', trans('admin.seasons.start'), ['class' => 'col-md-3']) !!}
                        <div class="col-sm-3">
                            {{ Form::selectRange('start', config('common.year.start'), config('common.year.end')) }}
                        </div>

                        {!! Form::label('end', trans('admin.seasons.end'), ['class' => 'col-md-3']) !!}
                        <div class="col-sm-3">
                            {{ Form::selectRange('end', config('common.year.start'), config('common.year.end')) }}
                        </div>
                    </div>

                    <div>
                        <table class="table table-bordered" style="margin-top: 1%" id="tblData">
                            <thead>
                            <tr>
                                <th class="th_chk"><input type="checkbox" id="checkAll"></th>
                                <th class="col-md-4">{{ trans('admin.leagues.logo') }}</th>
                                <th class="col-md-4">{{ trans('admin.league') }}</th>
                                <th class="col-md-4">{{ trans('admin.leagues.country') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($leagues) > 0)
                                @foreach($leagues as $league)
                                    <tr>
                                        <td class="chk">
                                            <input type="checkbox" name="league[]" class="case" value="{{  $league->id }}"/>
                                        </td>
                                        <td class="col-md-4">
                                            <a href="{{ route('admin.leagues.show', $league->id) }}">
                                                {!! Html::image($league->logo, null, ['class' => 'img-responsive img']) !!}
                                            </a>
                                        </td>
                                        <td class="col-md-4">{{ $league->name }}</td>
                                        <td class="col-md-4">{{ $league->country->name }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="pull-right">
                        {!! link_to_route(
                            'admin.seasons.index',
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
