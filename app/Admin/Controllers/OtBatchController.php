<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\CreateOtBatchForm;
use App\Admin\Forms\ImportEmployeeAllocateForm;
use App\Admin\Forms\SimpleForm;
use App\Admin\Grid\OtBatchGrid;
use App\Models\OtBatch;
use App\Models\OtRequest;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use App\Utility\SmallTool;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class OtBatchController extends AdminController
{
    /**
     * @var ProjectRepository
     */
    protected $projectRepo;

    public function __construct(ProjectRepository $projectRepo)
    {
        $this->projectRepo = $projectRepo;
    }

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OtBatch';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new OtBatchGrid(new OtBatch());

        $grid->column('id', __('Id'));
        $grid->column('project_code', __('Project'));
        $grid->column('date', __('Date'));
        $grid->column('note', __('Note'));
        $grid->column('requested_by', __('Requested'));
        $grid->column('status', __('Status'))->display(function ($status) {
            if ($status == OtRequest::STATUS_APPROVED) {
                $badgeClass = 'badge-success';
            } elseif ($status == OtRequest::STATUS_REJECTED) {
                $badgeClass = 'badge-warning';
            } else {
                $badgeClass = 'badge-info';
            }
           return "<span class='badge {$badgeClass}'>".OtRequest::getSttLabel($status)."</span>";
        })->filter(OtRequest::getStatusOptions());

        $grid->column('approved_by', __('Approved/Rejected'))->display(function(){
            if ($this->status == OtRequest::STATUS_APPROVED) {
                return $this->approved_by;
            }
            if ($this->status == OtRequest::STATUS_REJECTED) {
                return $this->rejected_by;
            }
            return 'N/A';
        });
        $grid->column('created_at', __('Created at'))
            ->display(function($created_at) {
                return SmallTool::convertToDate($created_at,'Y-m-d H:i:s');
            });
//        $grid->column('updated_at', __('Updated at'));

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
        $batch = OtBatch::findOrFail($id);

        $show = new Show($batch);

        $show->field('project_code', __('Project'));
        $show->field('date', __('Date'));
        $show->field('note', __('Note'));
        $requestedBy = \App\Models\Admin::findOrFail($batch->requested_by_id);
        $show->field('requested_by', __('Requested by'))->unescape()
            ->as(function () use ($requestedBy) {
            return $requestedBy->username;
        });
        $show->field('status', __('Status'))->unescape()
            ->as(function () use ($batch) {
            if ($batch->status == OtRequest::STATUS_APPROVED) {
                $badgeClass = 'badge-success';
            } elseif ($batch->status == OtRequest::STATUS_REJECTED) {
                $badgeClass = 'badge-warning';
            } else {
                $badgeClass = 'badge-info';
            }
            return "<span class='badge {$badgeClass}'>".OtRequest::getSttLabel($batch->status)."</span>";
        });
        $requests = OtRequest::query()->where('batch_id', $id)->get();
        $rows = [];
        $headers = [
            __('Account'),
            __('Date'),
            __('OT Time'),
            __('ON Time'),
            __('Reason'),
        ];
        foreach ($requests as $item) {
            $rows[] = [
                $item->account,
                $item->date,
                $item->ot_time,
                $item->on_time,
                $item->ot_reason,
            ];
        }
        $table = new Table($headers, $rows);
        $show->field('requested_list', __('Employee list'))
            ->unescape()
            ->as(function () use ($table) {
                return $table->render();
            });

        if ($batch->status == OtRequest::STATUS_REQUESTED) {
            $confirmForm = new SimpleForm();
            $confirmForm->setMethod('post');
            $confirmForm->setAction(url('/admin/ot-batches/confirm/'.$id));
            $confirmForm->setSubmitLabel(__('Approve'));
            $confirmForm->setBtnClass('btn-primary');

            $rejectForm = new SimpleForm();
            $rejectForm->setMethod('post');
            $rejectForm->setAction(url('/admin/ot-batches/reject/'.$id));
            $rejectForm->setSubmitLabel(__('Reject'));
            $rejectForm->setBtnClass('btn-danger');

            $show->field('_', '')->unescape()
                ->as(function () use ($confirmForm, $rejectForm){
                    return $confirmForm->render().$rejectForm->render();
                })->border = false;
        }

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OtBatch());

        $form->select('project_id', __('Project'))
            ->options(Project::all()
                ->pluck('code', 'id')->toArray())->rules('required');
        $form->date('date', __('Date'))
            ->default(date('Y-m-d'))->rules('required');
        $form->text('note', __('Note'));
        $form->file('ot_requests', __('Employee List'))->rules('required');

        return $form;
    }

    public function add()
    {
        return Admin::content(function (Content $content) {
            // optional
            $content->header('Request OT for project');
            // add breadcrumb since v1.5.7
            $content->breadcrumb(
                ['text' => 'Dashboard', 'url' => '/'],
                ['text' => 'Employee allocate', 'url' => '/ot-batches'],
                ['text' => 'Request OT for project']
            );

            $content->body(new CreateOtBatchForm());
        });
    }

    // Allow Admin & Manager only
    public function confirm(Request $request, $id)
    {
        // Check permission
        $batch = OtBatch::findOrFail($id);
        if(!$batch || $batch->status != OtRequest::STATUS_REQUESTED) {
            admin_error('Confirm OT Request', 'Invalid request.');
            return back();
        }
        if (!Admin::user()->isRole('administrator')) {
            $manageableProjects = $this->projectRepo->getManageableProjects(true);
            if (!in_array($batch->project_id, array_keys($manageableProjects))) {
                admin_error('Confirm OT Request', 'Permission Denied.');
                return back();
            }
        }
        // Confirming
        OtRequest::query()->where('batch_id', $id)
            ->update([
                'status' => OtRequest::STATUS_APPROVED,
                'approved_by_id' => Admin::user()->id,
                'updated_at' => Carbon::now(),
            ]);
        $batch->status = OtRequest::STATUS_APPROVED;
        $batch->approved_by_id = Admin::user()->id;
        $batch->save();
        admin_success('OT Request Confirm', 'Request has been approved');
        return back();
    }

    public function reject(Request $request, $id)
    {
        // Check permission
        $batch = OtBatch::findOrFail($id);
        if(!$batch || $batch->status != OtRequest::STATUS_REQUESTED) {
            admin_error('Confirm OT Request', 'Invalid request.');
            return back();
        }
        if (!Admin::user()->isRole('administrator')) {
            $manageableProjects = $this->projectRepo->getManageableProjects(true);
            if (!in_array($batch->project_id, array_keys($manageableProjects))) {
                admin_error('Confirm OT Request', 'Permission Denied.');
                return back();
            }
        }
        // Confirming
        OtRequest::query()->where('batch_id', $id)
            ->update([
                'status' => OtRequest::STATUS_REJECTED,
                'rejected_by_id' => Admin::user()->id,
                'updated_at' => Carbon::now(),
            ]);
        $batch->status = OtRequest::STATUS_REJECTED;
        $batch->rejected_by_id = Admin::user()->id;
        $batch->save();
        admin_success('OT Request Confirm', 'Request has been rejected');
        return back();
    }
}
