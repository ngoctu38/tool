<?php

namespace App\Admin\Controllers;

use App\Admin\Buttons\ImportButton;
use App\Admin\Forms\ImportEmployeeForm;
use App\Admin\Grid\EmployeeGrid;
use App\Imports\EmployeeImport;
use App\Models\Department;
use App\Models\Employee;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class EmployeeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Employee';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $departmentList = Department::query()->get()
            ->pluck('name', 'id')->toArray();

        $grid = new EmployeeGrid(new Employee());
        $grid->setDepartmentList($departmentList);

        $grid->filter(function (Grid\Filter $filter) use ($departmentList) {
            $filter->disableIdFilter();
//            $filter->where(function ($query) {
//                $query->where('email', 'like',"%".$this->input."%");
//            }, 'Email', 'email');
//            $filter->ilike('email', __('Email'));

            $filter->where(function ($query) {
                $subDepartIds = $this->input;
                $query->whereIn('department_id', $subDepartIds)
                    ->orWhereIn('sub_department_id', $subDepartIds);
            }, 'Department', 'sub_department_id')
                ->multipleSelect($departmentList);
        });
        $grid->column('id', __('Id'));
        $grid->column('email', __('Email'))->filter();
        $grid->column('full_name', __('Full name'))->filter();
        $grid->column('department_name', __('Department'))->display(function() {
            if ($this->sub_department_name != $this->department_name) {
                return $this->department_name. '-' .$this->sub_department_name;
            }
            return $this->department_name;
        });

        $grid->column('contract_type', __('Contract type'))->filter()->using([
            Employee::CONTRACT_TYPE_FULL => __('FULL'),
            Employee::CONTRACT_TYPE_OJT => __('OJT'),
            Employee::CONTRACT_TYPE_STUDENT => __('SVTT'),
        ]);
        $grid->column('contract_to', __('Contract to'))
            ->filter('range', 'date');

        $grid->column('status', __('Status'))->bool([
            Employee::STATUS_ACTIVE => __('Active'),
            Employee::STATUS_INACTIVE => __('Inactive')
        ])->filter();

        $grid->tools(function ($tools){
            $tools->append(new ImportButton(url('/admin/employees/import')));
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
        $show = new Show(Employee::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('email', __('Email'));
        $show->field('full_name', __('Full name'));
        $show->field('dob', __('Dob'));
        $show->field('gender', __('Gender'))->using([
            Employee::GENDER_MALE => __('Male'),
            Employee::GENDER_FEMALE => __('Female'),
        ]);
        $show->field('pid', __('Pid'));
        $show->field('mobile', __('Mobile'));
        $show->field('contract_type', __('Contract type'));
        $show->field('contract_from', __('Contract from'));
        $show->field('contract_to', __('Contract to'));
        $show->field('job_code', __('Job code'));
        $show->field('job_level', __('Job level'));
        $show->field('current_team', __('Current team'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Employee());

        $form->email('email', __('Email'));
        $form->text('full_name', __('Full name'));
        $form->date('dob', __('Dob'))->default(date('Y-m-d'));
        $form->select('gender', __('Gender'))->data([
            Employee::GENDER_MALE => __('Male'),
            Employee::GENDER_FEMALE => __('Female'),
        ]);
        $form->text('pid', __('Pid'));
        $form->mobile('mobile', __('Mobile'));
        $form->number('contract_type', __('Contract type'));
        $form->date('contract_from', __('Contract from'))->default(date('Y-m-d'));
        $form->date('contract_to', __('Contract to'))->default(date('Y-m-d'));
        $form->text('job_code', __('Job code'));
        $form->text('job_level', __('Job level'));
        $form->text('current_team', __('Current team'));
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }

    public function import(Request $request)
    {
        return Admin::content(function (Content $content) {
            // optional
            $content->header('Import Employee from Excel');
            // add breadcrumb since v1.5.7
            $content->breadcrumb(
                ['text' => 'Dashboard', 'url' => '/'],
                ['text' => 'Employee management', 'url' => '/employees'],
                ['text' => 'Import employee']
            );
            $content->body(new ImportEmployeeForm());
        });
    }
}
