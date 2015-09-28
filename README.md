# checkmycomposer-commands

This package provides 2 commands to check your Composer dependencies and synchronize your project with http://checkmycomposer.com.

## Getting Started

Follow this link to get started: http://checkmycomposer.com/getting-started

## Commands

### Check directly your dependencies in your console

To check your dependencies, type the following command in the directory where your composer.json file is stored.

```
composer check
```

A table will be displayed with all dependencies you are using with several information as in the following image.

![composer_check_img](http://checkmycomposer.com/bundles/app/img/help/composer_check.png)

_Note:_ You can change the alias of the command ("check") in the "scripts" node of your composer.json file (see section above).

### Synchronize your projects with CheckMyComposer

The main interest of CheckMyComposer is to follow your all projects very easily.
To complete this, you need to type the following command in the same directory as your composer.json file.
Don't forget to store your synchronization token (see section Implementation of token in Getting Started part).

```
composer synchro
```

A message will inform you that the synchronization has been succesfully done.

![composer_synchro_img](http://checkmycomposer.com/bundles/app/img/help/composer_synchro.png)

_Note:_ As the checking command, you can change the alias of the command ("synchro") in the "scripts" node of your composer.json file.

**Note:** We suggest you to synchronize your projects in production environment (this has more interest to follow real versions used by your projects in production).
As indicated in Getting Started section, we recommend to store your token in a file (only present in your production server) in this case.
