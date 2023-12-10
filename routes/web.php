<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::redirect('/', url("login"))->middleware([App\Http\Middleware\IsNotLogged::class]);

Route::get('/home', function () {

    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
})->middleware([App\Http\Middleware\IsLogged::class])->name("home");

//Exportar Archivosz
Route::get('/export-xml', 'ExportXmlController@exportTableToXml')->middleware([App\Http\Middleware\IsLogged::class])->name('export-xml');
Route::get('/export-to-txt', 'ExportController@exportToTxt')->middleware([App\Http\Middleware\IsLogged::class])->name('export-security');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => [App\Http\Middleware\IsLogged::class]], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');
    // Teams
    Route::delete('team/destroy', 'TeamController@massDestroy')->name('team.massDestroy');
    Route::resource('team', 'TeamController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Alta Restaurante
    Route::delete('alta-restaurantes/destroy', 'AltaRestauranteController@massDestroy')->name('alta-restaurantes.massDestroy');
    Route::resource('alta-restaurantes', 'AltaRestauranteController');

    // Alta Empleados
    Route::delete('alta-empleados/destroy', 'AltaEmpleadosController@massDestroy')->name('alta-empleados.massDestroy');
    Route::post('alta-empleados/parse-csv-import', 'AltaEmpleadosController@parseCsvImport')->name('alta-empleados.parseCsvImport');
    Route::post('alta-empleados/process-csv-import', 'AltaEmpleadosController@processCsvImport')->name('alta-empleados.processCsvImport');
    Route::resource('alta-empleados', 'AltaEmpleadosController');

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => [App\Http\Middleware\IsLogged::class]], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
