<?php

namespace App\Http\Controllers;

use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function edit(Request $request)
    {
        return view('dashboard.portfolio.edit', [
            'photographer' => $request->user()->photographer,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'portfolio_title' => ['nullable', 'string', 'max:100'],
            'portfolio_bio' => ['nullable', 'string', 'max:1000'],
            'portfolio_theme' => ['required', 'in:bold,light'],
            'portfolio_primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'portfolio_accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'portfolio_show_contact' => ['nullable', 'boolean'],
            'portfolio_cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'portfolio_contact_email' => ['nullable', 'email', 'not_regex:/[\r\n]/', 'max:255'],
            'portfolio_contact_phone' => ['nullable', 'string', 'max:30'],
            'portfolio_instagram' => ['nullable', 'string', 'max:100'],
            'portfolio_footer_text' => ['nullable', 'string', 'max:255'],
        ]);

        $data['portfolio_show_contact'] = $request->boolean('portfolio_show_contact');
        $photographer = $request->user()->photographer;
        unset($data['portfolio_cover']);
        $photographer->update($data);

        if ($request->hasFile('portfolio_cover')) {
            $previousCoverPath = $photographer->portfolio_cover_path;
            $file = $request->file('portfolio_cover');
            $extension = strtolower($file->extension());
            $path = 'users/' . $request->user()->id . '/portfolio/cover/' . Str::uuid() . '.' . $extension;

            if (! Storage::disk('wasabi')->put($path, file_get_contents($file))) {
                throw new \RuntimeException('The portfolio cover could not be uploaded to Wasabi.');
            }

            $photographer->update(['portfolio_cover_path' => $path]);
            $this->deleteCover($previousCoverPath);
        }

        return back()->with('success', 'Portfolio settings updated successfully.');
    }

    public function show($photographer_subdomain)
    {
        $photographer = Photographer::with('user')
            ->where('subdomain', $photographer_subdomain)
            ->firstOrFail();

        $galleries = $photographer->galleries()
            ->where('is_public', true)
            ->with('session')
            ->latest()
            ->get();

        return view('portfolio.show', compact('photographer', 'galleries'));
    }

    private function deleteCover(?string $path): void
    {
        if (! $path) {
            return;
        }

        $disk = str_starts_with($path, 'users/') ? 'wasabi' : 'public';
        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
