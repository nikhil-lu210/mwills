<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <flux:heading>{{ __('Consultation Messages') }}</flux:heading>
        <flux:select wire:model.live="statusFilter" class="w-full sm:w-48">
            <option value="">{{ __('All statuses') }}</option>
            <option value="new">{{ __('New') }}</option>
            <option value="read">{{ __('Read') }}</option>
            <option value="replied">{{ __('Replied') }}</option>
            <option value="archived">{{ __('Archived') }}</option>
        </flux:select>
    </div>

    <flux:card>
        <flux:table>
            <flux:columns>
                <flux:column>{{ __('Name') }}</flux:column>
                <flux:column>{{ __('Company') }}</flux:column>
                <flux:column>{{ __('Area') }}</flux:column>
                <flux:column>{{ __('Status') }}</flux:column>
                <flux:column>{{ __('Date') }}</flux:column>
                <flux:column class="text-end">{{ __('Actions') }}</flux:column>
            </flux:columns>
            <flux:rows>
                @forelse($messages as $message)
                    <flux:row>
                        <flux:cell class="font-medium">{{ $message->name }}</flux:cell>
                        <flux:cell>{{ $message->company }}</flux:cell>
                        <flux:cell>{{ $message->area ?? '—' }}</flux:cell>
                        <flux:cell>
                            @if($message->status === 'new')
                                <flux:badge color="green">{{ __('New') }}</flux:badge>
                            @elseif($message->status === 'read')
                                <flux:badge color="zinc">{{ __('Read') }}</flux:badge>
                            @elseif($message->status === 'replied')
                                <flux:badge color="blue">{{ __('Replied') }}</flux:badge>
                            @else
                                <flux:badge color="zinc">{{ __('Archived') }}</flux:badge>
                            @endif
                        </flux:cell>
                        <flux:cell>{{ $message->created_at->format('M j, Y H:i') }}</flux:cell>
                        <flux:cell class="text-end">
                            <flux:button size="sm" variant="ghost" :href="route('admin.messages.show', $message)" wire:navigate>
                                {{ __('View') }}
                            </flux:button>
                        </flux:cell>
                    </flux:row>
                @empty
                    <flux:row>
                        <flux:cell colspan="6" class="text-center text-zinc-500 dark:text-zinc-400">
                            {{ __('No messages yet.') }}
                        </flux:cell>
                    </flux:row>
                @endforelse
            </flux:rows>
        </flux:table>
        @if($messages->hasPages())
            <div class="mt-4">
                {{ $messages->links() }}
            </div>
        @endif
    </flux:card>
</div>
