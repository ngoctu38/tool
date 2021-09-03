<?php

namespace App\Admin\Controllers;

use App\Models\Admin;
use App\Models\Project;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Date;

class ProjectController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Project';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Project());
        $grid->model()->leftJoin('admin_users', 'admin_users.id',
            '=', 'projects.owner_id');
        $grid->model()->select(['projects.*', 'admin_users.name as owner_name']);

        $grid->column('status', __('Status'))
            ->display(function ($status) {
                return "<span class='badge'>$status</span>";
            })->filter(Project::getStatusOptions());
        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('code', __('Code'));
        $grid->column('owner_name', __('Owner'))->filter();
        $grid->column('from_date', __('From date'));
        $grid->column('to_date', __('To date'));

        $grid->column('rate', __('Rate'));
//        $grid->column('description', __('Description'));
        $grid->filter(function (Grid\Filter $filter){
//            $filter->like('title', 'Title');
            $filter->where(function ($query) {
                $query->where('projects.title', 'like', "%{$this->input}%")
                    ->orWhere('projects.code', 'like', "%{$this->input}%");

            }, __('Keyword'));
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
        $show = new Show(Project::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('code', __('Code'));
        $show->field('owner.name', __('Owner'));
        $show->field('from_date', __('From date'));
        $show->field('to_date', __('To date'));
        $show->field('status', __('Status'));
        $show->field('rate', __('Rate'));
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
        $form = new Form(new Project());

        $form->text('title', __('Title'));
        $form->text('code', __('Code'));
//        $form->hidden('owner_id', __('Owner id'));
        $form->select('owner_id', __('Manager'))
            ->options(Administrator::all()->pluck('name', 'id'));
        $form->select('pm_id', __('Project Manager'))
            ->options(Administrator::all()->pluck('name', 'id'));
        $form->date('from_date', __('From date'))->default(date('Y-m-d'));
        $form->date('to_date', __('To date'))->default(date('Y-m-d'));
        $form->select('status', __('Status'))
            ->options(Project::getStatusOptions());
        $form->number('rate', __('Rate'));
        $form->textarea('description', __('Description'));

        return $form;
    }
}
