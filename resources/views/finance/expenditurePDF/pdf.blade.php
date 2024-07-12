<!DOCTYPE html>
<html>
<head>
    <title>Expenditures</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Expenditures</h2>
    <table>
        <thead>
            <tr>
                <th>Expenditure ID</th>
                <th>Expenditure Type</th>
                <th>Amount</th>
                <th>Time Recorded</th>
                <th>Time Paid</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenditures as $expenditure)
                <tr>
                    <td>{{ $expenditure->expenditureID }}</td>
                    <td>{{ $expenditure->expenditureType }}</td>
                    <td>{{ $expenditure->amount }}</td>
                    <td>{{ $expenditure->timeRecorded }}</td>
                    <td>{{ $expenditure->timePaid }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>