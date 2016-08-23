<table class="table table-striped table-hover" id="tblData">
    <thead>
    <tr>
        <th class="th_chk"><input type="checkbox" id="checkAll"></th>
        <th class="col-md-3">{{ trans('admin.leagues.logo') }}</th>
        <th class="col-md-2">{{ trans('admin.name') }}</th>
        <th class="col-md-2">{{ trans('admin.leagues.country') }}</th>
        <th class="col-md-2">{{ trans('admin.updated_at') }}</th>
        <th class="th_action" colspan="3">{{ trans('admin.action') }}</th>
    </tr>
    </thead>
    <tbody>
    @if(count($listLeagues) > 0)
        @foreach($listLeagues as $league)
            <tr id="{{  $league->id }}">
                <td class="chk">
                    <input type="checkbox" class="case" value="{{  $league->id }}"/>
                </td>
                <td class="col-md-3">
                    <a href="{{ route('admin.leagues.show', $league->id) }}">
                        {!! Html::image($league->logo, null, ['class' => 'img-responsive img logo_league']) !!}
                    </a>
                </td>
                <td class="col-md-2">{{ $league->name }}</td>
                <td class="col-md-2">{{ $league->country->name }}</td>
                <td class="col-md-2">{{ $league['updated_at_status'] }}</td>

                <td class="col-md-1 td_action">
                    {!! Html::decode(link_to_route(
                        'admin.leagues.show',
                        '<i class="fa fa-th-list fa-fw"></i> ' . trans('app.button.detail'),
                        [ $league->id],
                        ['class' => 'btn btn-link']
                    )) !!}
                </td>

                <td class="col-md-1 td_action">
                    {!! Html::decode(link_to_route(
                        'admin.leagues.edit',
                        '<i class="fa fa-pencil fa-fw"></i> ' . trans('app.button.edit'),
                        [ $league->id],
                        ['class' => 'btn btn-link']
                    )) !!}
                </td>

                <td class="col-md-1 td_action">
                    {!! Form::open(['route' => ['admin.leagues.destroy',  $league->id], 'method' => 'DELETE']) !!}
                    {!! Form::button('<i class="fa fa-remove fa-fw"></i> ' . trans('app.button.delete'), [
                        'type' => 'submit',
                        'class' => 'btn btn-link',
                        'onclick' => "return confirm('" . trans('admin.confirm.delete') . "')"
                    ]) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

<div id="pagination" class="pull-right">
    @if(count($listLeagues) > 0)
        {{ $listLeagues->render() }}
    @endif
</div>