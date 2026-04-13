<?php

use App\Http\Controllers\Admin\PostImageUploadController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\MessageList;
use App\Livewire\Admin\MessageListArchived;
use App\Livewire\Admin\MessageView;
use App\Livewire\Admin\PostForm;
use App\Livewire\Admin\PostList;
use App\Livewire\Admin\PostView;
use App\Livewire\Admin\SiteSettings;
use App\Livewire\Admin\UserCreate;
use App\Livewire\Admin\UserEdit;
use App\Livewire\Admin\UserList;
use Illuminate\Support\Facades\Route;

// Public site
Route::get('/', [PostController::class, 'home'])->name('home');
Route::view('/services/strategy', 'services.strategy')->name('services.strategy');
Route::view('/services/bd', 'services.bd')->name('services.bd');
Route::view('/services/talent', 'services.talent')->name('services.talent');
Route::view('/services/content', 'services.content')->name('services.content');
Route::get('/intelligence', [PostController::class, 'index'])->name('intelligence');
Route::get('/intelligence/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::view('/about', 'about')->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::view('/contact/thank-you', 'contact-thank-you')->name('contact.thank-you');

// Admin (login required, no registration)
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::livewire('dashboard', Dashboard::class)->name('dashboard');

    Route::post('dashboard/upload-image', PostImageUploadController::class)->name('admin.upload.image');

    Route::livewire('dashboard/users/create', UserCreate::class)->name('admin.users.create');
    Route::livewire('dashboard/users/{user}/edit', UserEdit::class)->name('admin.users.edit');
    Route::livewire('dashboard/users', UserList::class)->name('admin.users.index');

    Route::livewire('dashboard/posts', PostList::class)->name('admin.posts.index');
    Route::livewire('dashboard/posts/create', PostForm::class)->name('admin.posts.create');
    Route::livewire('dashboard/posts/{post}', PostView::class)->name('admin.posts.show');
    Route::livewire('dashboard/posts/{post}/edit', PostForm::class)->name('admin.posts.edit');

    Route::livewire('dashboard/messages', MessageList::class)->name('admin.messages.index');
    Route::livewire('dashboard/messages/archived', MessageListArchived::class)->name('admin.messages.archived');
    Route::livewire('dashboard/messages/{message}', MessageView::class)->name('admin.messages.show');

    Route::livewire('dashboard/settings', SiteSettings::class)->name('admin.settings');
});

require __DIR__.'/settings.php';
