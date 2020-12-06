<table class="kt-datatable" id="html_table" width="100%">
    <thead>
        <tr>
            <th title="Field #1">ID</th>
            <th title="Field #2">First Name</th>
            <th title="Field #3">Last Name</th>
            <th title="Field #4">Email</th>
            <th title="Field #5">Phone</th>
            <th title="Field #6">Status</th>
            <th title="Field #7">Actions</th>
            <th title="Field *8"></th>
        </tr>
    </thead>
    <tbody>
    	@foreach($records as $record)
		<tr>
		    <td>{{ $record->id }}</td>
		    <td align="right">{{ $record->first_name }}</td>
		    <td align="right">{{ $record->last_name }}</td>
		    <td align="right">{{ $record->email }}</td>
		    <td align="right">{{ $record->phone }}</td>
		    <td align="right">{{ $record->status }}</td>
		    <td align="right">
		        <a href="{{ route('hospital.'.$title.'.add', $record->id) }}" title="Add Doctor" class="btn btn-sm btn-clean btn-icon btn-icon-md">
		            <i class="la la-plus"></i>
		        </a>
		    </td>
		    <td abbr="right"></td>
		</tr>
		@endforeach
    </tbody>
</table>

<script src="{{ asset('public/assets/js/demo1/pages/crud/metronic-datatable/base/custom/user-table.js') }}" type="text/javascript"></script>