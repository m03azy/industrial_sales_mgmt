<x-mail::message>
# Welcome to SmartSupply!

Hello **{{ $user->name }}**,

Congratulations! Your account has been approved and you now have full access to the SmartSupply platform.

## Your Account Details

- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Role:** {{ ucfirst($user->role) }}

<x-mail::button :url="url('/dashboard')">
Access Your Dashboard
</x-mail::button>

You can now:
@if($user->role === 'factory')
- Manage your products
- View and process orders
- Track your sales analytics
@elseif($user->role === 'retailer')
- Browse the marketplace
- Place orders
- Track your purchases
@endif

If you have any questions, feel free to contact our support team.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
