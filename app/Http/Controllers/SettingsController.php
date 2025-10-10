<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {
        $currentLink = env('GOOGLE_SPREADSHEET_ID');
        return view('main.settings', [
            'currentSheetLink' => $currentLink,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'sheet_link' => 'required|url',
        ]);

        $link = trim($request->input('sheet_link'));
        $use = $request->has('use_new_sheet');

        if ($use) {
            $this->updateEnvValue('GOOGLE_SPREADSHEET_ID', $link);
            session()->flash('success', 'Link Google Sheet berhasil diperbarui dan diaktifkan.');
        } else {
            session()->flash('warning', 'Link disimpan tetapi belum diaktifkan (centang "Gunakan link ini" untuk menerapkan).');
        }

        return redirect()->route('settings.index');
    }

    /**
     * Ubah nilai environment (.env) dengan aman
     */
    private function updateEnvValue($key, $value)
    {
        $path = base_path('.env');
        $escaped = preg_quote("{$key}=", '/');

        if (File::exists($path)) {
            $content = File::get($path);
            if (preg_match("/^{$escaped}.*/m", $content)) {
                $content = preg_replace("/^{$escaped}.*/m", "{$key}=\"{$value}\"", $content);
            } else {
                $content .= "\n{$key}=\"{$value}\"";
            }
            File::put($path, $content);
        }
    }
}
