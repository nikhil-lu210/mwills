<?php

use App\Http\Controllers\Admin\PostImageUploadController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Public site
Route::view('/', 'home')->name('home');
Route::view('/services/strategy', 'services.strategy')->name('services.strategy');
Route::view('/services/bd', 'services.bd')->name('services.bd');
Route::view('/services/talent', 'services.talent')->name('services.talent');
Route::view('/services/content', 'services.content')->name('services.content');
Route::view('/intelligence', 'placeholder', ['title' => 'The Intelligence Desk', 'message' => 'Blog and insights coming in Phase 3.'])->name('intelligence');
Route::get('/intelligence/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::view('/about', 'about')->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::view('/contact/thank-you', 'contact-thank-you')->name('contact.thank-you');

// Admin (login required, no registration)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');

    Route::post('dashboard/upload-image', PostImageUploadController::class)->name('admin.upload.image');

    Route::livewire('dashboard/posts', App\Livewire\Admin\PostList::class)->name('admin.posts.index');
    Route::livewire('dashboard/posts/create', App\Livewire\Admin\PostForm::class)->name('admin.posts.create');
    Route::livewire('dashboard/posts/{post}', App\Livewire\Admin\PostView::class)->name('admin.posts.show');
    Route::livewire('dashboard/posts/{post}/edit', App\Livewire\Admin\PostForm::class)->name('admin.posts.edit');

    Route::livewire('dashboard/messages', App\Livewire\Admin\MessageList::class)->name('admin.messages.index');
    Route::livewire('dashboard/messages/archived', App\Livewire\Admin\MessageListArchived::class)->name('admin.messages.archived');
    Route::livewire('dashboard/messages/{message}', App\Livewire\Admin\MessageView::class)->name('admin.messages.show');
});

require __DIR__.'/settings.php';
