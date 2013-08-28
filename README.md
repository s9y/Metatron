# Metatron – CLI tool for S9y

A command line admin tool (written in PHP) for [Serendipity](http://s9y.org).

## Installation

There are two ways to install Metatron.

### Install the metatron.phar file

Download the phar file to your Serendipity root directory:

`wget https://raw.github.com/s9y/Metatron/master/metatron.phar`

### Install Metatron from source using Composer

```bash
# Clone the git repository
git clone https://github.com/s9y/Metatron

# Download Composer
curl -s https://getcomposer.org/installer | php

# Use Composer to install all dependencies
php ./composer.phar install
```

## Developers

If you want to contribute to Metatron, you're invited to fork the [Repository](https://github.com/s9y/Metatron) and open a [Pull Request](https://help.github.com/articles/using-pull-requests).

If you find a bug in Metatron, please [file an issue](https://github.com/s9y/Metatron/issues). Metatron is currently in an alpha state and should **not** be used on production servers! You have been warned ;)

If you have any further questions, feel free to visit the [Serendipity forum](http://board.s9y.org/).

## Requirements

Metatron has been tested on Linux and PHP 5.4.9, but should run in other environments and at least PHP 5.3.3.

## Usage

Right now, there are only a few commands. Change to your Serendipity root directory and make sure you have read permissions to `serendipity_config_local.inc.php`.

You get a list of all available commands by entering:

```bash
$ php metatron.phar list
```

If you need help running a command, type:

```bash
$ php metatron.phar help <command>
```

### Keeping Metatron up to date

As of version 0.1.1, Metatron is able to update itself to the latest version. Just run

```bash
$ php metatron.phar self-update
```

### Diagnostics

#### Serendipity version

Prints the current S9y version.

```bash
$ php metatron.phar diag:version
```

#### Information about the current installation

Prints basic information about the current S9y installation.

```bash
$ php metatron.phar diag:version
```

#### Retrieve config values

Prints the value of one or more config keys where the optional argument `name` is the name of the config key. Setting the `search` option list all config keys that contain `name`.

```bash
$ php metatron.phar diag:config [-s|--search] [name]
```

### User

#### List users

Lists all users in the system. You can enter an optional `username`.

```bash
$ php metatron.phar user:list [username]
```
#### Set new password for user

Set new password for user. Opens a dialog where you must enter the user's new password twice.

```bash
$ php metatron.phar user:password username
```

### Cache

#### Flush cache

Flushes cache directory. You can enter an optional cache directory (Default: templates_c).

```bash
$ php metatron.phar cache:flush [dir]
```

### Comments

#### List comments

Lists all comments, or the last X comments.

```bash
$ php metatron.phar comment:list [limit]
```

### Approve comments

List and approve comments, or approve a single comment by id. This will most certainly change in future versions.

```bash
$ php metatron.phar comment:approve [commentid]
```
