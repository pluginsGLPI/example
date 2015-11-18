# Example Plugin for GLPI

## Introduction

This plugin aims to introduce how to interact with glpi HOOKS.  
Most of implemented features are for example and do nothing.  
 
## Why and when to make a plugin? It is necessary to make a plugin when:

* We want to manage inventory items or more additional fields on existing objects.
* We want to change some behavior standards GLPI (block changes to certain fields, search for data in an external source, etc.)

## What features are provided by a plugin?

* [Plugins features](https://github.com/pluginsGLPI/example/wiki/Features)
* Version Management for plugins

## How to develop a plugin for glpi?

## How to make a plugin available?

Create a repository on the provider of your choice (github.com, gitlab.org, git.framasoft.org, etc).  
Once it is created, you will have every opportunity to manage its evolution as you want:

* git or svn project-specific (plugin)
* Wiki
* Published Release
* Adding people in the project team

## How to see his plugin published in the catalog?

* Add to the root of your git master the plugin XML file that describes it (see http://plugins.glpi-project.org/#/submit)
* Add logo/screenshosts in png on your git master and target it in your xml.
* Submit the url of raw xml of your plugin in the catalogue http://plugins.glpi-project.org/#/submit
* The manager team will recieve a mail and after a check of your plugin and xml, it will be available in lists.
