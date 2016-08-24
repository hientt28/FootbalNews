@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-info">
                <div class="panel-heading">{{ trans('user.my_profile') }}</div>

                <div class="panel-body form-horizontal">
                    @include('common.error')
                    @include('common.flash_message')
                    <div class="form-group">
                        {!! Form::label('name', trans('user.name'), ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('name', $user->name, ['class' => 'form-control', 'disabled']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', trans('user.email'), ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('email', $user->email, ['class' => 'form-control', 'disabled']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('address', trans('user.address'), ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('address', $user->address, ['class' => 'form-control','disabled']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('avatar', trans('user.avatar'), ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-6">
                            <img src="{{ url(isset($user['avatar']) ? $user['avatar'] : config('common.user.avatar_default')) }}" class="img-thumbnail img-row">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-7 col-md-offset-3">
                            <a class="btn btn-info" href="{{ route('users.profile.edit', $user->id) }}">
                                {{ trans('user.update_profile') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
