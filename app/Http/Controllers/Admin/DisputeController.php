<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index()
    {
        $disputes = Dispute::with(['order', 'retailer', 'factory'])->latest()->paginate(15);
        return view('admin.disputes.index', compact('disputes'));
    }

    public function show(Dispute $dispute)
    {
        $dispute->load(['order', 'retailer', 'factory']);
        return view('admin.disputes.show', compact('dispute'));
    }

    public function resolve(Request $request, Dispute $dispute)
    {
        $request->validate([
            'resolution' => 'required|string',
            'status' => 'required|in:resolved,closed',
        ]);

        $dispute->update([
            'resolution' => $request->resolution,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.disputes.show', $dispute)->with('success', 'Dispute updated successfully.');
    }
}
