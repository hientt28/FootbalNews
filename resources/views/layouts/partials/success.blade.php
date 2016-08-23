@if (Session::has('success'))
    <div class="col-xs-6 col-xs-offset-3">
        <div class="alert alert-success success_show">
            <button class="close" type="button" data-dismiss="alert">&times;</button>
            <strong>
                <i class="fa fa-check-circle fa-lg fa-fw"></i> {{ trans('session.success') }} &nbsp;
            </strong>
            {{ Session::get('success') }}
        </div>
    </div>
@endif