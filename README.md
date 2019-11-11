# PHP Server

[![Latest release](https://img.shields.io/github/release/natanfelles/php-server.svg?colorB=green&label=Latest%20release&style=popout)](https://github.com/natanfelles/php-server/releases)
[![PHP version](https://img.shields.io/packagist/php-v/natanfelles/php-server.svg?colorB=blueviolet&label=PHP%20version&style=flat)](https://php.net/downloads.php)
[![Packagist downloads](https://img.shields.io/packagist/dt/natanfelles/php-server.svg?colorB=%23f28d1a&label=Packagist%20downloads&style=flat)](https://packagist.org/packages/natanfelles/php-server)

Fine tuning on the PHP Built-in web server

![PHP Built-in web server autoindex](https://i.imgur.com/dE7B2LJ.png)

> See release notes at: https://github.com/natanfelles/php-server/releases

## Installation

### Composer

Open your terminal and run:

```sh
composer global require natanfelles/php-server:dev-master
```

Add the composer bin path to your *.bashrc*:

```sh
echo 'export PATH="$PATH:$HOME/.config/composer/vendor/bin"' >> ~/.bashrc
```

### Manual

Download and extract the *php-server* project folder.

Add the php-server alias to your *.bashrc*:

```sh
echo 'alias php-server="~/php-server/bin/php-server"' >> ~/.bashrc
```

## Config

You can run the php-server in any folder of your operating system.

Each time you run the *php-server* command, it will look up if there is a file named *php-server.ini* in the current directory. If found, your settings will override the default settings.

A quick example file content is:

```ini
php = PHP_BINARY
host = localhost
port = 8080
root = ./public
autoindex = true
index = index.php
error_reporting = E_ALL

[ini]
display_errors = 1
display_startup_errors = 1
max_execution_time = 360
post_max_size = 200M
upload_max_filesize = 200M
; Array of runtime extensions:
extension[] = mysqli
extension[] = zip

[server]
ENVIRONMENT = development
```

> You can use the command `php-server new` to create a new configuration file in the current directory.

### Explanation

#### General Vars

| Key | Default Value| Description |
| --------------- | --- | --- |
| php | [PHP_BINARY](http://php.net/manual/en/reserved.constants.php#constant.php-binary) | PHP binary path or command |
| host | localhost | Server host |
| port | 8080 | Server host port |
| root | [getcwd()](http://php.net/manual/en/function.getcwd.php) | Document root. The location that will be the public root of your website. |
| autoindex | true | Determines if the server will list directory contents if it does not find an index file. |
| index | index.html index.php | The names of the index files separated by spaces. |
| error_reporting | [E_ALL](http://php.net/manual/en/errorfunc.constants.php#errorfunc.constants.errorlevels.e-all) | Sets the [level of errors](http://php.net/manual/en/function.error-reporting.php) that will be reported. |

#### Sections

| Section | Description |
| --- | --- |
| ini |  Used to set custom [php.ini directives](http://php.net/manual/en/ini.list.php). |
| server | Used to set custom [Server and execution environment information](http://php.net/manual/en/reserved.variables.server.php). |

Knowing this, just create (if necessary) a php-server.ini file, run the server and you're done.

## Run

As you can see in the [config](#config). You can create a php-server.ini file to leave the settings of each project already pre-established.

But, you can also simply run `php-server` and the server will already be available at [http://localhost:8080](http://localhost:8080).

The php-server command can receive some parameters and they are:

| Parameter | Description |
| --- | --- |
| --php | PHP binary path or command  |
| --host | Server host |
| --port | Server host port |
| --root | Document root |

For example, to run the server on a different port:

```sh
php-server --port 8081
```

Or, also with a different version of PHP than the default:

```sh
php-server --php php7.4 --port 8081
```

Or, custom domain on port 80:

```sh
sudo php-server --host domain.tld --port 80
```

Right. You get the idea. If you want to run on a different host you can add the host to the [hosts file](https://en.wikipedia.org/wiki/Hosts_(file)) of your operating system.

## Contribute

Hello, how nice that you are reading this.

If you have any idea to improve this project or something is not working as it should, do not hesitate to open an [issue](https://github.com/natanfelles/php-server/issues) and if you have solved the problem feel free to open a [Pull Request](https://github.com/natanfelles/php-server/pulls).
