<?php


namespace App\Admin\Forms;


use App\Imports\ImportOTRequest;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CreateOtBatchForm extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = 'Add OT Request';

    protected $projectList = [];

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        // Process import file
        try {
            $data = $request->only([
                'project_id', 'date', 'note',
            ]);
            if (isset($this->projectList[$data['project_id']])) {
                $data['project_code'] = $this->projectList[$data['project_id']];
            }
            $data['requested_by_id'] = Admin::user()->id;
            $import = new ImportOTRequest($data);
            Excel::import($import, request()->file('ot_requests'));
            admin_success('Create OT Request','Processed successfully.');
            return redirect(url('/admin/ot-batches'));
        } catch (\Exception $e) {
            admin_error('Create OT Request', $e->getMessage());
        }
        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        // get manageable project
        $repo = new ProjectRepository(new Project());
        $this->projectList = $repo->getManageableProjects(true);

        $this->select('project_id', __('Project'))
            ->options($this->projectList)->rules('required');
        $this->date('date', __('Date'))
            ->default(date('Y-m-d'))->rules('required');
        $this->text('note', __('Note'));
        $this->file('ot_requests', __('Employee List'))
            ->rules('required|mimes:xlsx,xls');
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        return [

        ];
    }
}
