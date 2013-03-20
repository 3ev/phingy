
** Add notes on the standard build steps.

** Add notes on the namespaces and what they mean.

** Add notes on the deployment-critical build tasks.


TODO:

- include sane production defaults.
- include the ability to use environment variables. E.g. in production. Perhaps a convention for database server domain, etc.
- v1/docs.xml (split?)

# Phingy

Phingy is a small collection of build scripts which you can mix together differently depending on your project. To use these scripts, you include the required ones in your build script starting with your own project.xml. A typical order of inclusion is:

    config/build/project.xml.   Your project tasks go here along with required hooks into the build process.
    platform/typo3.xml.         Tasks to get TYPO3 configured go here. These are generically called "platform" tasks. 
                                The configuration task goes here. See below for discussion.
    database.xml.               Tasks relating to getting the database loaded go here. 
    build.xml.                  The ov

The order matters. The more specific scripts _must_ come first so they can override the more general. Your build.xml is likely to look like this:
    
    <?xml version="1.0" encoding="UTF-8"?>
    <project name="build" basedir="." default="readme">
        <!-- where are the phingy sources? (WITHOUT trailing slash) -->
        <property name="phingy.source" value="/var/www/vhosts/phingy" />
        <!-- what version of phingy? -->
        <property name="phingy.version" value="1" />
        <property name="phingy.path" value="${phingy.source}/v${phingy.version}/" />
        <echo msg="LOADING PHINGY FROM ${phingy.path}" level="error" />
        
        <import file="config/build/project.xml" />
        <import file="${phingy.path}build.xml" />
        <import file="${phingy.path}platform/typo3.xml" />
        <import file="${phingy.path}database.xml" />
        <!-- Go away! Don't add stuff here! -->
    </project>

The first section is just configuring which version of phingy to include, and the section section is loading it.


# The configuration task (needConfiguration and build:config)

The configuration task is the step which asks you for all the required info to run the project. This differs mostly with the platform, so you'll find the task in platform/[something].xml. 

For example, the TYPO3 configuration task asks for the TYPO3 version.

If your project needs extra configuration or options, do one of two things:

1. Just use good defaults and don't ask the user (the builder). For example, if you have a shop to link to, put the production URL in there along with "shop.${your build URL}" so it's _convention_ rather than _configuration_.

Or

2. Override the "build:config" target in your project.xml. 

Option 1 is _strongly_ preferred. Option 2 is quick and dirty, but will make your build scripts harder to maintain in future.

As a examples of option 1, suppose you have a shared library and you want to assume that it will be in the shared location. Do this:

    <property name="library_location" value="${share.path}/mylibrary/" />
    <!-- and use it -->
    <target name="exampletask">
        <echo msg="This gives us: ${library_location}" />
    </target>

Or if you have a second domain (e.g. a shop), do this:

    <property name="shop_url" value="shop.${build.url}" />
    <!-- And use it -->
    <target name="exampletask">
        <echo msg="This gives us: ${shop_url}" />
    </target>


# Targets

If you _have_ to override a core target (e.g. "readme" or "build:build") then you create a target of that name in project.xml. The project.xml file overrides all the others because it is included first in your projects build.xml file.

There are a few standard hooks, which are actually just targets in your project.xml file that are called at specific points in the build process.
project.build:config
project.build:before
Your projects MUST support these targets otherwise the build will call the target, find it doesn't exist and throw an error.

These targets are usually not going to be run directly, so you can hide them from the list of tasks by setting the ' hidden="true"' attribute.

# Versions of phingy

Phingy is installed on the server as a shared library in:

    /share/phingy/v1/

This MUST be the case for all production code. 

Your project's build script will have the lines:

    <!-- where are the phingy sources? (WITHOUT trailing slash) -->
    <property name="phingy.source" value="/var/www/vhosts/phingy" />
    <!-- what version of phingy? -->
    <property name="phingy.version" value="1" />

Where you can specify a different location and different version. 

The phingy sources (i.e. the git project) contain all versions, so you can easily check it out and hack around with the different versions. If you have improvements or fixes, just submit them as pull requests as usual.

To work with your own copy of phingy:

    cd /home/me/
    git clone …. phingy

In your build.xml:

    <property name="phingy.source" value="/home/me/phingy" />

