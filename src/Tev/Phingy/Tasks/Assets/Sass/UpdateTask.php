<?php
namespace Tev\Phingy\Tasks\Assets\Sass;

use Task;

/**
 * This task will compile SASS files to CSS.
 *
 * Requires SASS: http://sass-lang.com/.
 */
class UpdateTask extends Task
{
    /**
     * The SASS file to compile from.
     *
     * @var string
     */
    private $in;

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
    public function init()
    {
        $this->loadPaths = '';
    }

    /**
     * Set the SASS file to compile from.
     *
     * @param  string $in
     * @return void
     */
    public function setIn($in)
    {
        $this->in = $in;
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
     * @param s tring $loadPaths Space-separated load paths
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
        $cmd .= '--update ';
        $cmd .= escapeshellarg($this->in) . ':' . escapeshellarg($this->out) . ' ';
        $cmd .= '--style compressed ';
        $cmd .= '--force ';
        $cmd .= '--sourcemap=none';

        passthru($cmd, $return);

        return $return;
    }
}
