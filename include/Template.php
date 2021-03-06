<?php

namespace AmavisWblist;

class Template {

    /**
     * @var \Smarty
     */
    private $template;

    /**
     * @var array
     */
    private $variables = [];

    /**
     * @var Template
     */
    private static $instance;

    public function __construct() {
        $this->template = new \Smarty();
        $this->template->setTemplateDir(dirname(__FILE__) . '/../templates/');
        $this->template->setCacheDir(sys_get_temp_dir());
        $this->template->setCompileDir(sys_get_temp_dir());
        $this->template->setCachingType('none');


    }

    /**
     * @return self
     */
    public function getInstance() {

        if(self::$instance == null) {
            self::$instance = new Template();
        }

        return self::$instance;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function assign($key, $value) {
        if($value instanceof Form) {

            $this->template->assign($key . '_form', $value);
            $value = $value->render();
        }
        $this->template->assign($key, $value);

        $this->variables[$key] = $value;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle($title) {
        $this->assign('title', $title);
    }

    /**
     * @param string $template - file name we want to use for display
     * @return  void
     */
    public function display($template) {

        $this->assign('flash', Flash::get());

        Flash::reset();

        header("Content-Type: text/html; charset=utf-8");
        $this->template->assign('inner_template', $template);
        $this->template->display('master.tpl');

    }
}
