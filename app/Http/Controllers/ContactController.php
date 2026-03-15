<?php

namespace App\Http\Controllers;

use App\Models\ConsultationMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'area' => ['nullable', 'string', Rule::in(array_merge([''], ConsultationMessage::AREAS))],
            'message' => ['nullable', 'string', 'max:200'],
        ], [
            'name.required' => 'Please enter your full name.',
            'company.required' => 'Please enter your company name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        ConsultationMessage::create([
            'name' => $validated['name'],
            'company' => $validated['company'],
            'email' => $validated['email'],
            'area' => ! empty($validated['area']) ? $validated['area'] : null,
            'message' => $validated['message'] ?? null,
            'status' => ConsultationMessage::STATUS_NEW,
        ]);

        return redirect()->route('contact.thank-you');
    }
}
