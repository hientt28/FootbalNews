@if (session()->has('message'))
    <div class="flash alert-info">
        <p class="panel-body">
            {{ session('message') }}
        </p>
    </div>
@endif
