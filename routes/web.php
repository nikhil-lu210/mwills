<?php

use App\Http\Controllers\Admin\PostImageUploadController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostController;
use App\Livewire\Admin\ContentAnalytics;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\LeadsList;
use App\Livewire\Admin\MessageListArchived;
use App\Livewire\Admin\MessageView;
use App\Livewire\Admin\PostForm;
use App\Livewire\Admin\PostList;
use App\Livewire\Admin\PostView;
use App\Livewire\Admin\SiteSettingsAnalytics;
use App\Livewire\Admin\SiteSettingsGeneral;
use App\Livewire\Admin\UserCreate;
use App\Livewire\Admin\UserEdit;
use App\Livewire\Admin\UserList;
use App\Models\ConsultationMessage;
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

    Route::livewire('dashboard/analytics/content', ContentAnalytics::class)->name('admin.analytics.content');

    Route::get('dashboard/messages', fn () => redirect()->route('admin.leads.index'))->name('admin.messages.index');
    Route::get('dashboard/messages/archived', fn () => redirect()->route('admin.leads.archived'))->name('admin.messages.archived');
    Route::get('dashboard/messages/{message}', fn (ConsultationMessage $message) => redirect()->route('admin.leads.show', $message));

    Route::livewire('dashboard/leads/archived', MessageListArchived::class)->name('admin.leads.archived');
    Route::livewire('dashboard/leads/{message}', MessageView::class)->name('admin.leads.show');
    Route::livewire('dashboard/leads', LeadsList::class)->name('admin.leads.index');

    Route::redirect('dashboard/settings', '/dashboard/settings/general')->name('admin.settings');
    Route::livewire('dashboard/settings/general', SiteSettingsGeneral::class)->name('admin.settings.general');
    Route::livewire('dashboard/settings/analytics', SiteSettingsAnalytics::class)->name('admin.settings.analytics');
});

require __DIR__.'/settings.php';
