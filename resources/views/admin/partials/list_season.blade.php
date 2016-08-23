<table class="table table-striped table-hover" id="tblData">
    <thead>
    <tr>
        <th class="th_chk"><input type="checkbox" id="checkAll"></th>
        <th class="col-md-3">{{ trans('admin.seasons.start') }}</th>
        <th class="col-md-3">{{ trans('admin.seasons.end') }}</th>
        <th class="col-md-3">{{ trans('admin.updated_at') }}</th>
        <th class="th_action" colspan="3">{{ trans('admin.action') }}</th>
    </tr>
    </thead>
    <tbody>
    @if(count($listSeasons) > 0)
        @foreach($listSeasons as $season)
            <tr id="{{ $season['id'] }}">
                <td class="chk">
                    <input type="checkbox" class="case" value="{{ $season['id'] }}"/>
                </td>
                <td class="col-md-3">{{ $season['start'] }}</td>
                <td class="col-md-3">{{ $season['end'] }}</td>
                <td class="col-md-3">{{ $season['updated_at_status'] }}</td>

                <td class="col-md-1 td_action">
                    {!! Html::decode(link_to_route(
                        'admin.seasons.show',
                        '<i class="fa fa-th-list fa-fw"></i> ' . trans('app.button.detail'),
                        [$season['id']],
                        ['class' => 'btn btn-link']
                    )) !!}
                </td>

                <td class="col-md-1 td_action">
                    {!! Html::decode(link_to_route(
                        'admin.seasons.edit',
                        '<i class="fa fa-pencil fa-fw"></i> ' . trans('app.button.edit'),
                        [$season['id']],
                        ['class' => 'btn btn-link']
                    )) !!}
                </td>

                <td class="col-md-1 td_action">
                    {!! Form::open(['route' => ['admin.seasons.destroy', $season['id']], 'method' => 'DELETE']) !!}
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
    @if(count($listSeasons) > 0)
        {{ $listSeasons->render() }}
    @endif
</div>