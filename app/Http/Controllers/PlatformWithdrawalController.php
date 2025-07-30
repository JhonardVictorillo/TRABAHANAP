<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlatformWithdrawalController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);
        
        // Calculate available revenue
        $totalRevenue = PlatformRevenue::sum('amount');
        $withdrawnRevenue = PlatformWithdrawal::sum('amount');
        $availableRevenue = $totalRevenue - $withdrawnRevenue;
        
        // Check if withdrawal amount is valid
        if ($request->amount > $availableRevenue) {
            return redirect()
                ->back()
                ->with('error', 'Withdrawal amount exceeds available revenue.');
        }
        
        try {
            DB::beginTransaction();
            
            // Create platform withdrawal record
            PlatformWithdrawal::create([
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'admin_id' => auth()->id(),
                'processed_at' => now(),
            ]);
            
            DB::commit();
            
            Log::info('Platform withdrawal created', [
                'admin_id' => auth()->id(),
                'amount' => $request->amount
            ]);
            
            return redirect()
                ->route('admin.dashboard', ['section' => 'revenue', 'tab' => 'withdrawals'])
                ->with('success', 'Platform revenue withdrawal recorded successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Platform withdrawal error', [
                'admin_id' => auth()->id(),
                'amount' => $request->amount,
                'error' => $e->getMessage()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Error processing platform withdrawal: ' . $e->getMessage());
        }
    }
    
    /**
     * Display list of platform withdrawals
     */
    public function index()
    {
        $withdrawals = PlatformWithdrawal::with('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $totalRevenue = PlatformRevenue::sum('amount');
        $withdrawnRevenue = PlatformWithdrawal::sum('amount');
        $availableRevenue = $totalRevenue - $withdrawnRevenue;
            
        return view('admin.platform-withdrawals.index', compact('withdrawals', 'availableRevenue'));
    }
}