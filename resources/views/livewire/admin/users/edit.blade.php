<div class="space-y-6 w-full max-w-2xl">
    <div>
        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
            <flux:link :href="route('admin.users.index')" wire:navigate variant="ghost" class="-ms-2">
                ← {{ __('Back to All Users') }}
            </flux:link>
        </flux:text>
        <flux:text class="mt-1 block text-sm text-zinc-500 dark:text-zinc-400">
            @if($user->isOwner())
                {{ __('Update name, email, or password for the primary admin. Status is always active.') }}
            @else
                {{ __('Update profile, sign-in access, or set a new password (leave password blank to keep the current one).') }}
            @endif
        </flux:text>
    </div>

    <flux:card>
        <form wire:submit="save" class="space-y-6">
            <flux:field>
                <flux:label>{{ __('Name') }}</flux:label>
                <flux:input wire:model="name" type="text" autocomplete="name" required />
                <flux:error name="name" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Email') }}</flux:label>
                <flux:input wire:model="email" type="email" autocomplete="email" required />
                <flux:error name="email" />
            </flux:field>
            @if(!$user->isOwner())
                <flux:field>
                    <flux:checkbox wire:model="is_active" :label="__('Active (user can sign in)')" />
                    <flux:error name="is_active" />
                </flux:field>
            @endif
            <flux:field>
                <flux:label>{{ __('New password') }}</flux:label>
                <flux:input wire:model="password" type="password" autocomplete="new-password" viewable />
                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Leave blank to keep the current password.') }}</flux:text>
                <flux:error name="password" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Confirm new password') }}</flux:label>
                <flux:input wire:model="password_confirmation" type="password" autocomplete="new-password" viewable />
                <flux:error name="password_confirmation" />
            </flux:field>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end sm:gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <flux:button variant="ghost" :href="route('admin.users.index')" wire:navigate class="justify-center sm:w-auto">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary" class="justify-center sm:w-auto">
                    {{ __('Save changes') }}
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>
