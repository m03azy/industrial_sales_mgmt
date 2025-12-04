<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f7f7f7; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Invoice: {{ $order->order_number }}</h2>
    <p>{{ optional($order->customer)->company_name ?? 'N/A' }}<br>{{ optional($order->customer)->contact_person ?? '' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th style="width:80px">Qty</th>
                <th style="width:120px">Unit</th>
                <th style="width:120px">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Deleted product' }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:20px;" class="text-right">
        <p>Subtotal: ${{ number_format($order->total_amount, 2) }}</p>
        <h3>Total Due: ${{ number_format($order->total_amount, 2) }}</h3>
    </div>
</body>
</html>
