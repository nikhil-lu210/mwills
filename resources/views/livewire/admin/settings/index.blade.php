<div class="space-y-6 w-full max-w-2xl">
    <flux:card>
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="sm" class="mb-4">{{ __('Contact page – Booking calendar') }}</flux:heading>
            <flux:text class="mb-6 text-zinc-500 dark:text-zinc-400">
                {{ __('Set the URL for the embedded calendar (Calendly, TidyCal, etc.) shown in the "Book Directly" section on the contact page. Leave empty to hide the embed.') }}
            </flux:text>

            <flux:field>
                <flux:label for="booking_embed_url">{{ __('Booking / calendar page URL') }}</flux:label>
                <flux:input
                    id="booking_embed_url"
                    type="url"
                    wire:model="booking_embed_url"
                    placeholder="https://calendly.com/yourname/30min"
                />
                <flux:error name="booking_embed_url" />
                <flux:text class="mt-2 block text-xs text-zinc-500 dark:text-zinc-400">
                    {{ __('Example: https://calendly.com/yourname/30min or your TidyCal booking link.') }}
                </flux:text>
            </flux:field>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <flux:button type="submit" variant="primary" class="justify-center">
                    {{ __('Save settings') }}
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>
