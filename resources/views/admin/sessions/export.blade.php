<table class="kt-datatable" id="html_table" width="100%">
    <thead>
    <tr>
        <th title="Field #1">Session ID</th>
        <th title="Field #1">Gym</th>
        <th title="Field #2">User</th>
        <th title="Field #3">Spend Minutes</th>
        <th title="Field #4">Total Amount</th>
        <th title="Field #5">Session Status</th>
        <th title="Field #5">Session Start Time</th>
        <th title="Field #5">Session End Time</th>
        <th title="Field #6">Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sessions as $session)
        <tr>
            <td>{{ $session->id }}</td>
            <td>{{ ($session->gym) ? $session->gym->name : '' }}</td>
            <td>{{ ($session->user) ? $session->user->first_name : '' }}</td>
            <td>{{ $session->time_spend_minutes }}</td>
            <td>{{ $session->total_amount }}</td>
            <td align="right">{{ $session->status }}</td>
            <td align="right">{{ $session->start_datetime }}</td>
            <td align="right">{{ $session->end_datetime }}</td>
            <td align="right">{{ date('d M Y H:i:s', strtotime($session->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
