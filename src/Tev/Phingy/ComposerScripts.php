<?php
namespace Tev\Phingy;

use Composer\Script\Event;

/**
 * Installation scripts.
 */
class ComposerScripts
{
    /**
     * @var array $templates Array of available project templates
     */
    private static $templates = array(
        'default',
        'typo3',
        'laravel'
    );

    /**
     * Create template build files if they don't exist.
     *
     * @param $event
     * @return void
     */
    public static function postInstall(Event $event)
    {
        // Create core build file if it doesn't exist
        if (!file_exists('build.xml')) {
            $event->getIO()->write('build.xml does not exist, creating template');

            symlink('vendor/3ev/phingy/scripts/build.xml', 'build.xml');
        } else {
            $event->getIO()->write('build.xml exists, template not needed');
        }

        // Create project build file if it doesn't exist
        if (!file_exists('config/project.xml')) {
            $event->getIO()->write('config/project.xml does not exist, creating template');
            try {
                $temps   = implode(',', self::$templates);
                $default = self::$templates[0];

                $template = $event->getIO()->ask(
                    "Which template would you like to use ({$temps}) [{$default}] ? ", $default);
            } catch (Exception $e) {
                $template = $default;
            }

            copy("vendor/3ev/phingy/scripts/templates/{$template}.xml", 'config/project.xml');
        } else {
            $event->getIO()->write('config/project.xml exists, template not needed');
        }
    }
}
