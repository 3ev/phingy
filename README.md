#Phingy

Phingy is a small collection of build scripts which you can mix together differently depending on your project. To use these scripts, you include the required ones in your build script starting with your own project.xml.

Using Phingy will mean that your project will be able to be built in its entirety with a simple

```sh
$ bin/phing build
```

call. If you need to do anything else, it's not doing its job!

##Installation & Dependencies

Phingy is installed via [Composer](http://getcomposer.org/). Add the following to your project's `composer.json` and run `composer install`:

```json
{
    "require": {
        "3ev/phingy": "~0.13"
    },
    "scripts": {
        "post-install-cmd": [
            "Tev\\Phingy\\ComposerScripts::postInstall"
        ]
    },
    "config": {
        "bin-dir": "bin"
    }
}
```

###Phing

Phing is installed as a dependency, with the binary by default being placed at
`vendor/bin/phing`. Adding the `bin-dir` config to your `composer.json` will
move it to `bin/phing` for convenience.

###Database tasks & S3

If you'd like to make use of the database tasks that push/pull database dumps
from Amazon S3, you will need to install the
[Pear Amazon S3 package](http://pear.php.net/package/Services_Amazon_S3/):

```sh
$ pear install Services_Amazon_S3
```

##Avaiable tasksets

Phingy ships with a set of base tasks and some platform specific tasks that you can use in your project. Use `$ bin/phing -l` to see what's available, but a brief overview is as follows:

###Core

- [build](https://github.com/3ev/phingy/blob/master/scripts/core/build.xml)
Core set of tasks and framework hooks.
- [db](https://github.com/3ev/phingy/blob/master/scripts/core/database.xml)
Database specific functionality. Each platform will include this as a dependency if it's needed - you will not have to do this yourself.

###Platform specific

- [typo3](https://github.com/3ev/phingy/blob/master/scripts/platform/typo3.xml)
Typo3 specific tasks (includes [db](https://github.com/3ev/phingy/blob/master/scripts/core/database.xml)). Will set up Typo3 on build, and provides some utility methods.
- [sphinx](https://github.com/3ev/phingy/blob/master/scripts/platform/sphinx.xml)
Sphinx specific tasks. Provides tasks to index Sphinx data for the project.
- [laravel](https://github.com/3ev/phingy/blob/master/scripts/platform/laravel.xml)
Laravel specific tasks. Provides task to work with the Laravel framework


##Setting up your project with Platforms

When you run `composer install`, you will be prompted to pick a template to use for you project. Currently, only 'default' and 'typo3' are available. Selecting a template will create a file called `project.xml` in your projects' `config/` directory.

The `project.xml` file includes a number of hooks you can add to for project-specific tasks. Currently, These are:

```
project:config                Configure extra properties to use in your build
project:build:before          Run some tasks before `build`
project:build:after           Run some tasks after `build`
project:build:housekeeping    Cleanup any uneeded files or data
project:typo3:cache:clear_all Do some extra cache clearing after TYPO3 caches are cleared
```

Each platform includes each of these hooks as well, of the form `[platform]:build:before` etc. If you'd like to make use of a platform, simply include it with:

```xml
<import file="${phingy.path}/scripts/platform/[platform].xml" />
```

and then call each of its hooks in your project-specific hooks:

```xml
<target name="project:build:before">
    <phingcall target="[platform]:build:before" />
</target>
```

**Note:** If you pick a non-default template (like 'typo3'), all of this will be handled for you.

##Adding your own project specific tasks and config

You can add any of your own tasks in `config/project.xml`. These should be namespaced with `project:`. You can either add standalone tasks, or call them in any of the available hooks.

###Overriding existing tasks

If you need to, you can override built in tasks by creating a new task with the same name in you `project.xml`. You shouldn't have to do this though, as the built in hooks provide enough flexibility for you to customise tasks.

###Adding extra config

You add should any project specific config to `config/project.properties` if possible. If you need to prompt the user for it during build, you can do so in the `project:config` hook.

##Deploying to production servers

When deploying to production, you should still be able to use `bin/phing build`. Tasks can use the `${build.environment}` variable to decide whether or not they should be run. Some built in taks that make use of this are:

```
typo3:build:after This will update the database, but only in development
```

Refer to these tasks to see how you can achieve this yourself.

##About ./build.xml

`build.xml` sits in the root of your project, and is symlinked from `vendor/`. You should add this file to your `.gitignore`.
