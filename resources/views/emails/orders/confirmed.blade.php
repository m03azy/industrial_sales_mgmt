<x-mail::message>
# Order Confirmed!

Hello **{{ $order->retailer->contact_person }}**,

Your order **{{ $order->order_number }}** has been confirmed and is being processed.

## Order Details

- **Order Number:** {{ $order->order_number }}
- **Order Date:** {{ $order->created_at->format('F d, Y') }}
- **Total Amount:** ${{ number_format($order->total_amount, 2) }}
- **Status:** {{ ucfirst($order->status) }}

<x-mail::button :url="url('/retailer/orders/' . $order->id)">
View Order Details
</x-mail::button>

We'll notify you when your order ships.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
