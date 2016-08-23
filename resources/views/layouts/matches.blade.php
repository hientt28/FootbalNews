@extends('layouts.app')

@section('content')
    	
	<div class="page-content">
        @include('layouts.result')
        <h2 class="ui blue header">Matches list</h2>
        <div id="jqxgrid"></div>
        @include('layouts.map')
        @include('layouts.menu', ['id' => 'Menu'])
        @can('is_user', Auth::user())
        	@include('user.match.usermatch')
        @endcan
	   @yield('grid')
    </div>		

@endsection