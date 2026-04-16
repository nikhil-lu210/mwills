<?php

namespace App\Livewire\Admin;

use App\Mail\NewInquiryReply;
use App\Models\ConsultationMessage;
use App\Models\ConsultationMessageNote;
use Flux\Concerns\InteractsWithComponents;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MessageView extends Component
{
    use InteractsWithComponents;

    public ConsultationMessage $message;

    public string $notes = '';

    public string $newNote = '';

    public string $replyBody = '';

    public function mount(ConsultationMessage $message): void
    {
        $this->message = $message->load('adminNotes.user');
        $this->notes = $message->notes ?? '';

        if ($message->status === ConsultationMessage::STATUS_NEW) {
            $message->update(['status' => ConsultationMessage::STATUS_CONTACTED]);
            $this->message->refresh();
        }
    }

    public function updateStatus(string $status): void
    {
        Validator::make(
            ['status' => $status],
            [
                'status' => ['required', Rule::in([
                    ConsultationMessage::STATUS_NEW,
                    ConsultationMessage::STATUS_CONTACTED,
                    ConsultationMessage::STATUS_CLOSED,
                ])],
            ],
        )->validate();

        $this->message->update(['status' => $status]);
        $this->message->refresh();
    }

    public function saveNotes(): void
    {
        // Keep for backwards compatibility with the single-notes field.
        $this->message->update(['notes' => $this->notes]);
        $this->dispatch('notes-saved');
    }

    public function addNote(): void
    {
        $validated = $this->validate([
            'newNote' => ['required', 'string', 'max:1000'],
        ], [], [
            'newNote' => __('note'),
        ]);

        ConsultationMessageNote::create([
            'consultation_message_id' => $this->message->id,
            'user_id' => auth()->id(),
            'body' => $validated['newNote'],
        ]);

        $this->newNote = '';
        $this->message->refresh()->load('adminNotes.user');
        $this->toast(__('Note added.'), null, 4000, 'success');
    }

    public function sendReply(): void
    {
        if (! empty($this->message->first_reply)) {
            $this->toast(__('A reply has already been sent for this enquiry.'), null, 6000, 'warning');
            session()->flash('error', __('A reply has already been sent for this enquiry.'));

            return;
        }

        $validated = $this->validate([
            'replyBody' => ['required', 'string', 'max:2000'],
        ], [], [
            'replyBody' => __('reply'),
        ]);

        Mail::to($this->message->email)->send(
            new NewInquiryReply($this->message, $validated['replyBody'])
        );

        $this->message->update([
            'status' => ConsultationMessage::STATUS_CONTACTED,
            'first_reply' => $validated['replyBody'],
        ]);
        $this->message->refresh();

        $this->toast(__('Reply email sent to :email.', ['email' => $this->message->email]), null, 5000, 'success');
        session()->flash('success', __('Reply email sent.'));
    }

    public function render()
    {
        return view('livewire.admin.message.show')
            ->layout('layouts.app.sidebar', [
                'title' => __('Message'),
                'breadcrumbs' => [
                    ['label' => __('Dashboard'), 'href' => route('dashboard')],
                    ['label' => __('Leads'), 'href' => route('admin.leads.index')],
                    ['label' => __('Lead'), 'href' => null],
                ],
            ]);
    }
}
