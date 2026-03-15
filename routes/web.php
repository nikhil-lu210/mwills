<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

// Public site
Route::view('/', 'home')->name('home');
Route::view('/services/strategy', 'services.strategy')->name('services.strategy');
Route::view('/services/bd', 'services.bd')->name('services.bd');
Route::view('/services/talent', 'services.talent')->name('services.talent');
Route::view('/services/content', 'services.content')->name('services.content');
Route::view('/intelligence', 'placeholder', ['title' => 'The Intelligence Desk', 'message' => 'Blog and insights coming in Phase 3.'])->name('intelligence');
Route::view('/about', 'about')->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::view('/contact/thank-you', 'contact-thank-you')->name('contact.thank-you');

// Auth
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
