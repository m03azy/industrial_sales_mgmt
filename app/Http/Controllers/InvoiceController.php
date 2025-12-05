<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function download($orderId)
    {
        $order = SalesOrder::with(['orderItems.product', 'retailer', 'user'])
            ->findOrFail($orderId);

        // Authorization check
        $user = auth()->user();
        if ($user->role === 'retailer' && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized access to invoice.');
        }

        // Generate invoice number if not exists
        if (!$order->invoice_number) {
            $order->update([
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT)
            ]);
        }

        $pdf = PDF::loadView('invoices.template', compact('order'));
        
        return $pdf->download('invoice-' . $order->invoice_number . '.pdf');
    }

    public function view($orderId)
    {
        $order = SalesOrder::with(['orderItems.product', 'retailer', 'user'])
            ->findOrFail($orderId);

        // Authorization check
        $user = auth()->user();
        if ($user->role === 'retailer' && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized access to invoice.');
        }

        // Generate invoice number if not exists
        if (!$order->invoice_number) {
            $order->update([
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT)
            ]);
        }

        $pdf = PDF::loadView('invoices.template', compact('order'));
        
        return $pdf->stream('invoice-' . $order->invoice_number . '.pdf');
    }

    public function email($orderId)
    {
        $order = SalesOrder::with(['orderItems.product', 'retailer', 'user'])
            ->findOrFail($orderId);

        // Authorization check (admin or order owner)
        $user = auth()->user();
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Generate invoice number if not exists
        if (!$order->invoice_number) {
            $order->update([
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT)
            ]);
        }

        $pdf = PDF::loadView('invoices.template', compact('order'));
        
        // Send email with PDF attachment
        Mail::send('emails.invoice', compact('order'), function($message) use ($order, $pdf) {
            $message->to($order->user->email)
                    ->subject('Invoice ' . $order->invoice_number . ' - SmartSupply')
                    ->attachData($pdf->output(), 'invoice-' . $order->invoice_number . '.pdf');
        });

        return redirect()->back()->with('success', 'Invoice sent to ' . $order->user->email);
    }
}
