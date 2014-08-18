<?php
namespace Editable;

use Bolt\BaseExtension;
use Composer\Autoload\ClassLoader;

abstract class ExtensionHelper extends BaseExtension
{

    protected $authorized = false;

    protected $resourcePaths;

    protected $config;

    /**
     * (non-PHPdoc)
     *
     * @see \Bolt\BaseExtension::initialize()
     */
    public function initialize()
    {
        $this->resourcePaths = array(
            $this->basepath . '/assets' => __DIR__ . '/assets',
            $this->app['paths']['themepath'] => $this->app['paths']['theme']
        );

        $this->preparePermissions();
        $this->authorized = $this->checkAuth();
    }

    /**
     * Setup permission defaults
     */
    protected function preparePermissions()
    {
        if (! isset($this->config['permissions']) || ! is_array($this->config['permissions'])) {
            $this->config['permissions'] = array(
                'root',
                'admin',
                'developer'
            );
        } else {
            $this->config['permissions'][] = 'root';
        }
    }

    /**
     * Checks user auth status
     *
     * @return boolean True if authenticated
     */
    public function checkAuth()
    {
        $currentUser = $this->app['users']->getCurrentUser();
        $currentUserId = $currentUser['id'];

        foreach ($this->config['permissions'] as $role) {
            if ($this->app['users']->hasRole($currentUserId, $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Creating controller class that implements behaviour of the chosen editor
     *
     * @param string $editor
     *            Name of editor
     * @return EditorController
     */
    protected function createController($editor)
    {
        $controllerclass = '\\' . __NAMESPACE__ . '\\' . ucfirst($editor);
        return new $controllerclass($this);
    }

    /**
     * Add search path finding resources can be added
     *
     * @param string $absolute
     * @param string $relative
     */
    public function addResourcePath($absolute, $relative)
    {
        $this->resourcePaths[$absolute] = $relative;
    }

    /**
     * Flush search paths
     */
    public function flushResourcePaths()
    {
        $this->resourcePaths = array();
    }

    /**
     * Add a HTML asset to rendered output.
     * Searches resources available in $this->resourcePath until found.
     *
     * @param string $type
     *            Type of asset to be added to output. 'CSS' or 'Javascript'
     * @param string $filename
     *            Name of file seeking
     * @param boolean $late
     *            Before </BODY> if true
     * @return boolean Resource successfully added or not
     */
    protected function addAsset($type, $filename, $late = false)
    {
        $addResource = 'add' . $type;
        foreach ($this->resourcePaths as $abspath => $relpath) {
            if (file_exists($abspath . '/' . $filename)) {
                $this->app['extensions']->$addResource($relpath . '/' . $filename, $late);
                return true;
            }
        }
        $this->app['log']->add("Couldn't add Javascript '$filename': File does not exist in 'extensions/" . __NAMESPACE__ . "'.", 2);
        return false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Bolt\BaseExtension::addJavascript()
     */
    public function addJavascript($filename, $late = false)
    {
        return $this->addAsset('Javascript', $filename, $late);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Bolt\BaseExtension::addCSS()
     */
    public function addCSS($filename, $late = false)
    {
        return $this->addAsset('CSS', $filename, $late);
    }
}