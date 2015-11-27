# IndexOf

_IndexOf is small php tool that display a good looking ajaxed directory listing for your remotely stored files_

## Install

    git clone https://github.com/janssens/IndexOf.git .iof

if you want to use another directory than ``.iof``, set the newname in _inc.php_.

### Apache

if you dont have .htaccess yet:

    mv .iof/tmp.htaccess .htaccess

*else* edit it in order to keep only one directive _DirectoryIndex_ like so

    DirectoryIndex /.iof/index.php index.html index.php

### NGiNX

    location / {
        index /.iof/index.php index.html index.php;
    }

## Required
	
* php

## Use

* history.js (https://github.com/browserstate/history.js/)
* bootstrap
* lightbox

## Initialize

## Demo

[http://tools.plopcom.fr](http://tools.plopcom.fr)

## License

 licensed GPL 2.

***

Copyright (c) 2014 Gaëtan Janssens
