<?php

namespace App\Admin\Controllers;

use App\Models\UserDepartment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserDepartmentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UserDepartment';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserDepartment());

        $grid->column('id', __('Id'));
        $grid->column('department_id', __('Department id'));
        $grid->column('admin_user_id', __('Admin user id'));
        $grid->column('employee_id', __('Employee id'));
        $grid->column('role_id', __('Role id'));
        $grid->column('role', __('Role'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(UserDepartment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('department_id', __('Department id'));
        $show->field('admin_user_id', __('Admin user id'));
        $show->field('employee_id', __('Employee id'));
        $show->field('role_id', __('Role id'));
        $show->field('role', __('Role'));
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
        $form = new Form(new UserDepartment());

        $form->number('department_id', __('Department id'));
        $form->number('admin_user_id', __('Admin user id'));
        $form->number('employee_id', __('Employee id'));
        $form->number('role_id', __('Role id'));
        $form->text('role', __('Role'));

        return $form;
    }
}
