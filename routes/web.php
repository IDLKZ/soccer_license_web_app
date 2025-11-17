<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\RoleManagement;
use App\Livewire\Admin\SeasonManagement;
use App\Livewire\Admin\CategoryDocumentManagement;
use App\Livewire\Admin\DocumentManagement;
use App\Livewire\Admin\LeagueManagement;
use App\Livewire\Admin\ClubManagement as AdminClubManagement;
use App\Livewire\Admin\ApplicationStatusCategoryManagement;
use App\Livewire\Club\ClubManagement;
use App\Livewire\Admin\ApplicationStatusManagement;
use App\Livewire\Admin\ClubTeamManagement;
use App\Livewire\Admin\LicenceManagement;
use App\Livewire\Admin\LicenceRequirementManagement;
use App\Livewire\Admin\LicenceDeadlineManagement;
use App\Livewire\Club\MyClubs;
use App\Livewire\Club\MyLicences;
use App\Livewire\Club\SingleLicenceDetail;
use App\Livewire\Club\MyApplications;
use App\Livewire\Club\MyApplicationDetail;
use App\Livewire\Club\MyCriterias;
use App\Livewire\Department\DepartmentApplications;
use App\Livewire\Department\DepartmentApplicationDetail;
use App\Livewire\Department\DepartmentCriterias;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', Login::class)->name('login');
    Route::get('/login', Login::class);
});

// Authenticated routes
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', UserManagement::class)->name('users');
        Route::get('/roles', RoleManagement::class)->name('roles');
        Route::get('/seasons', SeasonManagement::class)->name('seasons');
        Route::get('/category-documents', CategoryDocumentManagement::class)->name('category-documents');
        Route::get('/documents', DocumentManagement::class)->name('documents');
        Route::get('/leagues', LeagueManagement::class)->name('leagues');
        Route::get('/clubs', AdminClubManagement::class)->name('clubs');
        Route::get('/club-teams', ClubTeamManagement::class)->name('club-teams');
        Route::get('/licences', LicenceManagement::class)->name('licences');
        Route::get('/licence-requirements', LicenceRequirementManagement::class)->name('licence-requirements');
        Route::get('/licence-deadlines', LicenceDeadlineManagement::class)->name('licence-deadlines');
        Route::get('/application-criteria-deadlines', \App\Livewire\Admin\ApplicationCriteriaDeadlineManagement::class)->name('application-criteria-deadlines');
        Route::get('/application-status-categories', ApplicationStatusCategoryManagement::class)->name('application-status-categories');
        Route::get('/application-statuses', ApplicationStatusManagement::class)->name('application-statuses');
    });

    // Club routes
    Route::prefix('club')->name('club.')->group(function () {
        Route::get('/management', MyClubs::class)->name('management');
        Route::get('/club-management', ClubManagement::class)->name('club-management');
        Route::get('/applications', MyApplications::class)->name('applications');
        Route::get('/application/{application_id}', MyApplicationDetail::class)->name('application.detail');
        Route::get('/criterias', MyCriterias::class)->name('criterias');
        Route::get('/licences', MyLicences::class)->name('licences');
        Route::get('/licence/{id}', SingleLicenceDetail::class)->name('licence-detail');
    });

    // Department routes
    Route::prefix('department')->name('department.')->group(function () {
        Route::get('/applications', DepartmentApplications::class)->name('applications');
        Route::get('/application/{application_id}', DepartmentApplicationDetail::class)->name('application.detail');
        Route::get('/criterias', DepartmentCriterias::class)->name('criterias');
    });

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
