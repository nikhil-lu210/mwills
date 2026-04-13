<div class="space-y-6 w-full max-w-5xl">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Manage dashboard access. The primary admin account cannot be deleted or deactivated.') }}
            </flux:text>
        </div>
        <flux:button :href="route('admin.users.create')" variant="primary" wire:navigate class="w-full sm:w-auto justify-center">
            {{ __('Create New User') }}
        </flux:button>
    </div>

    <flux:card class="overflow-hidden">
        <div class="min-w-0 overflow-x-auto">
            <flux:table container:class="max-h-[70vh] min-w-[720px]">
                <flux:table.columns>
                    <flux:table.column>{{ __('Name') }}</flux:table.column>
                    <flux:table.column>{{ __('Email') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column>{{ __('Joined') }}</flux:table.column>
                    <flux:table.column class="text-end">{{ __('Actions') }}</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($users as $user)
                        <flux:table.row wire:key="user-row-{{ $user->id }}">
                            <flux:table.cell>
                                <span class="font-medium">{{ $user->name }}</span>
                                @if($user->isOwner())
                                    <flux:badge size="sm" color="zinc" class="ms-2 align-middle">{{ __('Primary admin') }}</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>{{ $user->email }}</flux:table.cell>
                            <flux:table.cell>
                                @if($user->isOwner())
                                    <flux:badge color="green">{{ __('Active') }}</flux:badge>
                                @else
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($user->is_active)
                                            <flux:badge color="green">{{ __('Active') }}</flux:badge>
                                        @else
                                            <flux:badge color="zinc">{{ __('Inactive') }}</flux:badge>
                                        @endif
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            wire:click="toggleActive({{ $user->id }})"
                                            wire:key="toggle-{{ $user->id }}"
                                        >
                                            {{ $user->is_active ? __('Set inactive') : __('Set active') }}
                                        </flux:button>
                                    </div>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>{{ $user->created_at->format('M j, Y') }}</flux:table.cell>
                            <flux:table.cell class="text-end">
                                <flux:button size="sm" variant="ghost" :href="route('admin.users.edit', $user)" wire:navigate>
                                    {{ __('Edit') }}
                                </flux:button>
                                @if(!$user->isOwner() && $user->id !== auth()->id())
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        color="red"
                                        wire:click="delete({{ $user->id }})"
                                        wire:confirm="{{ __('Delete this user? They will no longer be able to sign in.') }}"
                                    >
                                        {{ __('Delete') }}
                                    </flux:button>
                                @endif
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="py-12 text-center">
                                <flux:text class="block text-zinc-500 dark:text-zinc-400">{{ __('No users found.') }}</flux:text>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </flux:card>
</div>
