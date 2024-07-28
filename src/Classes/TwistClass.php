<?php

namespace Obelaw\Twist\Classes;

class TwistClass
{
    private $panel = null;
    private string $path = 'erp-o';
    private string $prefixTable = 'obelaw_';
    private array $modules = [];

    /**
     * Get the value of path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the value of modules
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Set the value of modules
     *
     * @return  self
     */
    public function resetModules()
    {
        $this->modules = [];

        return $this;
    }

    /**
     * Set the value of modules
     *
     * @return  self
     */
    public function setModules($modules)
    {
        $this->modules = $modules;

        return $this;
    }

    /**
     * Set the value of module
     *
     * @return  self
     */
    public function appendModule($module)
    {
        array_push($this->modules, $module);

        return $this;
    }

    /**
     * Get the value of panel
     */
    public function getPanel()
    {
        return $this->panel;
    }

    /**
     * Set the value of panel
     *
     * @return  self
     */
    public function setPanel($panel)
    {
        $this->panel = $panel;

        return $this;
    }

    /**
     * Get the value of prefixTable
     */ 
    public function getPrefixTable()
    {
        return $this->prefixTable;
    }

    /**
     * Set the value of prefixTable
     *
     * @return  self
     */ 
    public function setPrefixTable($prefixTable)
    {
        $this->prefixTable = $prefixTable;

        return $this;
    }
}
