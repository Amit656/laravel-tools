<table>
    <thead>
    <tr>
        <th>S No.</th>
        <th>Tool</th>
        <th>Tool Number</th>
        <th>Modality</th>
        <th>Site</th>
        <th>Serial Number</th>
        <th>Asset</th>
        <th>Calibration Due Date</th>
        <th>Due In Days</th>
        <th>Calibration Date</th>
        <th>Sort Field</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @php 
        $count = 0;
    @endphp
    @foreach($tools as $key => $tool)
        <tr>
            <td>{{ ++$count }}</td>
            <td>{{ $tool->description }}</td>
            <td>{{ $tool->tool_id }}</td>
            <td>{{ $tool->modalityName }}</td>
            <td>{{ $tool->siteName }}</td>
            <td>{{ $tool->serial_no }}</td>
            <td>{{ $tool->product_no }}</td>
            <td>{{ $tool->calibration_date }}</td>
            @if($tool->due_in_days < 1)
            <td>0</td>
            @else
            <td>{{ $tool->due_in_days }}</td>
            @endif
            @if($tool->calibrationReport)
            <td>{{ $tool->calibrationReport->calibrated_on }}</td>
            @else
            <td>N/A</td>
            @endif
            <td>{{ $tool->sort_field }}</td>
            <td>{{ $tool->status }}</td>
        </tr>
    @endforeach
    </tbody>
</table>