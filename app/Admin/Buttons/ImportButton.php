<?php


namespace App\Admin\Buttons;


use Encore\Admin\Grid\Tools\AbstractTool;

class ImportButton extends AbstractTool
{
    /**
     * @var string
     */
    protected $importUrl;

    /**
     * ImportButton constructor.
     * @param $importUrl
     */
    public function __construct($importUrl)
    {
        $this->importUrl = $importUrl;
    }

    /**
     * Render CreateButton.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->grid->showCreateBtn()) {
            return '';
        }

        $btnLabel = trans('admin.import');

        return <<<EOT

<div class="btn-group pull-right grid-import-btn" style="margin-right: 10px">
    <a href="{$this->importUrl}" class="btn btn-sm btn-success" title="{$btnLabel}">
        <i class="fa fa-upload"></i><span class="hidden-xs">&nbsp;&nbsp;{$btnLabel}</span>
    </a>
</div>

EOT;
    }
}
