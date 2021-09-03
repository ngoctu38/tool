<?php

namespace App\Admin\Controllers;

use App\Models\Task;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TaskController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Task';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Task());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('code', __('Code'));
        $grid->column('project_id', __('Project id'));
        $grid->column('owner_id', __('Owner id'));
        $grid->column('from_date', __('From date'));
        $grid->column('due_date', __('Due date'));
        $grid->column('status', __('Status'));
        $grid->column('description', __('Description'));
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
        $show = new Show(Task::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('code', __('Code'));
        $show->field('project_id', __('Project id'));
        $show->field('owner_id', __('Owner id'));
        $show->field('from_date', __('From date'));
        $show->field('due_date', __('Due date'));
        $show->field('status', __('Status'));
        $show->field('description', __('Description'));
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
        $form = new Form(new Task());

        $form->text('title', __('Title'));
        $form->text('code', __('Code'));
        $form->number('project_id', __('Project id'));
        $form->number('owner_id', __('Owner id'));
        $form->date('from_date', __('From date'))->default(date('Y-m-d'));
        $form->date('due_date', __('Due date'))->default(date('Y-m-d'));
        $form->text('status', __('Status'));
        $form->textarea('description', __('Description'));

        return $form;
    }
}
