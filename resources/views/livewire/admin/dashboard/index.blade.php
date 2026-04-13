<div class="space-y-6 w-full max-w-5xl text-left">
    <header class="space-y-1">
        <flux:subheading>{{ __('Manage your website content and enquiries.') }}</flux:subheading>
    </header>

    <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        <flux:card class="p-6">
            <flux:heading size="sm" class="mb-2">{{ __('Blog Posts') }}</flux:heading>
            <flux:text class="mb-4 text-zinc-500 dark:text-zinc-400">
                {{ __('Create and edit Intelligence Desk articles.') }}
            </flux:text>
            <flux:button :href="route('admin.posts.index')" variant="primary" wire:navigate>
                {{ __('Manage Posts') }}
            </flux:button>
        </flux:card>
        <flux:card class="p-6">
            <flux:heading size="sm" class="mb-2">{{ __('Consultation Messages') }}</flux:heading>
            <flux:text class="mb-4 text-zinc-500 dark:text-zinc-400">
                {{ __('View and manage enquiries from the contact form.') }}
            </flux:text>
            <flux:button :href="route('admin.messages.index')" variant="primary" wire:navigate>
                {{ __('View Messages') }}
            </flux:button>
        </flux:card>
        <flux:card class="p-6">
            <flux:heading size="sm" class="mb-2">{{ __('Users') }}</flux:heading>
            <flux:text class="mb-4 text-zinc-500 dark:text-zinc-400">
                {{ __('Invite teammates and control who can access the dashboard.') }}
            </flux:text>
            <flux:button :href="route('admin.users.index')" variant="primary" wire:navigate>
                {{ __('Manage Users') }}
            </flux:button>
        </flux:card>
    </div>
</div>
