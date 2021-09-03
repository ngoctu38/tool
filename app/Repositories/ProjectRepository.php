<?php


namespace App\Repositories;


use App\Models\Project;
use Encore\Admin\Facades\Admin;

class ProjectRepository
{
    protected $model;
    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function getManageableProjects($toPluck = false) {
        $query = $this->model->newQuery();
        if (!Admin::user()->isRole('administrator')) {
            // Get manageable project
            $query->where(function ($query) {
                return $query->where('owner_id', Admin::user()->id)
                        ->orWhere('pm_id', Admin::user()->id);
            });
        }
        $items = $query->get();
        if ($toPluck) {
            $items = $items->pluck('code', 'id')->toArray();
        }
        return $items;
    }
}
