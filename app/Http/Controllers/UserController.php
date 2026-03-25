<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('users.create', [
            'roles' => [User::ROLE_ADMIN, User::ROLE_USER],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'in:' . User::ROLE_ADMIN . ',' . User::ROLE_USER],
        ]);

        $generatedPassword = Str::random(12);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $generatedPassword,
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', 'User berhasil dibuat.')
            ->with('generated_password', $generatedPassword);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'admin_password' => ['required', 'current_password'],
            'user_id' => ['required', 'uuid', 'exists:users,id'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        if ($request->user()->is($user)) {
            return back()->withErrors([
                'admin_password' => 'Tidak bisa menghapus akun sendiri.',
            ]);
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('status', 'User berhasil dihapus.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:' . User::ROLE_ADMIN . ',' . User::ROLE_USER],
        ]);

        if ($request->user()->is($user) && $validated['role'] !== User::ROLE_ADMIN) {
            return back()->withErrors([
                'role' => 'Tidak bisa menurunkan role akun sendiri.',
            ]);
        }

        $user->update([
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', 'Role user berhasil diperbarui.');
    }
}
