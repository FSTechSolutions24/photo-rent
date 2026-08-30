<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppTemplate;
use Illuminate\Http\Request;

class WhatsAppTemplateController extends Controller
{
    private const DEFAULT_MESSAGE = "Hello {{client_name}},\n\nYour gallery is ready: {{url}}\n\nClient password: {{client_password}}\nGuest password: {{guest_password}}";

    public function edit()
    {
        $template = WhatsAppTemplate::firstOrCreate(
            ['photographer_id' => auth()->user()->photographer->id],
            ['message' => self::DEFAULT_MESSAGE]
        );

        return view('dashboard.whatsapp-template.edit', compact('template'));
    }

    public function update(Request $request)
    {
        $data = $request->validate(['message' => ['required', 'string', 'max:4000']]);

        WhatsAppTemplate::updateOrCreate(
            ['photographer_id' => auth()->user()->photographer->id],
            ['message' => $data['message']]
        );

        return redirect()->route('dashboard.whatsapp-template.edit')
            ->with('success', 'WhatsApp message template saved.');
    }
}
