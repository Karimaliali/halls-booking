<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update($request->only(['name', 'email']));

        return redirect()->back()->with('status', 'تم تحديث البيانات بنجاح.');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        auth()->logout();

        $user->delete();

        return redirect()->route('home')->with('status', 'تم حذف الحساب بنجاح.');
    }
}
