<div class="form-group, col-md-11">
    {!! Form::label('name', trans('team.name'), ['class' => 'control-label']) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'autofocus']) !!}
</div>

<div class="form-group, col-md-11">
    {!! Form::label('logo', trans('team.logo'), [ 'class' => 'control-label'  ]) !!}
    {!! Form::file('logo', ['class' => 'form-control']) !!}
</div>

<div class="form-group, col-md-11">
    {!! Form::label('description', trans('team.desciption'), ['class' => 'control-label']) !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'autofocus', 'rows' => '3']) !!}
</div>

<div class="form-group, col-md-11">
    {!! Form::label('country_id', trans('team.country'), ['class' => 'control-label']) !!}
    {!! Form::select('country_id', $country, null, ['class' => 'form-control', 'autofocus', 'placeholder' => trans('team.choose')]) !!}
</div>