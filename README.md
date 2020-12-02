[![Build Status](https://travis-ci.org/HamHamFonFon/Astrobin-API-PHP.svg?branch=master)](https://travis-ci.org/HamHamFonFon/Astrobin-API-PHP) [![codecov.io](https://codecov.io/gh/HamHamFonFon/Astrobin-API-PHP/branch/master/graphs/badge.svg?branch=master)](https://codecov.io/gh/HamHamFonFon/Astrobin-API-PHP/branch/master/graphs/badge.svg?branch=master)
# WebServices for Astrobin's API REST

## Table of contents

 * [Requirements](#requirements)
 * [Introduction](#introduction)
 * [Installing](#installing)
   * [Usage](#usage)
 * [WebServices](#webservices)
   * [GetImage](#getimage)
   * [GetTodayImage](#gettodayimage)
   * [GetCollection](#getcollection)
   * [GetLocation](#getlocation)
 * [Responses](#responses)
   * [Image](#image)
   * [ListImage](#listimage)
   * [Collection](#collection)
   * [ListCollection](#listcollection)
   * [Today](#today)
   * [Location](#location)
 * [Running the tests](#running-the-tests)
 * [Authors](#authors)
 * [Licence](#licence)

Version 1.1.0

## Requirements
* PHP 7.2 min or superior
* API Key and API Secret from [Astrobin](https://www.astrobin.com/api/request-key/)

## Introduction

Astrobin's WebServices is a PHP library for request Astrobin's API Rest and get amazing astrophotographies hosted on from [Astrobin](http://www.astrobin.com).
Please read API section in ["Terms of service"](https://welcome.astrobin.com/terms-of-service)

## Installing

You can install this package in 2 different ways.

* Basic installation; just install package from composer :

> `composer require hamhamfonfon/astrobin-ws`

For update :
> `composer update hamhamfonfon/astrobin-ws`
>
* If you just want to make some issues, make some simple tests etc, juste clone the repository

> `git clone git@github.com:HamHamFonFon/Astrobin-API-PHP.git`


* [OLD] If you want to add to your own composer.json project :

```json
    [...]
    "require" : {
        [...]
        "hamhamfonfon/astrobin-ws" : "dev-master"
    },
    "repositories" : [{
        "type" : "vcs",
        "url" : "https://github.com/HamHamFonFon/Astrobin-API-PHP.git"
    }],
    [...]
```

### Usage


With Symfony, you can set WebService class as services :

First, set your keys in .env file :
```yml
ASTROBIN_API_KEY=PutHereYourOwnApiKey
ASTROBIN_API_SECRET=PutHereYourOwnApiSecret
```

Exemple with Symfony 4:
```
use Astrobin\Exceptions\WsException;
use Astrobin\Exceptions\WsResponseException;
use Astrobin\Response\Image as AstrobinImage;
use Astrobin\Services\GetImage;

class MyService
{
    /** @var GetImage **/ 
    private $astrobinImage;

    public function __construct()
    {
        $this->astrobinImage = new GetImage();
    }

    public function getOrionNebula():? AstrobinImage
    {
        $orion = $this->astrobinImage->getImageById('m42');
      
        return $orion;
    }
}
```

## WebServices

The library expose 4 WebServices, each with these methods below.

### GetImage :

| Function name | Parameter| Response |
| ------------- | ------------------------------ |----------------------------- |
| `getImageById()`| `$id` | `Image` |
| `getImagesBySubject()`| `$subjectId`  `$limit`| `ListImage`,`Image`|
| `getImagesByDescription()`| `$description`  `$limit`| `ListImage`,`Image`|
| `getImagesByUser()`| `$userName`  `$limit`| `ListImage`,`Image` |
| `getImagesByRangeDate()`| `$dateFromStr` (ex: 2018-04-01), `$dateToStr` (2018-04-31 or null) | `ListImage`,`Image` |

### GetTodayImage :

| Function name | Parameter| Response |
| ------------- | ------------------------------ |----------------------------- |
| `getDayImage()`| `$offset` , limit = 1| `Today` |
| `getTodayDayImage()`|| `Today` |

### GetCollection :

| Function name | Parameter| Response |
| ------------- | ------------------------------ |----------------------------- |
| `getCollectionById()`| `$id`| `Collection` |
| `getCollectionByUser()`|`$user`,`$limit`| `ListCollection` |

### GetLocation :
*In progress...*

| Function name | Parameter| Response |
| ------------- | ------------------------------ |----------------------------- |
| `getLocationById()`| `$id`| `Location` |


## Responses

### Image
| Parameter| Description |
| ------------- | ------------------------------ |
| `title`| Title of image|
| `subjects`| Keywords|
| `description`| Description|
| `url_gallery`| URL of image for gallery|
| `url_thumb`| URL of image , thumb size|
| `url_regular`| URL of image|
| `user`| Username|
| `url_histogram` | URL to histogram |
| `url_skyplot` | URL to skyplot |

![](https://image.noelshack.com/fichiers/2018/17/5/1524854105-image.png)

### ListImage
| Parameter| Description |
| ------------- | ------------------------------ |
| `listImages`      | List of images       |

![](https://image.noelshack.com/fichiers/2018/18/1/1525117490-list-images.png)

### Collection
| Parameter| Description |
| ------------- | ------------------------------ |
| `id`| Identifier|
| `name`| Name of collection|
| `description`| Description|
| `user` User name|
| `date_created`| Date of creation|
| `date_updated`| Date of modification|
| `images`| Path of WS Image|
| `listImages`| Path of WS Image|

![](https://image.noelshack.com/fichiers/2018/18/2/1525187691-collection.png)

### ListCollection
| Parameter| Description |
| ------------- | ------------------------------ |
| `listCollection`| List of collection with list of images|

![](https://image.noelshack.com/fichiers/2018/18/2/1525189056-listcollection.png)

### Today
| Parameter| Description |
| ------------- | ------------------------------ |
| `date`| Date of image       |
| `resource_uri`| URI of image|
| `listImages`| List of images|

![](https://image.noelshack.com/fichiers/2018/18/1/1525117371-today.png)

### Location
*In progress*

## Running the tests

```
php ./vendor/bin/phpcs -p -n --standard=PSR2 src
```

Due to problems dependencies between PHP 7.0, PhpUnit 6 and doctrine/instantiator explained (here)[https://github.com/sebastianbergmann/phpunit/issues/2823], if you want run PHP Unit test, run `composer install` with PHP 7.0

## Authors
Stéphane Méaudre  - <balistik.fonfon@gmail.com>

## Licence

This project is licensed under the MIT License - see the LICENSE.md file for details
