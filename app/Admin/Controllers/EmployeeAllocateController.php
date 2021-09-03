<?php

namespace App\Admin\Controllers;

use App\Admin\Buttons\ImportButton;
use App\Admin\Forms\ImportEmployeeAllocateForm;
use App\Admin\Grid\AllocateGrid;
use App\Models\EmployeeAllocate;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class EmployeeAllocateController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'EmployeeAllocate';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new AllocateGrid(new EmployeeAllocate());

        $grid->column('id', __('Id'));
        $grid->column('project_code', __('Project'));
        $grid->column('employee_acc', __('Employee'));
        $grid->column('job', __('Job'));
        $grid->column('from_date', __('From date'));
        $grid->column('to_date', __('To date'));
        $grid->column('hours', __('Hours'));
        $grid->column('calendar_effort', __('Calendar effort'));
        $grid->column('billable', __('Billable'));

        $grid->tools(function ($tools){
            $tools->append(new ImportButton(url('/admin/employee-allocates/import')));
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(EmployeeAllocate::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('project_id', __('Project id'));
        $show->field('employee_id', __('Employee id'));
        $show->field('job', __('Job'));
        $show->field('from_date', __('From date'));
        $show->field('to_date', __('To date'));
        $show->field('hours', __('Hours'));
        $show->field('calendar_effort', __('Calendar effort'));
        $show->field('billable', __('Billable'));
        $show->field('note', __('Note'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new EmployeeAllocate());

        $form->number('project_id', __('Project id'));
        $form->number('employee_id', __('Employee id'));
        $form->text('job', __('Job'));
        $form->date('from_date', __('From date'))->default(date('Y-m-d'));
        $form->date('to_date', __('To date'))->default(date('Y-m-d'));
        $form->decimal('hours', __('Hours'))->default(8.00);
        $form->decimal('calendar_effort', __('Calendar effort'))->default(0.00);
        $form->switch('billable', __('Billable'))->default(1);
        $form->text('note', __('Note'));

        return $form;
    }

    public function import(Request $request)
    {
        return Admin::content(function (Content $content) {
            // optional
            $content->header('Import Employee Allocation from Excel');
            // add breadcrumb since v1.5.7
            $content->breadcrumb(
                ['text' => 'Dashboard', 'url' => '/'],
                ['text' => 'Employee allocate', 'url' => '/employee-allocates'],
                ['text' => 'Import employee allocation']
            );
            $content->body(new ImportEmployeeAllocateForm());
        });
    }
}
