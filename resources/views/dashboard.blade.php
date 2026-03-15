<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <flux:heading>{{ __('Dashboard') }}</flux:heading>
        <flux:subheading>{{ __('Manage your website content and enquiries.') }}</flux:subheading>

        <div class="grid gap-4 md:grid-cols-2">
            <flux:card class="p-6">
                <flux:heading size="sm" class="mb-2">{{ __('Blog Posts') }}</flux:heading>
                <flux:text class="mb-4 text-zinc-500 dark:text-zinc-400">{{ __('Create and edit Intelligence Desk articles.') }}</flux:text>
                <flux:button :href="route('admin.posts.index')" variant="primary" wire:navigate>
                    {{ __('Manage Posts') }}
                </flux:button>
            </flux:card>
            <flux:card class="p-6">
                <flux:heading size="sm" class="mb-2">{{ __('Consultation Messages') }}</flux:heading>
                <flux:text class="mb-4 text-zinc-500 dark:text-zinc-400">{{ __('View and manage enquiries from the contact form.') }}</flux:text>
                <flux:button :href="route('admin.messages.index')" variant="primary" wire:navigate>
                    {{ __('View Messages') }}
                </flux:button>
            </flux:card>
        </div>
    </div>
</x-layouts::app>
