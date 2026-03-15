<div class="space-y-6 w-full max-w-5xl">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
                <flux:link :href="route('admin.messages.index')" wire:navigate variant="ghost" class="-ms-2">
                    ← {{ __('Back to Messages') }}
                </flux:link>
            </flux:text>
            <flux:heading class="mt-1">
                {{ __('Enquiry from :name', ['name' => $message->name]) }}
            </flux:heading>
            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ $message->created_at->format('F j, Y \a\t g:i A') }}
            </flux:text>
        </div>

        <div class="flex flex-wrap gap-2">
            <flux:badge size="sm" :color="$message->status === 'new' ? 'green' : ($message->status === 'replied' ? 'blue' : 'zinc')">
                {{ ucfirst($message->status) }}
            </flux:badge>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <flux:card class="lg:col-span-2">
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
                    <p class="mt-2 whitespace-pre-wrap leading-relaxed text-zinc-900 dark:text-zinc-100">
                        {{ $message->message }}
                    </p>
                </div>
            @endif
        </flux:card>

        <div class="space-y-4">
            <flux:card>
                <flux:heading size="sm" class="mb-3">{{ __('Status') }}</flux:heading>
                <div class="flex flex-wrap gap-2">
                    <flux:button size="sm" variant="{{ $message->status === 'read' ? 'primary' : 'ghost' }}" wire:click="updateStatus('read')" class="justify-center">
                        {{ __('Mark as read') }}
                    </flux:button>
                    <flux:button size="sm" variant="{{ $message->status === 'replied' ? 'primary' : 'ghost' }}" wire:click="updateStatus('replied')" class="justify-center">
                        {{ __('Mark as replied') }}
                    </flux:button>
                    <flux:button size="sm" variant="{{ $message->status === 'archived' ? 'primary' : 'ghost' }}" wire:click="updateStatus('archived')" class="justify-center">
                        {{ __('Archive') }}
                    </flux:button>
                </div>
            </flux:card>

            <flux:card>
                <flux:heading size="sm" class="mb-2">{{ __('Admin notes') }}</flux:heading>
                <flux:textarea wire:model="notes" rows="4" placeholder="{{ __('Private notes about this enquiry...') }}" />
                <div class="mt-2 flex items-center gap-2">
                    <flux:button variant="primary" size="sm" wire:click="saveNotes" class="justify-center">
                        {{ __('Save notes') }}
                    </flux:button>
                    <x-action-message class="text-xs text-zinc-500 dark:text-zinc-400" on="notes-saved">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </flux:card>
        </div>
    </div>
</div>
