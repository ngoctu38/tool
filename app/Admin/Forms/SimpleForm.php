<?php


namespace App\Admin\Forms;


class SimpleForm
{
    protected $action;
    protected $method = 'POST';
    protected $submitLabel = 'Submit';
    protected $btnClass = '';

    /**
     * @return string
     */
    public function getBtnClass(): string
    {
        return $this->btnClass;
    }

    /**
     * @param string $btnClass
     */
    public function setBtnClass(string $btnClass): void
    {
        $this->btnClass = $btnClass;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action): void
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getSubmitLabel(): string
    {
        return $this->submitLabel;
    }

    /**
     * @param string $submitLabel
     */
    public function setSubmitLabel(string $submitLabel): void
    {
        $this->submitLabel = $submitLabel;
    }


    public function __construct()
    {

    }

    public function render()
    {
        $csrf_field = csrf_field();
        return "
            <div class='col-md-1 inline-block' style='float: right;'>
                <form action='{$this->action}' method='{$this->method}'>
                    {$csrf_field}
                    <button type='submit' class='btn btn-default {$this->btnClass}'><span>{$this->submitLabel}</span></button>
                </form>
            </div>
        ";
    }

}
