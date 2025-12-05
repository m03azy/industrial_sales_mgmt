<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 5px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            text-align: right;
            color: #333;
        }
        .invoice-details {
            text-align: right;
            margin-top: 10px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #4F46E5;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background: #4F46E5;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-table {
            width: 300px;
            margin-left: auto;
            margin-top: 20px;
        }
        .totals-table td {
            border: none;
            padding: 5px 10px;
        }
        .total-row {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #333;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background: #FEF3C7; color: #92400E; }
        .status-confirmed { background: #D1FAE5; color: #065F46; }
        .status-shipped { background: #DBEAFE; color: #1E40AF; }
        .status-delivered { background: #E0E7FF; color: #3730A3; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; width: 50%;">
                    <div class="company-name">SmartSupply</div>
                    <div>Industrial Sales Management</div>
                    <div>Email: info@smartsupply.com</div>
                    <div>Phone: +255 123 456 789</div>
                </td>
                <td style="border: none; width: 50%; vertical-align: top;">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-details">
                        <strong>Invoice #:</strong> {{ $order->invoice_number }}<br>
                        <strong>Order #:</strong> {{ $order->order_number }}<br>
                        <strong>Date:</strong> {{ $order->order_date->format('M d, Y') }}<br>
                        <strong>Status:</strong> 
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Bill To -->
    <div class="section-title">Bill To:</div>
    <div class="info-box">
        @if($order->retailer)
            <strong>{{ $order->retailer->company_name }}</strong><br>
            Contact: {{ $order->retailer->contact_person }}<br>
            Email: {{ $order->retailer->email }}<br>
            Phone: {{ $order->retailer->phone }}<br>
            @if($order->delivery_address)
                Address: {{ $order->delivery_address }}
            @else
                Address: {{ $order->retailer->address }}
            @endif
        @else
            <strong>{{ $order->user->name }}</strong><br>
            Email: {{ $order->user->email }}
        @endif
    </div>

    <!-- Order Items -->
    <div class="section-title">Order Items:</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Product</th>
                <th style="width: 15%;" class="text-center">Quantity</th>
                <th style="width: 17%;" class="text-right">Unit Price</th>
                <th style="width: 18%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product->name }}</strong><br>
                        <small>SKU: {{ $item->product->sku }}</small>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">${{ number_format($order->subtotal ?? $order->total_amount, 2) }}</td>
        </tr>
        @if($order->tax)
            <tr>
                <td>Tax (18%):</td>
                <td class="text-right">${{ number_format($order->tax, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td>Shipping:</td>
            <td class="text-right">FREE</td>
        </tr>
        <tr class="total-row">
            <td>Total:</td>
            <td class="text-right">${{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>

    <!-- Payment Info -->
    @if($order->payment_method)
        <div class="section-title">Payment Information:</div>
        <div class="info-box">
            <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}<br>
            @if($order->delivery_notes)
                <strong>Notes:</strong> {{ $order->delivery_notes }}
            @endif
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Thank you for your business!</strong></p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
        <p>For any queries, please contact us at info@smartsupply.com or call +255 123 456 789</p>
    </div>
</body>
</html>
