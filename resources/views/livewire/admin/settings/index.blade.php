<div class="space-y-6 w-full max-w-2xl">
    <form wire:submit="save" class="space-y-6">
        <flux:card>
            <flux:heading size="sm" class="mb-4">{{ __('Contact page – Booking calendar') }}</flux:heading>
            <flux:text class="mb-6 text-zinc-500 dark:text-zinc-400">
                {{ __('Set the URL for the booking page shown on the contact page. Leave empty to hide the button.') }}
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
        </flux:card>

        <flux:card>
            <flux:heading size="sm" class="mb-2">{{ __('Email sending') }}</flux:heading>
            <flux:text class="mb-6 text-xs text-zinc-500 dark:text-zinc-400">
                {{ __('Configure how the system sends emails. These settings override the values from .env.') }}
            </flux:text>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label for="mail_mailer">{{ __('Mailer') }}</flux:label>
                    <flux:input id="mail_mailer" type="text" wire:model="mail_mailer" placeholder="smtp" />
                    <flux:error name="mail_mailer" />
                </flux:field>

                <flux:field>
                    <flux:label for="mail_host">{{ __('Host') }}</flux:label>
                    <flux:input id="mail_host" type="text" wire:model="mail_host" placeholder="smtp.your-provider.com" />
                    <flux:error name="mail_host" />
                </flux:field>

                <flux:field>
                    <flux:label for="mail_port">{{ __('Port') }}</flux:label>
                    <flux:input id="mail_port" type="number" wire:model="mail_port" placeholder="587" />
                    <flux:error name="mail_port" />
                </flux:field>

                <flux:field>
                    <flux:label for="mail_username">{{ __('Username') }}</flux:label>
                    <flux:input id="mail_username" type="text" wire:model="mail_username" />
                    <flux:error name="mail_username" />
                </flux:field>

                <flux:field>
                    <flux:label for="mail_password">{{ __('Password') }}</flux:label>
                    <flux:input id="mail_password" type="password" wire:model="mail_password" />
                    <flux:error name="mail_password" />
                </flux:field>

                <flux:field>
                    <flux:label for="mail_from_address">{{ __('From address') }}</flux:label>
                    <flux:input id="mail_from_address" type="email" wire:model="mail_from_address" />
                    <flux:error name="mail_from_address" />
                </flux:field>

                <flux:field>
                    <flux:label for="mail_from_name">{{ __('From name') }}</flux:label>
                    <flux:input id="mail_from_name" type="text" wire:model="mail_from_name" />
                    <flux:error name="mail_from_name" />
                </flux:field>
            </div>
        </flux:card>

        <flux:card>
            <flux:heading size="sm" class="mb-2">{{ __('Enquiry notifications') }}</flux:heading>
            <flux:text class="mb-6 text-xs text-zinc-500 dark:text-zinc-400">
                {{ __('Choose where new contact-form enquiries should be delivered.') }}
            </flux:text>

            <flux:field>
                <flux:label for="enquiry_recipient_email">{{ __('Enquiry recipient email') }}</flux:label>
                <flux:input id="enquiry_recipient_email" type="email" wire:model="enquiry_recipient_email" />
                <flux:error name="enquiry_recipient_email" />
                <flux:text class="mt-2 block text-xs text-zinc-500 dark:text-zinc-400">
                    {{ __('New enquiries from the contact form will be sent to this address.') }}
                </flux:text>
            </flux:field>
        </flux:card>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <flux:button type="submit" variant="primary" class="justify-center">
                {{ __('Save settings') }}
            </flux:button>
        </div>
    </form>
</div>
