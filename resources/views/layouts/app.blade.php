<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('app.app_name') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://formvalidation.io/vendor/formvalidation/css/formValidation.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/semantic.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jqx.base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jqx.ui-redmond.css') }}" rel="stylesheet">
</head>
<body id="app-layout">
<div class="ui fixed inverted menu full height">
    <div class="main ui container">
        <a class="launch icon item" onclick="(function () {
                $('.ui.sidebar').sidebar('toggle');
            })()">
            <i class="content icon"></i>
            <!-- {{ trans('app.menu') }} -->
        </a>
        <a href="#" class="header item ct-header">
            {{ trans('app.app_name') }}
        </a>
        @if(!Auth::guest())
        <div class="ui search item">
            <div class="ui icon input">
                <input class="prompt" type="text" placeholder="Search...">
                <i class="search icon"></i>
            </div>
            <div class="results"></div>
        </div>
        @endif
        <div class="right menu">
            <a href="#" class="item"><i class="home icon"></i>&nbsp;{{ trans('app.home') }}</a>
            @if(Auth::guest())
                <a class="item" href="{{ url('/login') }}" class="ui inverted button"><i class="sign in icon"></i>&nbsp;
                    {{ trans('login.login') }}
                </a>
            @else
                @can('is_admin', Auth::user())
                    <a href="{{ route('admin.news.index') }}" class="item"><i class="newspaper icon"></i>&nbsp;{{ trans('app.new') }}</a>
                @else
                    <a href="{{ route('users.news.index') }}" class="item"><i class="newspaper icon"></i>&nbsp;{{ trans('app.new') }}</a>
                @endcan
                
                <div class="ui simple dropdown item">
                    {!! Html::image(Auth::user()->avatar ? Auth::user()->avatar : asset('images\man.png'), null , ['class' => 'avatar']) !!}
                    {{ Auth::user()->name }} <i class="dropdown icon"></i>
                    <div class="menu">
                        <a class="item" href="{{ url('/logout') }}" class="ui inverted button"><i
                                    class="sign out icon"></i>&nbsp; {{ trans('login.logout') }}</a>
                        <a class="item" href="{{ route('users.profile.show', [Auth::user()->id]) }}"><i class="user icon"></i>&nbsp;{{ trans('app.profile') }}</a>
                        <div class="ui right pointing dropdown link item">
                            <i class="dropdown icon"></i>
                            {{ trans('app.language') }}
                            <div class="menu">
                                <div class="item"><a href=" {{ route('lang', 'en') }} ">
                                    <i class="us flag"></i>
                                    {{ trans('app.english') }}</a>
                                </div>
                                <div class="item"><a href="{{ route('lang', 'vi') }}">
                                    <i class="vietnam flag"></i>
                                    {{ trans('app.vietnamese') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.notifications');
                <a href="#" class="item ui blue" onclick="notifications.displayMessage()">
                    <label style="margin:10px;" id="_message">0</label>
                    <i style="font-size:30px;position:absolute;color:red;" class="comment outline icon"></i>&nbsp;
                </a>
            @endif
        </div>
    </div>

    <div class="ui left vertical inverted labeled icon sidebar menu uncover">
       @can('is_admin', Auth::user())
       <a class="item" href="{{ route('admin.leagues.index') }}">
           <i class="flag icon"></i>&nbsp;
           {{ trans('app.league') }}
       </a>
       <a class="item" href="{{ route('admin.seasons.index') }}">
           <i class="flag icon"></i>&nbsp;
           {{ trans('app.season') }}
       </a>
        <a class="item" href="{{ route('admin.chart') }}">
            <i class="linux icon"></i>&nbsp;
            {{ trans('app.chart') }}
        </a>
        <a class="item" href="{{ route('admin.teams.index') }}">
            <i class="linux icon"></i>&nbsp;
            {{ trans('app.team') }}
        </a>
        <a class="item" href="{{ route('admin.players.index') }}">
            <i class="linux icon"></i>&nbsp;
            {{ trans('app.player') }}
        </a>
       <a class="item" href="{{ route('admin.matches.index') }}">
            <i class="soccer icon"></i>&nbsp;
            {{ trans('app.match') }}
        </a>
        <a class="item" href="{{ route('admin.awards.index') }}">
            <i class="flag icon"></i>&nbsp;
            {{ trans('app.award') }}
        </a>
       @else
            <a class="item">
                <i class="flag icon"></i>&nbsp;
                {{ trans('app.league') }}
            </a>
            <a class="item">
                <i class="linux icon"></i>&nbsp;
                {{ trans('app.team') }}
            </a>
            <a class="item" href="{{ route('users.matches.index') }}">
                <i class="soccer icon"></i>&nbsp;
                {{ trans('app.match') }}
            </a>
       @endcan
    </div>
    <div class="pusher">
    </div>
</div>
@if(!Auth::guest())
<div class="ui stackable grid">
    <div class="equal height row">
        <div class="two wide column">
        </div>
        <div class="twelve wide column">
            <div class="ui segment content">
                <div class="ui big breadcrumb">
                  <a class="section">Home</a>
                  <i class="right chevron icon divider"></i>
                  <a class="section">Matches List</a>
                  <i class="right chevron icon divider"></i>
                  <div class="active section">Personal Information</div>
                </div>
                @yield('content')
            </div>
        </div>
        <div class="two wide column">
            @can('is_admin', Auth::user())
                <div id="message" style="margin-top : 20%;z-index:9999999;display:none;">
                    <div class="field">
                        <img id="user-bet" src="{{ asset('images/man.png') }}" width="50" height="50"/>
                        <label class="msg-content"></label>
                    </div>
                </div>
            @else
                <div id="messageToUser" style="margin-top : 20%;z-index:9999999;">
                    <div class="field">
                        <label class="alert alert-info msg-user-content"></label>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>    
@else
    @yield('content')
@endif
@if(!Auth::guest())
<div class="ui black inverted vertical footer segment">
  <div class="ui center aligned container">
    <div class="ui stackable inverted grid">
      <div class="three wide column">
        <h4 class="ui inverted header">Company</h4>
        <div class="ui inverted link list">
          <a class="item" href="https://github.com/Semantic-Org/Semantic-UI" target="_blank">Framgia</a>
        </div>
      </div>
      <div class="seven wide right floated column">
        <h4 class="ui inverted teal header">Football News System</h4>
        <p> This is a football news page tell about news,transfer,result matches,v.v...</p>
      </div>
    </div>
  </div>
</div>
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/semantic.min.js') }}"></script>
<script src="{{ asset('js/jqx-all.js') }}"></script>
<script src="{{ asset('js/jquery.lazyload.js') }}" type="text/javascript"></script>
{{--<script src="https://cdn.socket.io/socket.io-1.3.4.js"></script> --}}
<script src="https://maps.googleapis.com/maps/api/js?json?&mode=transit&origin=frontera+el+hierro&destination=la+restinga+el+hierro&departure_time=1399995076&key=AIzaSyBY2xnVxwjLYhuBNmhiMDUExm-vpUBa-IY&&libraries=places&callback=app.initMap"p
         async defer></script>
</body>
</html>
