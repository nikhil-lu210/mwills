<div class="w-full max-w-4xl space-y-6">
    @include('livewire.admin.settings._tabs')

    <form wire:submit="save" class="space-y-6">
        <flux:card>
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <flux:heading size="sm">{{ __('Google Analytics') }}</flux:heading>
                    <flux:text class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ __('Connect GA4 for public-site tracking and admin dashboard reports. Values are stored in the database; the service account key file is kept in private storage.') }}
                    </flux:text>
                </div>
                <flux:modal.trigger name="ga-analytics-help">
                    <flux:button type="button" size="sm" variant="ghost" class="shrink-0">
                        {{ __('Setup guide') }}
                    </flux:button>
                </flux:modal.trigger>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="space-y-4">
                    <flux:field>
                        <flux:label for="ga4_measurement_id">{{ __('Measurement ID (gtag)') }}</flux:label>
                        <flux:input
                            id="ga4_measurement_id"
                            type="text"
                            wire:model="ga4_measurement_id"
                            placeholder="G-XXXXXXXXXX"
                            autocomplete="off"
                        />
                        <flux:error name="ga4_measurement_id" />
                        <flux:text class="mt-2 block text-xs text-zinc-500 dark:text-zinc-400">
                            {{ __('From GA4 → Admin → Data streams → your web stream. Enables the tracking snippet on the public site.') }}
                        </flux:text>
                    </flux:field>

                    <flux:field>
                        <flux:label for="google_analytics_property_id">{{ __('GA4 property ID (Data API)') }}</flux:label>
                        <flux:input
                            id="google_analytics_property_id"
                            type="text"
                            inputmode="numeric"
                            wire:model="google_analytics_property_id"
                            placeholder="123456789"
                            autocomplete="off"
                        />
                        <flux:error name="google_analytics_property_id" />
                        <flux:text class="mt-2 block text-xs text-zinc-500 dark:text-zinc-400">
                            {{ __('Numeric ID from GA4 → Admin → Property settings → Property details. Used only for server-side reports (not the same as Stream ID).') }}
                        </flux:text>
                    </flux:field>

                    <flux:field>
                        <flux:label for="analytics_cache_ttl">{{ __('Report cache (seconds)') }}</flux:label>
                        <flux:input
                            id="analytics_cache_ttl"
                            type="number"
                            wire:model="analytics_cache_ttl"
                            min="60"
                            max="86400"
                            step="60"
                        />
                        <flux:error name="analytics_cache_ttl" />
                        <flux:text class="mt-2 block text-xs text-zinc-500 dark:text-zinc-400">
                            {{ __('How long to cache dashboard analytics (60–86400). Default 300.') }}
                        </flux:text>
                    </flux:field>
                </div>

                <div class="space-y-4 rounded-lg border border-zinc-200 bg-zinc-50/80 p-4 dark:border-zinc-700 dark:bg-zinc-800/40">
                    <flux:heading size="sm">{{ __('Service account JSON key') }}</flux:heading>
                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                        {{ __('Upload the JSON key from Google Cloud (IAM → Service accounts → Keys). The file is stored outside the web root.') }}
                    </flux:text>

                    @if($this->hasAnalyticsCredentials)
                        <flux:callout variant="success" icon="check-circle" class="text-sm">
                            {{ __('A credentials file is saved. Upload a new file to replace it.') }}
                        </flux:callout>
                    @endif

                    <flux:field>
                        <flux:label for="credentialsFile">{{ __('Upload JSON key') }}</flux:label>
                        <input
                            id="credentialsFile"
                            type="file"
                            wire:model="credentialsFile"
                            accept=".json,application/json"
                            class="block w-full cursor-pointer text-sm text-zinc-600 file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-200 file:px-4 file:py-2 file:text-sm file:font-medium file:text-zinc-800 hover:file:bg-zinc-300 dark:text-zinc-300 dark:file:bg-zinc-600 dark:file:text-zinc-100 dark:hover:file:bg-zinc-500"
                        />
                        @if($credentialsFile)
                            <flux:text class="mt-2 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                {{ $credentialsFile->getClientOriginalName() }} — {{ __('will be saved when you click “Save settings”.') }}
                            </flux:text>
                        @endif
                        <flux:error name="credentialsFile" />
                        <div wire:loading wire:target="credentialsFile" class="mt-2 text-xs text-zinc-500">
                            {{ __('Processing file…') }}
                        </div>
                    </flux:field>

                    @if($this->hasAnalyticsCredentials)
                        <flux:button type="button" wire:click="removeAnalyticsCredentials" wire:confirm="{{ __('Remove the stored service account file? The admin analytics API will stop until you upload a new key.') }}" variant="danger" size="sm" class="w-full justify-center sm:w-auto">
                            {{ __('Remove credentials file') }}
                        </flux:button>
                    @endif
                </div>
            </div>
        </flux:card>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <flux:button type="submit" variant="primary" class="justify-center" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">{{ __('Save settings') }}</span>
                <span wire:loading wire:target="save">{{ __('Saving…') }}</span>
            </flux:button>
        </div>
    </form>

    <flux:modal name="ga-analytics-help" class="max-h-[90vh] w-full max-w-3xl overflow-y-auto md:min-w-[32rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Google Analytics setup') }}</flux:heading>
                <flux:text class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Follow these steps once. You need access to Google Analytics 4 and Google Cloud Console.') }}
                </flux:text>
            </div>

            <div class="space-y-4 text-sm text-zinc-700 dark:text-zinc-300">
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">1. {{ __('Measurement ID (public tracking)') }}</p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-zinc-600 dark:text-zinc-400">
                        <li>{{ __('Open Google Analytics and select your GA4 property.') }}</li>
                        <li>{{ __('Go to Admin → Data collection and modification → Data streams.') }}</li>
                        <li>{{ __('Open your Web stream and copy the Measurement ID (format G-XXXXXXXX). Paste it above.') }}</li>
                    </ul>
                </div>

                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">2. {{ __('Property ID (admin dashboard API)') }}</p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-zinc-600 dark:text-zinc-400">
                        <li>{{ __('In GA4: Admin → Property settings.') }}</li>
                        <li>{{ __('Under “Property details”, copy the numeric Property ID (digits only). This is not the same as the Stream ID on the stream details screen.') }}</li>
                    </ul>
                </div>

                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">3. {{ __('Google Cloud project & Data API') }}</p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-zinc-600 dark:text-zinc-400">
                        <li>{{ __('Open Google Cloud Console and select or create a project.') }}</li>
                        <li>{{ __('APIs & Services → Library → enable “Google Analytics Data API”.') }}</li>
                    </ul>
                </div>

                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">4. {{ __('Service account & JSON key') }}</p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-zinc-600 dark:text-zinc-400">
                        <li>{{ __('IAM & Admin → Service accounts → Create service account (any name).') }}</li>
                        <li>{{ __('Open the account → Keys → Add key → Create new key → JSON. Download the file.') }}</li>
                        <li>{{ __('Upload that file here. It must contain "type": "service_account".') }}</li>
                    </ul>
                </div>

                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">5. {{ __('Grant access in GA4') }}</p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-zinc-600 dark:text-zinc-400">
                        <li>{{ __('Open the JSON file in a text editor and copy the client_email value.') }}</li>
                        <li>{{ __('In GA4: Admin → Property access management → Add users.') }}</li>
                        <li>{{ __('Paste the service account email and assign the Viewer role.') }}</li>
                    </ul>
                </div>

                <flux:callout variant="info" icon="information-circle" class="text-sm">
                    {{ __('After saving, open the Dashboard. It may take a few minutes for GA to show data; use Realtime to verify the Measurement ID.') }}
                </flux:callout>
            </div>

            <div class="flex justify-end border-t border-zinc-200 pt-4 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button type="button" variant="primary">{{ __('Done') }}</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>
</div>
