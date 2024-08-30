<?php

namespace Obelaw\Twist\Classes;

use Obelaw\Twist\Base\BaseAddon;

class TwistClass
{
    private $panel = null;
    private string $path = 'erp-o';
    private string $prefixTable = 'obelaw_';
    private array $addons = [];

    public function make(): static
    {
        return $this;
    }

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
     * Get the value of addons
     */
    public function getAddons()
    {
        return $this->addons;
    }

    /**
     * Set the value of modules
     *
     * @return  self
     */
    public function appendAddons(array $addons)
    {
        foreach ($addons as $addon) {
            $this->appendAddon($addon);
        }

        return $this;
    }

    /**
     * Set the value of addon
     *
     * @return  self
     */
    public function appendAddon(BaseAddon $addon)
    {
        array_push($this->addons, $addon);

        return $this;
    }

    /**
     * Set the value of addons
     *
     * @return  self
     */
    public function resetAddons()
    {
        $this->addons = [];

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
