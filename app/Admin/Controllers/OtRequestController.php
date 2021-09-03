<?php

namespace App\Admin\Controllers;

use App\Models\OtRequest;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OtRequestController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OtRequest';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OtRequest());

        $grid->column('id', __('Id'));
        $grid->column('date', __('Date'));
        $grid->column('day_type', __('Day type'));
        $grid->column('employee_id', __('Employee id'));
        $grid->column('account', __('Account'));
        $grid->column('project_id', __('Project id'));
        $grid->column('project_code', __('Project code'));
        $grid->column('entry_time', __('Entry time'));
        $grid->column('leave_time', __('Leave time'));
        $grid->column('ot_time', __('Ot time'));
        $grid->column('on_time', __('On time'));
        $grid->column('ot_reason', __('Ot reason'));
        $grid->column('confirmed_by_id', __('Confirmed by id'));
        $grid->column('approved_by_id', __('Approved by id'));
        $grid->column('rejected_by_id', __('Rejected by id'));
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
        $show = new Show(OtRequest::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date', __('Date'));
        $show->field('day_type', __('Day type'));
        $show->field('employee_id', __('Employee id'));
        $show->field('account', __('Account'));
        $show->field('project_id', __('Project id'));
        $show->field('project_code', __('Project code'));
        $show->field('entry_time', __('Entry time'));
        $show->field('leave_time', __('Leave time'));
        $show->field('ot_time', __('Ot time'));
        $show->field('on_time', __('On time'));
        $show->field('ot_reason', __('Ot reason'));
        $show->field('confirmed_by_id', __('Confirmed by id'));
        $show->field('approved_by_id', __('Approved by id'));
        $show->field('rejected_by_id', __('Rejected by id'));
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
        $form = new Form(new OtRequest());

        $form->date('date', __('Date'))->default(date('Y-m-d'));
        $form->switch('day_type', __('Day type'))->default(1);
        $form->number('employee_id', __('Employee id'));
        $form->text('account', __('Account'));
        $form->number('project_id', __('Project id'));
        $form->text('project_code', __('Project code'));
        $form->datetime('entry_time', __('Entry time'))->default(date('Y-m-d H:i:s'));
        $form->datetime('leave_time', __('Leave time'))->default(date('Y-m-d H:i:s'));
        $form->decimal('ot_time', __('Ot time'))->default(0.00);
        $form->decimal('on_time', __('On time'))->default(0.00);
        $form->textarea('ot_reason', __('Ot reason'));
        $form->number('confirmed_by_id', __('Confirmed by id'));
        $form->number('approved_by_id', __('Approved by id'));
        $form->number('rejected_by_id', __('Rejected by id'));

        return $form;
    }
}
