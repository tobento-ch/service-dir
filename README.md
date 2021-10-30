# Dir Service

The Dir Service provides a way for managing directories in an application.

## Table of Contents

- [Getting started](#getting-started)
	- [Requirements](#requirements)
	- [Highlights](#highlights)
	- [Simple Example](#simple-example)
- [Documentation](#documentation)
    - [Create Dirs](#create-dirs)
    - [Add Directories](#add-directories)
    - [Filter Directories](#filter-directories)
    - [Sort Directories](#sort-directories)
    - [Iterating Directories](#iterating-directories)
    - [Get Directories](#get-methods)
    - [Directory](#directory)
- [Credits](#credits)
___

# Getting started

Add the latest version of the dir service running this command.

```
composer require tobento/service-dir
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design

## Simple Example

Here is a simple example of how to use the dir service:

```php
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\Dir;

$dirs = new Dirs(
    new Dir(dir: 'home/private/views', name: 'views'),
    new Dir('home/private/config', 'config'),
);

$dirs->dir('home/private/cache', 'cache');

var_dump($dirs->get('views'));
// string(19) "home/private/views/"
```

# Documentation

## Create Dirs

```php
use Tobento\Service\Dir\DirsInterface;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\Dir;

$dirs = new Dirs(
    new Dir(dir: 'home/private/views', name: 'views'),
    new Dir('home/private/config', 'config'),
);

var_dump($dirs instanceof DirsInterface);
// bool(true)
```

## Adding Directories

**add**

Adding directories by the **add** method which takes an object implementing the DirInterface.

```php
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\Dir;
use Tobento\Service\Dir\DirInterface;

$dirs = new Dirs();

// dir: DirInterface
$dirs->add(dir: new Dir('home/private/config', 'config'));

$dirs->add(new Dir('home/private/config', 'config'))
     ->add(new Dir('home/private/views', 'views'));
```

**dir**

Adding directories by the **dir** method.

```php
use Tobento\Service\Dir\Dirs;

$dirs = new Dirs();

$dirs->dir(
    dir: 'home/private/config', // string
    name: 'config', // null|string
    group: 'front', // string
    priority: 10, // int
);

$dirs->dir('home/private/views', 'views')
     ->dir('home/private/cache', 'cache');
```

## Filter Directories

You may use the filter methods returning a new instance.

**filter**

```php
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\DirInterface;

$dirs = new Dirs();

$dirs->dir(
    dir: 'home/private/views/front',
    group: 'frontend',
    priority: 10,
);

$dirs->dir(
    dir: 'home/private/views/backend',
    group: 'backend',
);

// filter by group:
$dirs = $dirs->filter(fn(DirInterface $dir): bool => $dir->group() === 'frontend');
```

**group**

```php
use Tobento\Service\Dir\Dirs;

$dirs = new Dirs();

$dirs->dir(
    dir: 'home/private/views/front',
    group: 'frontend',
    priority: 10,
);

$dirs->dir(
    dir: 'home/private/views/backend',
    group: 'backend',
);

$dirs = $dirs->group('frontend');
```

**groups**

```php
use Tobento\Service\Dir\Dirs;

$dirs = new Dirs();

$dirs->dir(
    dir: 'home/private/views/front',
    group: 'frontend',
    priority: 10,
);

$dirs->dir(
    dir: 'home/private/views/backend',
    group: 'backend',
);

$dirs = $dirs->groups(['frontend', 'backend']);
```

**only**

```php
use Tobento\Service\Dir\Dirs;

$dirs = new Dirs();

$dirs->dir('home/private/views', 'views');
$dirs->dir('home/private/config', 'config');
$dirs->dir('home/private/cache', 'cache');

$dirs = $dirs->only(['views', 'cache']);
```

**except**

```php
use Tobento\Service\Dir\Dirs;

$dirs = new Dirs();

$dirs->dir('home/private/views', 'views');
$dirs->dir('home/private/config', 'config');
$dirs->dir('home/private/cache', 'cache');

$dirs = $dirs->except(['views', 'cache']);
```

## Sort Directories

**Sort by priority**

```php
use Tobento\Service\Dir\Dirs;

$dirs = new Dirs();

$dirs->dir(
    dir: 'home/private/views/front',
    priority: 10,
);

$dirs->dir(
    dir: 'home/private/views/theme/front',
    priority: 15,
);

// sort by priority:
$dirs = $dirs->sort();
```

**Sort by callback**

```php
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\DirInterface;

$dirs = new Dirs();

$dirs->dir(
    dir: 'home/private/views/front',
    priority: 10,
);

$dirs->dir(
    dir: 'home/private/views/theme/front',
    priority: 15,
);

// sort by name:
$dirs = $dirs->sort(
    fn(DirInterface $a, DirInterface $b): int => $a->name() <=> $b->name()
);
```

## Iterating Directories

```php
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\DirInterface;

$dirs = new Dirs();

$dirs->dir('home/private/views', 'views');
$dirs->dir('home/private/config', 'config');

foreach($dirs->all() as $dir)
{
    var_dump($dir instanceof DirInterface);
    // bool(true)
}

// or just:
foreach($dirs as $dir)
{
    var_dump($dir instanceof DirInterface);
    // bool(true)
}
```

## Get Directories

**get**

Get a single directory.

```php
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\DirNotFoundException;

$dirs = new Dirs();

$dirs->dir('home/private/views', 'views');
$dirs->dir('home/private/config', 'config');

var_dump($dirs->get('config'));
// string(20) "home/private/config/"

// throws DirNotFoundException if dir is not found
$dirs->get('cache');
```

**getDir**

Get a single directory object.

```php
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\DirInterface;
use Tobento\Service\Dir\DirNotFoundException;

$dirs = new Dirs();

$dirs->dir('home/private/views', 'views');
$dirs->dir('home/private/config', 'config');

var_dump($dirs->getDir('config') instanceof DirInterface);
// bool(true)

// throws DirNotFoundException if dir is not found
$dirs->getDir('cache');
```

**all**

Get all directories.

```php
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\DirInterface;

$dirs = new Dirs();

$dirs->dir('home/private/views', 'views');
$dirs->dir('home/private/config', 'config');

$dirs = $dirs->all('config');
// array<string, DirInterface>
```

**has**

```php
use Tobento\Service\Dir\Dirs;

$dirs = new Dirs();

$dirs->dir('home/private/config', 'config');

var_dump($dirs->has('config'));
// bool(true)

var_dump($dirs->has('view'));
// bool(false)
```

## Directory

```php
use Tobento\Service\Dir\Dir;
use Tobento\Service\Dir\DirInterface;

$dir = new Dir(
    dir: 'home/private/config', // string
    name: 'config', // null|string
    group: 'front', // string
    priority: 10, // int
);

var_dump($dir instanceof DirInterface);
// bool(true)

var_dump($dir->dir());
// string(20) "home/private/config/"

var_dump($dir->name());
// string(6) "config"

var_dump($dir->group());
// string(5) "front"

var_dump($dir->priority());
// int(10)
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)