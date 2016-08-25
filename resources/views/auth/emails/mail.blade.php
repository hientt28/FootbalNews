<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>{{ trans('message.title') }}</h2>

<div>
    {!! trans('message.content') !!}
    <br/>
    <!-- {{ URL::to('register/verify/' . $confirmation_code) }}.<br/> -->
    <a href="{{ route('user.active', $confirmation_code) }}"> {{ trans('message.link') }} </a>

</div>

</body>
</html>