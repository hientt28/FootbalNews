@extends('layouts.app')

@section('content')
	
	<div class="container page-content">
    	<div class="row">
    	 <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default ">
                <div class="panel-heading"></div>

                <div class="panel-body ui fluid">
                    <div id="jqxgrid"></div>
                </div>
            </div>
        </div>
    	@yield('grid')
    	</div>
    </div>		

@endsection