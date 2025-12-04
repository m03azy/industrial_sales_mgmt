<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SalesOrder;

class ExportController extends Controller
{
    /**
     * Export data as CSV for the given type (products, customers, orders).
     */
    public function export($type)
    {
        $now = now()->format('Ymd_His');

        // Use cursor() to stream results and avoid large memory usage
        switch ($type) {
            case 'products':
                $filename = "products_{$now}.csv";
                $rows = Product::cursor();
                $callback = function () use ($rows) {
                    $out = fopen('php://output', 'w');
                    fputcsv($out, ['SKU', 'Name', 'Description', 'Cost Price', 'Selling Price', 'Stock Quantity', 'Low Stock Threshold', 'Category']);
                    foreach ($rows as $r) {
                        fputcsv($out, [$r->sku, $r->name, $r->description, $r->cost_price, $r->selling_price, $r->stock_quantity, $r->low_stock_threshold, $r->category]);
                        // flush occasionally
                        if (function_exists('ob_flush')) { @ob_flush(); }
                        if (function_exists('flush')) { @flush(); }
                    }
                    fclose($out);
                };
                break;

            case 'customers':
                $filename = "customers_{$now}.csv";
                $rows = Customer::cursor();
                $callback = function () use ($rows) {
                    $out = fopen('php://output', 'w');
                    fputcsv($out, ['Company', 'Contact Person', 'Email', 'Phone', 'Address']);
                    foreach ($rows as $r) {
                        fputcsv($out, [$r->company_name, $r->contact_person, $r->email, $r->phone, $r->address]);
                        if (function_exists('ob_flush')) { @ob_flush(); }
                        if (function_exists('flush')) { @flush(); }
                    }
                    fclose($out);
                };
                break;

            case 'orders':
                $filename = "orders_{$now}.csv";
                $rows = SalesOrder::with('customer')->cursor();
                $callback = function () use ($rows) {
                    $out = fopen('php://output', 'w');
                    fputcsv($out, ['Order #', 'Customer', 'Date', 'Total Amount', 'Status']);
                    foreach ($rows as $r) {
                        fputcsv($out, [$r->order_number, optional($r->customer)->company_name, $r->order_date, $r->total_amount, $r->status]);
                        if (function_exists('ob_flush')) { @ob_flush(); }
                        if (function_exists('flush')) { @flush(); }
                    }
                    fclose($out);
                };
                break;

            default:
                abort(404);
        }

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
