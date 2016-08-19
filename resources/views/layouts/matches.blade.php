@extends('layouts.app')

@section('content')
	
	<div class="page-content">
        @include('layouts.result')
        <h2 class="ui blue header">Matches list</h2>
        <div id="jqxgrid"></div>
        @include('layouts.map')
        @include('layouts.menu', ['id' => 'Menu'])
	   @yield('grid')
    </div>		

@endsection