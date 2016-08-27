@extends('layouts.app')

@section('content')
    <div class="container page-content">
        <div class="row">
            <div class="col-md-10 col-md-offset-0">
                <div class="row page-title-row">
                    <div class="col-md-8">
                        <h3> {{ trans('common.chart_team') }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="dataTable_wrapper">
                                    <div id="pop_div">
                                        <div class="pop_div">
                                            {!! Lava::render('ColumnChart', 'Team', 'pop_div') !!}
                                        </div>
                                    </div>
                                    <div id="userbest_div">
                                        <div class="userbest_div">
                                            {!! Lava::render('PieChart', 'UserMatch', 'userbest_div') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop