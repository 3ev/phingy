#Phingy

Phingy is a small collection of build scripts which you can mix together differently
depending on your project. To use these scripts, you include the required ones in
your build script starting with your own project.xml.

###Installation & dependencies

Phingy is installed via [Composer](http://getcomposer.org/). Add the following to
your project's `composer.json` and run `composer install`:

```json
{
    "require": {
        "3ev/phingy": "~4.0"
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

You'll then be able to run Phing using

```
$ bin/phing
```

###'Skel' files

Phingy will automatically generate files from `.skel` files anywhere in your project.
These files can include Phing properties which will be replaced with their actual
values at compile time.

You may for example want to create an Apache vhost config file for your project,
which you'll be able to commit to source control without having to including any
environment specific config. Like so:

```
# In config/httpd.conf.skel

<VirtualHost *:80>
    ServerName ${build.url}
    DocumentRoot ${build.public_dir}
</VirtualHost>
```

###Database tasks & S3

If you'd like to make use of the database tasks that push/pull database dumps
from Amazon S3, you will need to install the
[Pear Amazon S3 package](http://pear.php.net/package/Services_Amazon_S3/):

```sh
$ pear install Services_Amazon_S3
```

This will allow you to backup and download your full database to/from S3 using:

```
$ bin/phing db:commit
$ bin/phing db:update
```

There are numerous other database tasks that make it easy to configure a MySQL
database for your project.

###Setting up a webserver

Phingy includes a `build:server` task which will by default configure and install
Apache configuration for your project.

Config will be symlinked from `config/httpd.conf` to `/etc/apache2/sites-enabled`,
so you can commit a file to version control (or use a `.skel` extension) and simply
run

```
$ sudo bin/phing build:server
```

to start your project running on Apache.

###Platform specific tasks

Phingy includes a few 'platform' specifc task sets, as followss:

- [typo3](https://github.com/3ev/phingy/blob/master/scripts/platform/typo3.xml)
Typo3 specific tasks (includes [db](https://github.com/3ev/phingy/blob/master/scripts/core/database.xml)). Will set up TYPO3 on build, and provides some utility methods.
- [sphinx](https://github.com/3ev/phingy/blob/master/scripts/platform/sphinx.xml)
Sphinx specific tasks. Provides tasks to index Sphinx data for the project.
- [laravel](https://github.com/3ev/phingy/blob/master/scripts/platform/laravel.xml)
Laravel specific tasks. Provides tasks to work with the Laravel framework

###Setting up your project with Platforms

When you run `composer install`, you will be prompted to pick a template to use
for your project. Selecting a template will create a file called `project.xml` in
your project's `config/` directory.

The `project.xml` file includes a number of hooks you can add to for project-specific
tasks. Currently, These are:

```
project:config                Configure extra properties to use in your build
project:build:before          Run some tasks before `build`
project:build:after           Run some tasks after `build`
project:build:housekeeping    Cleanup any uneeded files or data
```

Each platform includes each of these hooks as well, of the form `[platform]:build:before` etc.
If you'd like to make use of a platform, simply include it with:

```xml
<import file="${phingy.path}/scripts/platform/[platform].xml" />
```

and then call each of its hooks in your project-specific hooks:

```xml
<target name="project:build:before">
    <phingcall target="[platform]:build:before" />
</target>
```

**Note:** If you pick a non-default template (like 'typo3'), all of this will be
handled for you.

###Adding your own project specific tasks and config

You can add any of your own tasks in `config/project.xml`. These should be namespaced
with `project:` for convention. You can either add standalone tasks, or call them
in any of the available hooks.

###Overriding existing tasks

If you need to, you can override built in tasks by creating a new task with the
same name in your `project.xml`. You shouldn't have to do this though, as the
built in hooks provide enough flexibility for you to customise tasks.

###Adding extra config

You add should any project specific config to `config/project.properties` if possible.
If you need to prompt the user for it during build, you can do so in the
`project:config` hook.

###About ./build.xml

`build.xml` sits in the root of your project, and is symlinked from `vendor/`.
You should add this file to your `.gitignore`.
