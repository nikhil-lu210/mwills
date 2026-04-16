<div class="space-y-6 w-full max-w-5xl">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
                <flux:link href="{{ route('admin.leads.index') }}" wire:navigate variant="ghost" class="-ms-2">
                    ← {{ __('Back to Leads') }}
                </flux:link>
            </flux:text>
            <flux:heading class="mt-1">
                {{ __('Enquiry from :name', ['name' => $message->name]) }}
            </flux:heading>
            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ $message->created_at->format('F j, Y \a\t g:i A') }}
            </flux:text>
        </div>

        <div class="flex flex-wrap gap-2">
            <flux:badge size="sm" :color="$message->status === 'new' ? 'green' : ($message->status === 'contacted' ? 'blue' : 'zinc')">
                @if($message->status === 'new')
                    {{ __('New') }}
                @elseif($message->status === 'contacted')
                    {{ __('Contacted') }}
                @else
                    {{ __('Closed') }}
                @endif
            </flux:badge>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <flux:card class="lg:col-span-2 space-y-6">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Name') }}</flux:text>
                    <flux:heading size="sm">{{ $message->name }}</flux:heading>
                </div>
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Company') }}</flux:text>
                    <flux:heading size="sm">{{ $message->company }}</flux:heading>
                </div>
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Email') }}</flux:text>
                    <flux:link :href="'mailto:'.$message->email" target="_blank">{{ $message->email }}</flux:link>
                </div>
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Area') }}</flux:text>
                    <flux:heading size="sm">{{ $message->area ?? '—' }}</flux:heading>
                </div>
            </div>

            @if($message->message)
                <div class="pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Message') }}</flux:text>
                    <p class="mt-2 whitespace-pre-wrap leading-relaxed text-zinc-900 dark:text-zinc-100">{{ $message->message }}</p>
                </div>
            @endif

            @if($message->first_reply)
                <div class="pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('First reply sent to the sender') }}</flux:text>
                    <p class="mt-2 whitespace-pre-wrap leading-relaxed text-zinc-900 dark:text-zinc-100">{{ $message->first_reply }}</p>
                </div>
            @endif
        </flux:card>

        <div class="space-y-4">
            <flux:card>
                <flux:heading size="sm" class="mb-3">{{ __('Status') }}</flux:heading>
                <div class="flex flex-wrap gap-2">
                    <flux:button size="sm" variant="{{ $message->status === 'new' ? 'primary' : 'ghost' }}" wire:click="updateStatus('new')" class="justify-center">
                        {{ __('New') }}
                    </flux:button>
                    <flux:button size="sm" variant="{{ $message->status === 'contacted' ? 'primary' : 'ghost' }}" wire:click="updateStatus('contacted')" class="justify-center">
                        {{ __('Contacted') }}
                    </flux:button>
                    <flux:button size="sm" variant="{{ $message->status === 'closed' ? 'primary' : 'ghost' }}" wire:click="updateStatus('closed')" class="justify-center">
                        {{ __('Closed') }}
                    </flux:button>
                </div>
            </flux:card>

            <flux:card>
                <flux:heading size="sm" class="mb-2">{{ __('Admin notes') }}</flux:heading>

                @if($message->adminNotes->isNotEmpty())
                    <ul class="mb-3 space-y-2">
                        @foreach($message->adminNotes as $note)
                            <li class="rounded-md border border-zinc-200 bg-white px-3 py-2 text-left text-sm dark:border-zinc-700 dark:bg-zinc-800">
                                <div class="mb-1 flex items-center justify-between gap-2">
                                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                                        {{ $note->user?->name ?? __('Admin') }}
                                    </span>
                                    <span class="text-[11px] text-zinc-400 dark:text-zinc-500">
                                        {{ $note->created_at->format('M j, Y H:i') }}
                                    </span>
                                </div>
                                <p class="whitespace-pre-wrap text-zinc-800 dark:text-zinc-100">{{ trim($note->body) }}</p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <flux:text class="mb-3 text-xs text-zinc-500 dark:text-zinc-400">
                        {{ __('No notes yet. Add your first note below.') }}
                    </flux:text>
                @endif

                <flux:textarea
                    wire:model="newNote"
                    rows="3"
                    placeholder="{{ __('Add a new note about this enquiry…') }}"
                />
                <div class="mt-2 flex items-center gap-2">
                    <flux:button variant="primary" size="sm" wire:click="addNote" class="justify-center">
                        {{ __('Add note') }}
                    </flux:button>
                </div>
            </flux:card>

            @if($message->first_reply === null)
            <flux:card>
                <flux:heading size="sm" class="mb-2">{{ __('Reply by email') }}</flux:heading>
                <flux:text class="mb-3 text-xs text-zinc-500 dark:text-zinc-400">
                    {{ __('Send a one-time reply to this enquiry. The lead will be marked as contacted.') }}
                </flux:text>
                <flux:textarea
                    wire:model="replyBody"
                    rows="5"
                    placeholder="{{ __('Write your reply to :name here…', ['name' => $message->name]) }}"
                />
                <div class="mt-2 flex items-center gap-2">
                    <flux:button
                        variant="primary"
                        size="sm"
                        wire:click="sendReply"
                        class="justify-center"
                    >
                        {{ __('Send reply email') }}
                    </flux:button>
                </div>
            </flux:card>
            @endif
        </div>
    </div>
</div>
