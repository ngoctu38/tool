<?php

namespace App\Admin\Forms;

use App\Imports\EmployeeAllocateImport;
use App\Imports\EmployeeImport;
use App\Imports\ImportOTRequest;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportEmployeeAllocateForm extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = '';

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
            $import = new EmployeeAllocateImport();
            Excel::import($import, request()->file('employee_upload_file'));
            admin_success('Import Employee Allocation','Processed successfully.');
            return redirect(url('/admin/employee-allocates'));
        } catch (\Exception $e) {
            admin_error('Import Employee Allocation', $e->getMessage());
        }
        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->file('employee_upload_file', 'Select File')
            ->rules('required')->rules('mimes:xlsx,xls');
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
