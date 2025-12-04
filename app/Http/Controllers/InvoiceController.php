<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;

class InvoiceController extends Controller
{
    /**
     * Show a simple invoice view for the given order.
     */
    public function show(SalesOrder $order)
    {
        $order->load(['customer', 'orderItems.product']);
        return view('invoices.show', compact('order'));
    }

    /**
     * Download invoice as PDF (requires barryvdh/laravel-dompdf).
     */
    public function downloadPdf(SalesOrder $order)
    {
        $order->load(['customer', 'orderItems.product']);
        $storagePath = storage_path('app/invoices/' . $order->order_number . '.pdf');

        // If a stored PDF exists, serve it
        if (file_exists($storagePath)) {
            return response()->download($storagePath, $order->order_number . '.pdf');
        }

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            abort(501, 'PDF generation is not available. Run: composer require barryvdh/laravel-dompdf');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('order'));

        return $pdf->download($order->order_number . '.pdf');
    }

    /**
     * Generate and store invoice PDF for an order (if dompdf available).
     */
    public function generate(SalesOrder $order)
    {
        $order->load(['customer', 'orderItems.product']);

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return redirect()->back()->with('error', 'PDF generation is not available. Run: composer require barryvdh/laravel-dompdf');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('order'));

        $dir = storage_path('app/invoices');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $dir . '/' . $order->order_number . '.pdf';
        file_put_contents($file, $pdf->output());

        return redirect()->route('orders.invoice', $order)->with('success', 'Invoice PDF generated and stored.');
    }
}
