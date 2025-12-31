<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>

    <h2>Invoice #{{ $order->id }}</h2>

    <p>
        <strong>Customer Name:</strong> {{ $order->full_name }} <br>
        <strong>Mobile:</strong> {{ $order->mobile }} <br>
        <strong>Address:</strong> {{ $order->address }} <br>
        <strong>Status:</strong> {{ ucfirst($order->status) }}
    </p>

    <hr>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->title }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }} EGP</td>
                    <td>{{ $item->quantity * $item->price }} EGP</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total: {{ number_format($order->total_price, 2) }} EGP</h3>

</body>

</html>
