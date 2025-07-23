<!DOCTYPE html>
<html>
<head>
    <title>Supervisor-Student Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    
@php
    $fypLabel = $semester == 'Semester 1' ? 'FYP1' : 'FYP2';
@endphp

<h1>Supervisor-Students Report ({{ $year }} - {{ $fypLabel }})</h1>

@foreach($registrations as $supervisorId => $items)
    @php
        $supervisor = $items->first()->supervisor;
    @endphp

    <h2>Supervisor: {{ $supervisor->name }} ({{ $supervisor->email }})</h2>

    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $reg)
                <tr>
                    <td>{{ $reg->student->student_id }}</td>
                    <td>{{ $reg->student->name }}</td>
                    <td>{{ $reg->student->email }}</td>
                    <td>{{ ucfirst($reg->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach

</body>
</html>
