<?php

namespace App\Admin\Controllers;

use App\Models\Department;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DepartmentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Department';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Department());
        $grid->model()->leftJoin('departments as p',
            'p.id', '=', 'departments.parent_id')
            ->leftJoin('admin_users as ad',
                'ad.id', '=', 'departments.owner_id')
            ->select(['departments.*', 'p.name as parentDepart', 'ad.name as owner'])
            ->orderByDesc('id');

        $grid->column('id', __('Id'));
        $grid->column('parentDepart', __('Parent'));
        $grid->column('name', __('Name'));
        $grid->column('owner', __('Owner'));

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
        $show = new Show(Department::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('parent_id', __('Parent id'));
        $show->field('name', __('Name'));
        $show->field('owner_id', __('Owner id'));
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
        $form = new Form(new Department());
        $parentList = Department::query()->whereNull('parent_id')->get()
            ->pluck('name', 'id')->toArray();
        $form->select('parent_id', __('Parent'))
            ->options([
                '' => '- None -'
            ] + $parentList);
        $form->text('name', __('Name'));
        $managerList = Administrator::query()
            ->join('admin_role_users', 'admin_role_users.user_id',
                '=', 'admin_users.id')
            ->whereIn('admin_role_users.role_id', [1,2]) // admin & manager
            ->get(['admin_users.id as id', 'admin_users.name as name'])
            ->pluck('name', 'id')->toArray();
        $form->select('owner_id', __('Owner'))
            ->options($managerList);

        return $form;
    }
}
