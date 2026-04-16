@php
    $pageSubheading = $pageSubheading ?? __('Messages you have archived.');
    $hideStatusFilter = true;
@endphp
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ $pageSubheading }}
            </flux:text>
        </div>
    </div>

    <flux:card class="overflow-hidden">
        <div class="min-w-0 overflow-x-auto">
            <flux:table container:class="max-h-[70vh] min-w-[640px]">
                <flux:table.columns>
                    <flux:table.column>{{ __('Name') }}</flux:table.column>
                    <flux:table.column>{{ __('Company') }}</flux:table.column>
                    <flux:table.column>{{ __('Area') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column>{{ __('Date') }}</flux:table.column>
                    <flux:table.column class="text-end">{{ __('Actions') }}</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($messages as $message)
                        <flux:table.row>
                            <flux:table.cell class="font-medium">{{ $message->name }}</flux:table.cell>
                            <flux:table.cell>{{ $message->company }}</flux:table.cell>
                            <flux:table.cell>{{ $message->area ?? '—' }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge color="zinc">{{ __('Closed') }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>{{ $message->created_at->format('M j, Y H:i') }}</flux:table.cell>
                            <flux:table.cell class="text-end">
                                <flux:button size="sm" variant="ghost" :href="route('admin.leads.show', $message)" wire:navigate>
                                    {{ __('View') }}
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="6" class="py-12 text-center">
                                <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('No archived messages.') }}</flux:text>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
        @if($messages->hasPages())
            <div class="mt-4">
                {{ $messages->links() }}
            </div>
        @endif
    </flux:card>
</div>
