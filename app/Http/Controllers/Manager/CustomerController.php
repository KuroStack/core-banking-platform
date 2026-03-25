<?php
namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct(private CustomerService $customerService) {}

    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        $query = Customer::where('branch_id', $branchId)->with(['city', 'state']);

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('full_name', 'like', "%{$search}%")->orWhere('mobile', 'like', "%{$search}%")->orWhere('customer_number', 'like', "%{$search}%"));
        }

        $customers = $query->latest()->paginate(20)->withQueryString();
        return view('manager.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['city', 'state', 'country', 'branch']);
        return view('manager.customers.show', compact('customer'));
    }

    public function bulkApprove(Request $request)
    {
        $request->validate(['customer_ids' => 'required|array', 'customer_ids.*' => 'exists:customers,id']);
        $count = 0;
        foreach ($request->customer_ids as $id) {
            $customer = Customer::find($id);
            if ($customer && $customer->approval_status === 'pending') {
                $this->customerService->approve($customer, Auth::id());
                $count++;
            }
        }
        return redirect()->route('manager.customers.index')
            ->with('success', "{$count} customer(s) approved successfully.");
    }

    public function approve(Request $request, Customer $customer)
    {
        $this->customerService->approve($customer, Auth::id());

        return redirect()->route('manager.customers.show', $customer->id)
            ->with('success', 'Customer approved successfully.');
    }

    public function reject(Request $request, Customer $customer)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $this->customerService->reject($customer, Auth::id(), $request->rejection_reason ?? '');

        return redirect()->route('manager.customers.show', $customer->id)
            ->with('success', 'Customer rejected.');
    }
}
