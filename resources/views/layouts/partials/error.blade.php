@if (count($errors) > 0)
    <div class="col-xs-6 col-xs-offset-3">
        <div class="alert alert-danger">
            <strong> {{ trans('session.whoops') }} </strong>
            <p> {{ trans('session.error') }} </p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif