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
     * @param  string   $path
     * @param  array    $resources
     * @param  array    $excludes
     * @param  boolean  $cacheFiles
     */
    public function __construct($path, $resources, $excludes=array(), $cacheFiles=true) {
        $this->path = $path;
        $this->resources = $resources;
        $this->excludes = $excludes;

        // if the user wishes not to load from cache,
        // then scan and execute all files.
        if (!$cacheFiles) {
            $files = $this->scanFiles();
            $this->executeFiles($files);
            return true;
        }

        // if the file exists, then load all files
        // and execute them. If not, create the cache
        // file, scan for all files, execute and then
        // save to cache.
        if ($this->fileExists()) {
            $files = $this->loadFiles();
            $this->executeFiles($files);
            return true;
        } else {
            $this->createCacheFile();
            $files = $this->scanFiles();
            $this->executeFiles($files);
            $this->saveCache($files);
            return true;
        }
    }

    /**
     * Scan through all directories and cache the
     * found files.
     * @return  void
     */
    private function scanFiles() {
        foreach ($this->resources as $object) {
            $directory = new RecursiveDirectoryIterator($object);
            $iterator = new RecursiveIteratorIterator($directory);
            $objects = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

            $files = array();
            foreach ($objects as $object) {
                $object = str_replace('\\', '/', $object[0]);
                if (!in_array($object, $this->excludes)) {
                    $files[] = $object;
                }
            }

            return $files;
        }
    }

    /**
     * Save files to cache.
     * @param   array  $files
     * @return  void
     */
    private function saveCache($files) {
        $tmp = '';

        foreach ($files as $file) {
            $tmp .= $file."\n";
        }

        file_put_contents($this->path, $tmp);
    }

    /**
     * Load all cached files.
     * @return  array
     */
    private function loadFiles() {
        $data = file_get_contents($this->path);
        $files = explode("\n", $data);

        return $files;
    }

    /**
     * Execute all files.
     * @param   array  $files
     * @return  void
     */
    private function executeFiles($files) {
        foreach ($files as $file) {
            if (!empty($file)) {
                require($file);   
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