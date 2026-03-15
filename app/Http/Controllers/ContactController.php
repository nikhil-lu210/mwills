<?php

namespace App\Http\Controllers;

use App\Mail\NewInquiryNotification;
use App\Models\ConsultationMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        $inquiry = ConsultationMessage::create([
            'name' => $validated['name'],
            'company' => $validated['company'],
            'email' => $validated['email'],
            'area' => ! empty($validated['area']) ? $validated['area'] : null,
            'message' => $validated['message'] ?? null,
            'status' => ConsultationMessage::STATUS_NEW,
        ]);

        // Send notification email after the response is sent to avoid gateway timeouts.
        // Mail is still sent directly (no queue); user gets redirect immediately.
        $inquiryId = $inquiry->id;
        app()->terminating(function () use ($inquiryId): void {
            $inquiry = ConsultationMessage::find($inquiryId);
            if ($inquiry) {
                $adminEmail = User::query()->first()?->email ?? config('mail.from.address');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new NewInquiryNotification($inquiry));
                }
            }
        });

        return redirect()->route('contact.thank-you');
    }
}
