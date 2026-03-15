<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
                <flux:sidebar.group :heading="__('Messages')" expandable :expanded="request()->routeIs('admin.messages.*')" icon="chat-bubble-left-right" class="grid">
                    <flux:sidebar.item :href="route('admin.messages.index')" :current="request()->routeIs('admin.messages.index')" wire:navigate>
                        {{ __('All Messages') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item :href="route('admin.messages.archived')" :current="request()->routeIs('admin.messages.archived')" wire:navigate>
                        {{ __('Archived') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
                <flux:sidebar.group :heading="__('Blogs')" expandable :expanded="request()->routeIs('admin.posts.*')" icon="document-text" class="grid">
                    <flux:sidebar.item :href="route('admin.posts.index')" :current="request()->routeIs('admin.posts.index')" wire:navigate>
                        {{ __('All Posts') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item :href="route('admin.posts.create')" :current="request()->routeIs('admin.posts.create')" wire:navigate>
                        {{ __('Create Post') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="arrow-top-right-on-square" :href="route('home')" target="_blank">
                    {{ __('View site') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:main class="min-h-[calc(100vh-3.5rem)] lg:min-h-screen w-full flex flex-col items-stretch overflow-auto">
            <div class="mx-4 mt-4 sm:mx-6 sm:mt-6 mb-4">
                @if(isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0)
                    <flux:breadcrumbs class="mb-2">
                        @foreach($breadcrumbs as $crumb)
                            @if(!empty($crumb['href']))
                                <flux:breadcrumbs.item :href="$crumb['href']" wire:navigate>{{ $crumb['label'] }}</flux:breadcrumbs.item>
                            @else
                                <flux:breadcrumbs.item>{{ $crumb['label'] }}</flux:breadcrumbs.item>
                            @endif
                        @endforeach
                    </flux:breadcrumbs>
                @endif
                @if(!empty($title))
                    <flux:heading size="lg">{{ $title }}</flux:heading>
                @endif
            </div>
            @if(session('success'))
                <div class="mx-4 mt-4 sm:mx-6 sm:mt-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <flux:callout variant="success" icon="check-circle">{{ session('success') }}</flux:callout>
                </div>
            @endif
            @if(session('error'))
                <div class="mx-4 mt-4 sm:mx-6 sm:mt-6" x-data="{ show: true }" x-show="show">
                    <flux:callout variant="danger" icon="x-circle">{{ session('error') }}</flux:callout>
                </div>
            @endif
            <div class="px-4 pb-8 sm:px-6">
                {{ $slot }}
            </div>
        </flux:main>

        @fluxScripts
    </body>
</html>
