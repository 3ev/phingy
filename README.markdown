# Phingy

Current version: `0.0.4`

Phingy is a small collection of build scripts which you can mix together differently depending on your project. To use these scripts, you include the required ones in your build script starting with your own project.xml. 

Using Phingy will mean that your project will be able to be built in its entirety with a simple

```
$ phing build
```

call. If you need to do anything else, it's not doing its job!

## Installation

Phingy is installed via [Composer](http://getcomposer.org/). Add the following to your project's `composer.json` and run `composer install`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/3ev/phingy"
        }
    ],
    "require": {
        "3ev/phingy": "dev-master"
    },
    "scripts": {
        "post-package-install": [
            "Ev\\Phingy\\ComposerScripts::postPackageInstall"
        ]
    }
}
```

Specify a particular version with:

```json
{
    "require": {
        "3ev/phingy": "0.0.1"
    }
}
```

or to use the latest unstable build:

```json
{
    "require": {
        "3ev/phingy": "dev-master"
    }
}
```

As Phingy is a private Github repo, you will be prompted for your username and password the first time you install it on a project.

## Avaiable tasksets

Phingy ships with a set of base tasks and some platform specific tasks that you can use in your project. Use `$ phing -l` to see what's available, but a brief overview is as follows:

### Core

- [build](https://github.com/3ev/phingy/blob/master/scripts/core/build.xml)
Core set of tasks and framework hooks.
- [db](https://github.com/3ev/phingy/blob/master/scripts/core/database.xml)
Database specific functionality. Each platform will include this as a dependency if it's needed - you will not have to do this yourself.

### Platform specific

- [typo3](https://github.com/3ev/phingy/blob/master/scripts/platform/typo3.xml)
Typo3 specific tasks (includes [db](https://github.com/3ev/phingy/blob/master/scripts/core/database.xml)). Will set up Typo3 on build, and provides some utility methods.
- [sphinx](https://github.com/3ev/phingy/blob/master/scripts/platform/sphinx.xml)
Sphinx specific tasks. Provides tasks to index Sphinx data for the project.
- [wordpress](https://github.com/3ev/phingy/blob/master/scripts/platform/wordress.xml)
Not yet implemented.

## Setting up your project with Platforms

When you run `composer install`, you will be prompted to pick a template to use for you project. Currently, only 'default' and 'typo3' are available. Selecting a template will create a file called `project.xml` in your projects' `config/` directory.

The `project.xml` file includes 3 hooks you can add to for project-specific tasks. These are:

```
project:build:before       Run some tasks before `build`
project:build:after        Run some tasks after `build`
project:build:housekeeping Cleanup any uneeded files or data
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

## Adding your own project specific tasks and config

You can add any of your own tasks in `config/project.xml`. These should be namespaced with `project:`. You can either add standalone tasks, or call them in any of the available hooks.

### Overriding existing tasks

If you need to, you can override built in tasks by creating a new task with the same name in you `project.xml`. You shouldn't have to do this though, as the built in hooks provide enough flexibility for you to customise tasks.

### Adding extra config

You add should any project specific config to `config/project.properties` if possible. If you need to prompt the user for it during build, you can create your own `project:need_configuration` task and depend on it.

## Deploying to production servers

When deploying to production, you should still be able to use `phing build`. Tasks can use the `${build.environment}` variable to decide whether or not they should be run. Some built in taks that make use of this are:

```
build:server      The server will only be symlinked and restarted in development
sphinx:symlink    Sphinx will only be symlinked in development
typo3:build:after This will update the database, but only in development
```

Refer to these tasks to see how you can achieve this yourself.

## About ./build.xml

`build.xml` sits in the root of your project, and is symlinked from `vendor/`. You should add this file to your `.gitignore`.
