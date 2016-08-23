<table class="table table-striped table-hover" id="tblData">
    <thead>
    <tr>
        <th class="th_chk"><input type="checkbox" id="checkAll"></th>
        <th class="col-md-6">{{ trans('admin.description') }}</th>
        <th class="col-md-3">{{ trans('admin.updated_at') }}</th>
        <th class="th_action" colspan="3">{{ trans('admin.action') }}</th>
    </tr>
    </thead>
    <tbody>
    @if(count($listAwards) > 0)
        @foreach($listAwards as $award)
            <tr id="{{ $award['id'] }}">
                <td class="chk">
                    <input type="checkbox" class="case" value="{{ $award['id'] }}"/>
                </td>
                <td class="col-md-6">{{ $award['description'] }}</td>
                <td class="col-md-3">{{ $award['updated_at_status'] }}</td>

                <td class="col-md-1 td_action">
                    {!! Html::decode(link_to_route(
                        'admin.awards.show',
                        '<i class="fa fa-th-list fa-fw"></i> ' . trans('app.button.detail'),
                        [$award['id']],
                        ['class' => 'btn btn-link']
                    )) !!}
                </td>

                <td class="col-md-1 td_action">
                    {!! Html::decode(link_to_route(
                        'admin.awards.edit',
                        '<i class="fa fa-pencil fa-fw"></i> ' . trans('app.button.edit'),
                        [$award['id']],
                        ['class' => 'btn btn-link']
                    )) !!}
                </td>

                <td class="col-md-1 td_action">
                    {!! Form::open(['route' => ['admin.awards.destroy', $award['id']], 'method' => 'DELETE']) !!}
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
    @if(count($listAwards) > 0)
        {{ $listAwards->render() }}
    @endif
</div>