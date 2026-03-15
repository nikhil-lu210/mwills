<div class="space-y-6 max-w-3xl">
    <flux:button variant="ghost" :href="route('admin.messages.index')" wire:navigate class="-ms-2">
        ← {{ __('Back to Messages') }}
    </flux:button>

    <flux:card>
        <flux:subheading class="mb-4">{{ $message->created_at->format('F j, Y \a\t g:i A') }}</flux:subheading>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Name') }}</flux:text>
                <flux:heading size="sm">{{ $message->name }}</flux:heading>
            </div>
            <div>
                <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Company') }}</flux:text>
                <flux:heading size="sm">{{ $message->company }}</flux:heading>
            </div>
            <div>
                <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Email') }}</flux:text>
                <flux:link :href="'mailto:'.$message->email" target="_blank">{{ $message->email }}</flux:link>
            </div>
            <div>
                <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Area') }}</flux:text>
                <flux:heading size="sm">{{ $message->area ?? '—' }}</flux:heading>
            </div>
        </div>

        @if($message->message)
            <div class="mt-6 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Message') }}</flux:text>
                <p class="mt-1 whitespace-pre-wrap">{{ $message->message }}</p>
            </div>
        @endif

        <div class="mt-6 flex flex-wrap gap-2">
            <flux:button size="sm" variant="{{ $message->status === 'read' ? 'primary' : 'ghost' }}" wire:click="updateStatus('read')">
                {{ __('Read') }}
            </flux:button>
            <flux:button size="sm" variant="{{ $message->status === 'replied' ? 'primary' : 'ghost' }}" wire:click="updateStatus('replied')">
                {{ __('Replied') }}
            </flux:button>
            <flux:button size="sm" variant="{{ $message->status === 'archived' ? 'primary' : 'ghost' }}" wire:click="updateStatus('archived')">
                {{ __('Archived') }}
            </flux:button>
        </div>
    </flux:card>

    <flux:card>
        <flux:heading size="sm" class="mb-2">{{ __('Admin notes') }}</flux:heading>
        <flux:textarea wire:model="notes" rows="3" placeholder="{{ __('Private notes about this enquiry...') }}" />
        <flux:button class="mt-2" variant="primary" wire:click="saveNotes">
            {{ __('Save notes') }}
        </flux:button>
        <x-action-message class="ms-2" on="notes-saved">{{ __('Saved.') }}</x-action-message>
    </flux:card>
</div>
