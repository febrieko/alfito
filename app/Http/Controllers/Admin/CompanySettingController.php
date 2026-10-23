<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanySettingController extends Controller
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    /**
     * Show the company settings edit form.
     */
    public function edit(): View
    {
        $setting = CompanySetting::get();

        return view('admin.settings.edit', compact('setting'));
    }

    /**
     * Update the company settings in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'short_name' => ['required', 'string', 'max:50'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'whatsapp_number' => ['required', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
            'operational_hours' => ['nullable', 'string', 'max:100'],
            'license_info' => ['nullable', 'string', 'max:150'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'threads_url' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'tiktok_url' => ['nullable', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'string', 'max:255'],
            'footer_text' => ['nullable', 'string', 'max:500'],
            'logo_url' => ['nullable', 'string', 'max:1000'],
            'logo_file' => ['nullable', 'image', 'max:5120'],
            'favicon_url' => ['nullable', 'string', 'max:1000'],
            'favicon_file' => ['nullable', 'image', 'max:2048'],
            'bank_accounts' => ['nullable', 'array'],
            'bank_accounts.*.bank' => ['nullable', 'string', 'max:50'],
            'bank_accounts.*.account_number' => ['nullable', 'string', 'max:50'],
            'bank_accounts.*.account_name' => ['nullable', 'string', 'max:100'],
        ]);

        $setting = CompanySetting::first() ?? new CompanySetting;

        // Handle logo file upload
        if ($request->hasFile('logo_file')) {
            $validated['logo_url'] = $this->imageService->resizeAndStore(
                $request->file('logo_file'),
                'settings',
                600,
                300
            );
        }

        // Handle favicon file upload
        if ($request->hasFile('favicon_file')) {
            $validated['favicon_url'] = $this->imageService->resizeAndStore(
                $request->file('favicon_file'),
                'settings',
                128,
                128
            );
        }

        // Format and clean bank accounts array
        $bankAccounts = [];
        if (! empty($validated['bank_accounts']) && is_array($validated['bank_accounts'])) {
            foreach ($validated['bank_accounts'] as $acc) {
                if (! empty($acc['bank']) && ! empty($acc['account_number'])) {
                    $bankAccounts[] = [
                        'bank' => trim($acc['bank']),
                        'account_number' => trim($acc['account_number']),
                        'account_name' => trim($acc['account_name'] ?? $validated['company_name']),
                    ];
                }
            }
        }
        $validated['bank_accounts'] = ! empty($bankAccounts) ? $bankAccounts : null;

        unset($validated['logo_file'], $validated['favicon_file']);

        $setting->fill($validated)->save();

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Informasi profil perusahaan dan nomor WhatsApp berhasil diperbarui!');
    }
}
