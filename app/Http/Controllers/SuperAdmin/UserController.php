<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\{User, Role, Branch};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with(['role', 'branch'])
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"))
            ->latest()
            ->paginate(20)
            ->withQueryString();
        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        $roles    = Role::all();
        $branches = Branch::where('is_active', true)->get();
        return view('superadmin.users.create', compact('roles', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'role_id'    => 'required|exists:roles,id',
            'branch_id'  => 'nullable|exists:branches,id',
            'mobile'     => 'nullable|string|max:20',
            'is_active'  => 'boolean',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
            'branch_id' => $request->branch_id,
            'mobile'    => $request->mobile,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['role', 'branch']);
        return view('superadmin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles    = Role::all();
        $branches = Branch::where('is_active', true)->get();
        return view('superadmin.users.edit', compact('user', 'roles', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:8|confirmed',
            'role_id'   => 'required|exists:roles,id',
            'branch_id' => 'nullable|exists:branches,id',
            'mobile'    => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'email', 'role_id', 'branch_id', 'mobile']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->update(['is_active' => false]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User deactivated successfully.');
    }
}
