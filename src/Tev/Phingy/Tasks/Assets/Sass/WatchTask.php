<?php
namespace Tev\Phingy\Tasks\Assets\Sass;

use Task;

/**
 * This task will watch SASS files, and compile them to CSS when they're
 * changed.
 *
 * Requires SASS: http://sass-lang.com/.
 */
class WatchTask extends Task
{
    /**
     * The SASS file to watch.
     *
     * @var string
     */
    private $watch;

    /**
     * The CSS file to compile to.
     *
     * @var string
     */
    private $out;

    /**
     * Whether or not to use bundler to execute the task.
     *
     * @var boolean
     */
    private $bundler = false;

    /**
     * Space separated load paths.
     *
     * @var string
     */
    private $loadPaths;

    /**
     * Init method.
     *
     * @return void
     */
    public function init() {}

    /**
     * Set the SASS file to watch.
     *
     * @param  string $watch
     * @return void
     */
    public function setWatch($watch)
    {
        $this->watch = $watch;
    }

    /**
     * Set the CSS file to compile to.
     *
     * @param  string $out
     * @return void
     */
    public function setOut($out)
    {
        $this->out = $out;
    }

    /**
     * Set whether or not to use bundler to execute the task.
     *
     * @param  boolean $bundler
     * @return void
     */
    public function setBundler($bundler)
    {
        $this->bundler = $bundler;
    }

    /**
     * Set additional load paths.
     *
     * Space separated.
     *
     * @param  string $loadPaths Space-separated load paths
     * @return void
     */
    public function setLoadPaths($loadPaths)
    {
        $this->loadPaths = $loadPaths;
    }

    /**
     * The main entry point method.
     *
     * @return void
     */
    public function main()
    {
        $return = null;

        $cmd  = $this->bundler ? 'bundle exec ' : '';
        $cmd .= 'sass ';
        foreach (explode(' ', trim($this->loadPaths)) as $path) {
            if (strlen($path)) {
                $cmd .= "--load-path $path ";
            }
        }
        $cmd .= '--watch ';
        $cmd .= escapeshellarg($this->watch) . ':' . escapeshellarg($this->out) . ' ';
        $cmd .= '--style expanded ';
        $cmd .= '--sourcemap=none';

        passthru($cmd, $return);

        return $return;
    }
}
