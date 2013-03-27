<?php

namespace Ev\Phingy;

use Composer\Script\Event;

/**
 * Installation scripts.
 */
class ComposerScripts
{
    /**
     * Create template build files if they don't exist.
     * 
     * @param Composer\Script\Event $event
     */
    public static function postPackageInstall(Event $event)
    {
        // Create core build file if it doesn't exist
        if (!file_exists('build.xml')) {
            $event->getIO()->write('build.xml does not exist, creating template');

            copy('vendor/3ev/phingy/scripts/templates/build.xml', 'build.xml');
        } else {
            $event->getIO()->write('build.xml exists, template not needed');
        }

        // Create project build file if it doesn't exist
        if (!file_exists('config/project.xml')) {
            $event->getIO()->write('config/project.xml does not exist, creating template');

            copy('vendor/3ev/phingy/scripts/templates/project.xml', 'config/project.xml');
        } else {
            $event->getIO()->write('config/project.xml exists, template not needed');
        }
    }
}