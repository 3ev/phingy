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
     * Init method.
     *
     * @return void
     */
    public function init() {}

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
     * The main entry point method.
     *
     * @return void
     */
    public function main()
    {
        $return = null;

        $cmd  = $this->bundler ? 'bundle exec ' : '';
        $cmd .= 'sass --update ';
        $cmd .= escapeshellarg($this->in) . ':' . escapeshellarg($this->out) . ' ';
        $cmd .= '--style compressed ';
        $cmd .= '--force ';
        $cmd .= '--sourcemap=none';

        passthru($cmd, $return);

        return $return;
    }
}
