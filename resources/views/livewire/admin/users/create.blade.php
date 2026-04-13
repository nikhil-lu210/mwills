<div class="space-y-6 w-full max-w-2xl">
    <div>
        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
            <flux:link :href="route('admin.users.index')" wire:navigate variant="ghost" class="-ms-2">
                ← {{ __('Back to All Users') }}
            </flux:link>
        </flux:text>
        <flux:text class="mt-1 block text-sm text-zinc-500 dark:text-zinc-400">
            {{ __('Create a new dashboard user with their own login. Inactive users cannot sign in until activated.') }}
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
            <flux:field>
                <flux:label>{{ __('Password') }}</flux:label>
                <flux:input wire:model="password" type="password" autocomplete="new-password" viewable required />
                <flux:error name="password" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Confirm password') }}</flux:label>
                <flux:input wire:model="password_confirmation" type="password" autocomplete="new-password" viewable required />
                <flux:error name="password_confirmation" />
            </flux:field>
            <flux:field>
                <flux:checkbox wire:model="is_active" :label="__('Active (user can sign in)')" />
                <flux:error name="is_active" />
            </flux:field>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end sm:gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <flux:button variant="ghost" :href="route('admin.users.index')" wire:navigate class="justify-center sm:w-auto">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary" class="justify-center sm:w-auto">
                    {{ __('Create user') }}
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>
