<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPictureRatingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\livewire\UserList;
use \App\Http\Livewire\CompetitionDetails;
//use the CompetitionCreate.php that is in the app folder


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth', 'verified', 'user')->group(callback: function() {

    Route::get('/create-competition', [App\Http\Controllers\CompetitionController::class, 'create'])->name('competitions.create');
    Route::get('/participating', [App\Http\Controllers\CompetitionController::class, 'participatingList'])->name('competitions.participatingList');
    Route::Post('/participate/{competitionId}', [App\Http\Controllers\CompetitionController::class, 'participate'])->name('competitions.participate');
    Route::post('/wedstrijden/{imageId}/setmain', [ImageController::class, 'setMainImage'])->name('picture.updateMain');
    Route::get('/upload/{id}', [ImageController::class, 'uploadIndex'])->name('upload');
    Route::post('/upload/{id}', [ImageController::class, 'store'])->name('pictures.store');
    Route::post('/wedstrijden/{competitionId}/end', [\App\Http\Controllers\CompetitionController::class, 'endCompetition'])->name('competition.end');
    // mark as read
    Route::post('/markAsRead', [\App\Http\Controllers\NotificationController::class, 'markAsRead'] )->name('notifications.markAllAsRead');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'superAdmin'])->name('dashboard');

Route::get('/wedstrijden/{wedstrijdId}', CompetitionDetails::class)->name('wedstrijden');
Route::post('/wedstrijden/{imageId}', [CompetitionDetails::class, 'deleteSendInWork'])->name('deleteImage');

Route::get('/upload/{id}', [ImageController::class, 'uploadIndex'])->middleware(['auth', 'verified', 'user'])->name('upload');
Route::post('/upload/{id}', [ImageController::class, 'store'])->middleware(['auth'])->name('pictures.store');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/wedstrijden/{competitionId}/submission/{submissionId}', [UserPictureRatingController::class, 'postFeedback'])->name('postFeedback');
});

Route::get('/wedstrijden/{competitionId}/submission/{submissionId}', [\App\Http\Controllers\SubmissionDetailsController::class, 'index'])->name('submissionDetails');

// admin only
Route::middleware('auth', 'verified', 'superAdmin')->group(function(){
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashBoardController::class, 'index'])->name('dashboard');
    Route::get('/ListUser', \App\Http\Livewire\UserList::class)->name('list_users');
    Route::get('/ListUserArchive/{userId}', [\App\Http\Livewire\UserList::class, 'archive'])->name('list_users_archive');

    Route::get('/categoryList', \App\Http\Livewire\ListCategorie::class)->name('listCategory');
    Route::get('/categoryEdit/{id}', \App\Http\Livewire\UpdateCategorie::class)->name('editCategory');
    Route::get('/categoryCreate', \App\Http\Livewire\CreateCategorie::class)->name('createCategory');
    Route::get('/archiveMessage/{id}', \App\Http\Livewire\ArchiveMessage::class)->name('archiveMessage');

    Route::post('/deleteCompetition', [App\Http\Controllers\CompetitionController::class, 'delete'])
        ->name('deleteCompetition');

    Route::post('/uploadBanner', [\App\Http\Controllers\UploadBannerController::class, 'uploadBanner'])->name('uploadBanner');
    Route::get('/uploadBanner', [\App\Http\Controllers\UploadBannerController::class, 'index'])->name('uploadBanner.index');
});

Route::get('/competitions', [App\Http\Controllers\CompetitionController::class, 'index'])->name('competitions.index');

Route::get('/', \App\Http\Livewire\Homepage::class)->name('homepage');

Route::post('/competitions', [App\Http\Controllers\CompetitionController::class, 'store'])->name('competitions.store');


Route::get('/wedstrijden/{wedstrijdId}', CompetitionDetails::class)->name('wedstrijden');
Route::post('/wedstrijden/{imageId}', [CompetitionDetails::class, 'deleteSendInWork'])->name('deleteImage');


Route::get('/accessdenied', [\App\Http\Controllers\ErrorPageController::class, 'error403'])->name('accessdenied');

require __DIR__ . '/auth.php';

Route::get('locale/{locale}', function ($locale) {
    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('locale');

Route::post('/updateStars', [UserPictureRatingController::class, 'store']);
Route::get('/getPlayerStarRatingList', [UserPictureRatingController::class, 'getPlayerStarRatingList'])->name("getPlayerStarRatingList");



Route::post('/makeWinner/{main_photo_id}', [App\Http\Controllers\CompetitionController::class, 'makeWinner'])->name('makeWinner')->middleware(['auth', 'verified', 'user']);


