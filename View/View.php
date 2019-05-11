<?php

namespace View;

use View\Engine\EngineInterface;
use View\ViewFactory;

class View
{
    private $factory;
    private $engine;

    private $data;
    private $path;

    function __construct(ViewFactory $factory, EngineInterface $engine)
    {
        $this->factory = $factory;
        $this->engine = $engine;
    }

    public function make($view = null, $data = [])
    {
        $this->data = $data;
        $this->path = $this->factory->find($view);
        return $this;
    }

    public function render()
    {
        require 'helpers.php';
        return $this->factory->exec($this->path, $this->data);
    }
}