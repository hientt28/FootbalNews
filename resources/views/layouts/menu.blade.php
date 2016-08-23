<div id='{{ $id }}'>
    <ul>
        @can('is_admin', Auth::user())
        <li type="add"><i class="plus icon"></i>Add New Row</li>
        <li type="edit"><i class="edit icon"></i>Edit Selected Row</li>
        <li type="delete"><i class="trash icon"></i>Delete Selected Row</li>
        <li type="bet" onclick="app.betMatch('jqxgrid')">
            <i class="dollar icon"></i>Bet a Match</li>
        @endcan
        <li type="show"><i class="arrow circle right icon"></i> Show Row Detail</li>
        <li type="map" 
            onclick="app.viewLocation('jqxgrid')">
            <i class="map icon"></i>
            View Location with Maps
        </li>
        <li type="export">
            Export
            <ul>
                <li type="pdf"><i class="file pdf outline icon"></i> PDF</li>
                <li type="excel"><i class="file excel outline icon"></i> Excel</li>
                <li type="csv"><i class="file archive outline icon"></i> CSV</li>
            </ul>
        </li>
    </ul>
</div>