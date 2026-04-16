<div class="space-y-6 w-full max-w-6xl">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
            {{ __('Contact form leads. Update status as you work each enquiry.') }}
        </flux:text>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
            <flux:field class="w-full sm:w-44">
                <flux:label class="sr-only">{{ __('Period') }}</flux:label>
                <flux:select wire:model.live="periodFilter">
                    <option value="">{{ __('All time') }}</option>
                    <option value="today">{{ __('Today') }}</option>
                    <option value="week">{{ __('This week') }}</option>
                    <option value="month">{{ __('This month') }}</option>
                </flux:select>
            </flux:field>
            <flux:field class="w-full sm:w-44">
                <flux:label class="sr-only">{{ __('Status') }}</flux:label>
                <flux:select wire:model.live="statusFilter">
                    <option value="">{{ __('All statuses') }}</option>
                    <option value="new">{{ __('New') }}</option>
                    <option value="contacted">{{ __('Contacted') }}</option>
                    <option value="closed">{{ __('Closed') }}</option>
                </flux:select>
            </flux:field>
        </div>
    </div>

    <flux:card class="overflow-hidden">
        <div class="min-w-0 overflow-x-auto">
            <flux:table container:class="max-h-[70vh] min-w-[720px]">
                <flux:table.columns>
                    <flux:table.column>{{ __('Name') }}</flux:table.column>
                    <flux:table.column>{{ __('Email') }}</flux:table.column>
                    <flux:table.column>{{ __('Message') }}</flux:table.column>
                    <flux:table.column>{{ __('Date') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column class="text-end">{{ __('Actions') }}</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($leads as $lead)
                        <flux:table.row wire:key="lead-{{ $lead->id }}">
                            <flux:table.cell class="font-medium">{{ $lead->name }}</flux:table.cell>
                            <flux:table.cell>{{ $lead->email }}</flux:table.cell>
                            <flux:table.cell>
                                <span class="line-clamp-2 text-sm text-zinc-600 dark:text-zinc-300">{{ $lead->message ? \Illuminate\Support\Str::limit(strip_tags($lead->message), 120) : '—' }}</span>
                            </flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap text-sm">{{ $lead->created_at->format('M j, Y H:i') }}</flux:table.cell>
                            <flux:table.cell>
                                @if($lead->status === 'new')
                                    <flux:badge color="green">{{ __('New') }}</flux:badge>
                                @elseif($lead->status === 'contacted')
                                    <flux:badge color="blue">{{ __('Contacted') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc">{{ __('Closed') }}</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell class="text-end">
                                <flux:button size="sm" variant="ghost" :href="route('admin.leads.show', $lead)" wire:navigate>
                                    {{ __('View') }}
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="6" class="py-12 text-center">
                                <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('No leads match your filters.') }}</flux:text>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
        @if($leads->hasPages())
            <div class="mt-4 px-4 pb-4">
                {{ $leads->links() }}
            </div>
        @endif
    </flux:card>
</div>
