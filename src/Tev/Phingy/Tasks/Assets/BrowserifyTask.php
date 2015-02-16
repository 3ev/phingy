<?php
namespace Tev\Phingy\Tasks\Assets;

use Task;

/**
 * This task will compile a Browserify bundle.
 *
 * Requires Browserify: http://browserify.org/.
 */
class BrowserifyTask extends Task
{
    /**
     * The JavaScript file to compile from.
     *
     * @var string
     */
    private $in;

    /**
     * The JavaScript file to compile to.
     *
     * @var string
     */
    private $out;

    /**
     * Space separated list of global transforms to apply.
     *
     * @var string
     */
    private $gTransforms;

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
    public function init()
    {
        $this->gTransforms = '';
    }

    /**
     * Set the JavaScript file to compile from.
     *
     * @param  string $in
     * @return void
     */
    public function setIn($in)
    {
        $this->in = $in;
    }

    /**
     * Set the JavaScript file to compile to.
     *
     * @param  string $out
     * @return void
     */
    public function setOut($out)
    {
        $this->out = $out;
    }

    /**
     * Set the space separated list of global transforms to apply.
     *
     * @param  string $gTransforms
     * @return void
     */
    public function setGlobalTransforms($gTransforms)
    {
        $this->gTransforms = $gTransforms;
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
        $cmd .= 'browserify ';

        foreach (explode(' ', trim($this->gTransforms)) as $trans) {
            if (strlen($trans)) {
                $cmd .= "-g $trans ";
            }
        }

        $cmd .= escapeshellarg($this->in) . ' ';
        $cmd .= '-o ';
        $cmd .= escapeshellarg($this->out);

        passthru($cmd, $return);

        return $return;
    }
}
