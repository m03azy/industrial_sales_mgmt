<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #4F46E5;">Invoice from SmartSupply</h2>
        
        <p>Dear {{ $order->user->name }},</p>
        
        <p>Thank you for your order! Please find your invoice attached to this email.</p>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Invoice Number:</strong> {{ $order->invoice_number }}</p>
            <p style="margin: 5px 0;"><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p style="margin: 5px 0;"><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
        </div>
        
        <p>If you have any questions about this invoice, please don't hesitate to contact us.</p>
        
        <p>Best regards,<br>
        <strong>SmartSupply Team</strong></p>
        
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
        
        <p style="font-size: 12px; color: #666;">
            This is an automated email. Please do not reply to this message.
        </p>
    </div>
</body>
</html>
