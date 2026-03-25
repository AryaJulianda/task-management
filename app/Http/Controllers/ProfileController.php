<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
  public function edit(Request $request): View
  {
    return view('profile.edit', [
      'user' => $request->user(),
    ]);
  }

  public function update(Request $request): RedirectResponse
  {
    $user = $request->user();

    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
    ]);

    $user->update($validated);

    return redirect()->route('profile.edit')->with('status', 'Profil berhasil diperbarui.');
  }

  public function updatePassword(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'current_password' => ['required', 'current_password'],
      'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $request->user()->update([
      'password' => $validated['password'],
    ]);

    return redirect()->route('profile.edit')->with('status', 'Password berhasil diperbarui.');
  }
}
