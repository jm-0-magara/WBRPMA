<!DOCTYPE html>
<html>
<head>
    <title>Payments</title>
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
    <h2>Payments</h2>
    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>House No</th>
                <th>Payment Type</th>
                <th>Amount</th>
                <th>Time Recorded</th>
                <th>Time Paid</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->paymentID }}</td>
                    <td>{{ $payment->houseNo }}</td>
                    <td>{{ $payment->paymentType }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ $payment->timeRecorded }}</td>
                    <td>{{ $payment->timePaid }}</td>
                    <td>{{ $payment->paymentMethod }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>