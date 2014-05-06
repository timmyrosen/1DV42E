<?php namespace Framework\Autoloading;

use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \RegexIterator;
use \RecursiveRegexIterator;

class Autoloader {
    /**
     * Path to the cache file.
     * @var  string
     */
    private $path;

    /**
     * All directories and files which will be scanned.
     * @var  array
     */
    private $resources;

    /**
     * Stores all excluded files.
     * @var  array
     */
    private $excludes;

    /**
     * Construct.
     * 
     * Check if a cache file already exists. If
     * not, then create an empty file, scan through all
     * directories and cache the files.
     *
     * Try to load all files and include them in the
     * project.
     * @param  string  $path
     * @param  array   $resources
     * @param  array   $excludes
     */
    public function __construct($path, $resources, $excludes=array()) {
        $this->path = $path;
        $this->resources = $resources;
        $this->excludes = $excludes;

        if (!$this->fileExists()) {
            $this->createCacheFile();
            $this->scanAndCacheFiles();
        }

        $this->loadCache();
    }

    /**
     * Scan through all directories and cache the
     * found files.
     * @return  void
     */
    private function scanAndCacheFiles() {
        foreach ($this->resources as $object) {
            $directory = new RecursiveDirectoryIterator($object);
            $iterator = new RecursiveIteratorIterator($directory);
            $object = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

            $tmp = '';
            foreach ($object as $object) {
                $object = str_replace('\\', '/', $object[0]);
                if (!in_array($object, $this->excludes)) {
                    $tmp .= $object."\n";
                }
            }

            file_put_contents($this->path, $tmp);
        }
    }

    /**
     * Load all cached files and include (by require) them.
     * @return  void
     */
    private function loadCache() {
        $data = file_get_contents($this->path);
        $objects = explode("\n", $data);

        foreach ($objects as $object) {
            if (!empty($object)) {
                require($object);   
            }
        }
    }

    /**
     * Check if the cache file exists.
     * @return  boolean
     */
    private function fileExists() {
        if (is_file($this->path)) {
            return true;
        }
        return false;
    }

    /**
     * Create the cache file.
     * @return  void
     */
    private function createCacheFile() {
        touch($this->path);
    }
}