@extends('layouts.app')

@section("content")
    <div class="row">
        <div class="col-lg-12 col-md-12 body-content">
            <div class="page-content-wrapper">
                <h3 class="page-header"> {{ trans('user.edit_profile') }} </h3>
                <div class="row">
                    {!! Form::model($user, ['method' => 'PUT', 'route' => ['users.profile.update', $user->id], 'class' => 'form-horizontal', 'files' => true]) !!}
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="text-center">
                                {!! Form::image($user->avatar, null, ['id' => 'show_avatar']) !!}
                                <h6> {{ trans('user.avatar') }} </h6>
                                {!! Form::file('avatar', ['class' => 'text-center center-block well well-sm']) !!}
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12 personal-info">
                            <h4> {{ trans('user.information') }} </h4>
                            <div class="form-group">
                                {!! Form::label('name', trans('user.name'), ['class' => 'col-lg-3 control-label']) !!}
                                <div class="col-lg-8 input-group">
                                    <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    {!! Form::text('name', $user->name, ['class' => 'form-control', 'autofocus']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('address', trans('user.address'), ['class' => 'col-lg-3 control-label']) !!}
                                <div class="col-lg-8 input-group">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {!! Form::text('address', $user->address, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('email', trans('user.email'), ['class' => 'col-lg-3 control-label']) !!}
                                <div class="col-lg-8 input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                                    {!! Form::text('email', $user->email, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-8">
                                    {!! Form::button('<i class="fa fa-edit"></i>&nbsp;' . trans('user.update_profile'), [
                                        'type' => 'submit',
                                        'class' => 'btn btn-primary btn-md'
                                    ]) !!}
                                    @can('is_admin', Auth::user())
                                        {{ link_to_route('admin.profile.index', trans('user.cancel'), null, ['class' => 'btn btn-default']) }}
                                    @else
                                        {{ link_to_route('users.profile.index', trans('user.cancel'), null, ['class' => 'btn btn-default']) }}
                                    @endcan
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
