<x-mail::message>
# Your Order Has Shipped!

Hello **{{ $order->retailer->contact_person }}**,

Great news! Your order **{{ $order->order_number }}** has been shipped and is on its way.

## Shipping Details

- **Order Number:** {{ $order->order_number }}
- **Shipped Date:** {{ now()->format('F d, Y') }}
- **Total Amount:** ${{ number_format($order->total_amount, 2) }}

<x-mail::button :url="url('/retailer/orders/' . $order->id)">
Track Your Order
</x-mail::button>

You'll receive another email when your order is delivered.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
