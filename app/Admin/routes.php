<?php

use Encore\Admin\Facades\Admin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;


Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->match(['get', 'post'],'/employees/import', 'EmployeeController@import')
        ->name('admin.employee.import');
    $router->get('/employee-allocates/import',
        'EmployeeAllocateController@import');

    Route::group([
        'middleware' => 'admin.permission:check,edit-post,create-post,delete-post',
    ], function ($router) {

    $router->resource('employees', EmployeeController::class);
    $router->resource('projects', ProjectController::class);
    $router->resource('tasks', TaskController::class);
    $router->resource('departments', DepartmentController::class);
    $router->resource('user-departments', UserDepartmentController::class);
    $router->resource('employee-allocates', EmployeeAllocateController::class);
    $router->resource('ot-requests', OtRequestController::class);
    $router->resource('time-sheets', TimeSheetController::class);
    $router->resource('ot-batches', OtBatchController::class);
    });

    $router->get('/ot-batches/create',
        'OtBatchController@add');
    // Approve & reject OT Request
    Route::group([
        'middleware' => 'admin.permission:allow,administrator,manager',
    ], function ($router) {
        $router->post('/ot-batches/confirm/{id}', 'OtBatchController@confirm');
        $router->post('/ot-batches/reject/{id}', 'OtBatchController@reject');
    });


});
