<?php

namespace App\Http\Controllers;

use App\Models\DataKonservasi;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $records = DataKonservasi::with('subBidang.bidang')
            ->where('user_id', $user->id)
            ->latest()
            ->take(12)
            ->get();

        $recordCount = DataKonservasi::where('user_id', $user->id)->count();

        $contributions = DataKonservasi::query()
            ->where('data_konservasi.user_id', $user->id)
            ->join('ref_sub_bidang', 'data_konservasi.sub_bidang_id', '=', 'ref_sub_bidang.id')
            ->join('ref_bidang', 'ref_sub_bidang.bidang_id', '=', 'ref_bidang.id')
            ->select('ref_bidang.id', 'ref_bidang.nama_bidang')
            ->selectRaw('COUNT(data_konservasi.id) as total')
            ->groupBy('ref_bidang.id', 'ref_bidang.nama_bidang')
            ->orderByDesc('total')
            ->get();

        return view('profile.edit', [
            'user' => $user,
            'records' => $records,
            'recordCount' => $recordCount,
            'contributions' => $contributions,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
