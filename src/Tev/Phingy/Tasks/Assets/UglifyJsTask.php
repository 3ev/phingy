<?php
namespace Tev\Phingy\Tasks\Assets;

use Task;

/**
 * This task will run a JavaScript file through the UglifyJS compression
 * tool.
 *
 * Requires Uglify 2: https://github.com/mishoo/UglifyJS2.
 */
class UglifyJsTask extends Task
{
    /**
     * The JavaScript file to compress from.
     *
     * @var string
     */
    private $in;

    /**
     * The JavaScript file to compress to.
     *
     * @var string
     */
    private $out;

    /**
     * Whether or not to use a local binary (in node_modules/.bin/) to
     * execute the task.
     *
     * @var boolean
     */
    private $useLocal = false;

    /**
     * Init method.
     *
     * @return void
     */
    public function init() {}

    /**
     * Set the JavaScript file to compress from.
     *
     * @param  string $in
     * @return void
     */
    public function setIn($in)
    {
        $this->in = $in;
    }

    /**
     * Set the JavaScript file to compress to.
     *
     * @param  string $out
     * @return void
     */
    public function setOut($out)
    {
        $this->out = $out;
    }

    /**
     * Whether or not to use a local binary (in node_modules/.bin/) to
     * execute the task.
     *
     * @param  boolean $useLocal
     * @return void
     */
    public function setUseLocal($useLocal)
    {
        $this->useLocal = $useLocal;
    }

    /**
     * The main entry point method.
     *
     * @return void
     */
    public function main()
    {
        $return = null;

        $cmd  = $this->useLocal ? 'node_modules/.bin/' : '';
        $cmd .= 'uglifyjs ';
        $cmd .= escapeshellarg($this->in) . ' ';
        $cmd .= '-o ';
        $cmd .= escapeshellarg($this->out);

        passthru($cmd, $return);

        return $return;
    }
}
