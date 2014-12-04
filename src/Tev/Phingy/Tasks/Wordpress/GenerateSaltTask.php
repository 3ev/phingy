<?php
namespace Tev\Phingy\Tasks\Wordpress;

use Task, Project;

/**
 * This task will generate a WordPress salt in .env file format, and append
 * a specified file.
 *
 * The idea for this is borrowed from the Bedrock project:
 *
 * https://github.com/roots/bedrock/blob/master/scripts/Roots/Bedrock/Installer.php
 *
 * and is used in the 3ev Wordpress starter:
 *
 * https://github.com/3ev/wordpress-starter
 */
class GenerateSaltTask extends Task
{
    /**
     * Env file absolute path.
     *
     * @var string
     */
    private $path = null;

    /**
     * Salt length.
     *
     * @var integer
     */
    private $length = 64;

    /**
     * Salt key name.
     *
     * @var string
     */
    private $name = null;

    /**
     * Init method.
     *
     * @return void
     */
    public function init() {}

    /**
     * Set env file absolute path.
     *
     * @param  string $path Absolute path to env file
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Set salt length.
     *
     * @param  integer $length Salt length
     * @return void
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * Set salt key name.
     *
     * @param  string $name Salt key name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * The main entry point method.
     *
     * @return void
     */
    public function main()
    {
        if (file_exists($this->path)) {
            $result = file_put_contents(
                $this->path,
                sprintf("%s='%s'\n", $this->name, $salt = $this->generateSalt()),
                FILE_APPEND | LOCK_EX
            );

            if ($result !== false) {
                $this->log("Salt {$this->name} written successfully", Project::MSG_INFO);
            } else {
                $this->log("Could not write to file {$this->path}. Check permissions", Project::MSG_ERR);
            }
        } else {
            $this->log("File {$this->path} does not exist", Project::MSG_ERR);
        }
    }

    /**
     * Generate a random salt string.
     *
     * @return string
     */
    private function generateSalt()
    {
        $chars  = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $chars .= '!@#$%^&*()-_ []{}<>~`+=,.;:/?|';

        $salt = '';
        for ($i = 0; $i < $this->length; $i++) {
          $salt .= substr($chars, rand(0, strlen($chars) - 1), 1);
        }

        return $salt;
    }
}
