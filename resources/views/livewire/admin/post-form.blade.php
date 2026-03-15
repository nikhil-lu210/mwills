<div class="space-y-6 max-w-3xl">
    <flux:button variant="ghost" :href="route('admin.posts.index')" wire:navigate class="-ms-2">
        ← {{ __('Back to Posts') }}
    </flux:button>

    <form wire:submit="save" class="space-y-6">
        <flux:field>
            <flux:label>{{ __('Title') }}</flux:label>
            <flux:input wire:model="title" placeholder="{{ __('Post title') }}" required />
            <flux:error name="title" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Category') }}</flux:label>
            <flux:select wire:model="category" placeholder="{{ __('Select category') }}">
                @foreach(\App\Models\Post::categoryOptions() as $opt)
                    <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
            </flux:select>
            <flux:error name="category" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Excerpt') }}</flux:label>
            <flux:textarea wire:model="excerpt" placeholder="{{ __('Short summary') }}" rows="2" />
            <flux:error name="excerpt" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Body') }}</flux:label>
            <flux:textarea wire:model="body" placeholder="{{ __('Full content (HTML allowed)') }}" rows="12" />
            <flux:error name="body" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Read time (minutes)') }}</flux:label>
            <flux:input type="number" wire:model="read_time_minutes" min="1" max="120" placeholder="5" />
            <flux:error name="read_time_minutes" />
        </flux:field>

        <flux:field>
            <flux:checkbox wire:model="publish" :label="__('Publish now')" />
        </flux:field>

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">{{ $postId ? __('Update Post') : __('Create Post') }}</flux:button>
            <flux:button variant="ghost" :href="route('admin.posts.index')" wire:navigate>{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</div>
