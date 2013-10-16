# Classroom Observation Protocol for Undergraduate STEM (COPUS)
Built for the Carl Wieman Science Education Initiative. This app is meant to facilitate the CWSEI in lecture analysis by providing a dynamic interface in which to do so.

See more on COPUS: http://www.cwsei.ubc.ca/resources/COPUS.htm

A logistics management system for shipping agencies

* [Installation](#installation)
* [Style Guide](#style-guide)
    * [PHP](#style-guide-php)
    * [Javascript](#style-guide-javascript)
    * [HTML](#style-guide-html)
* [Architecture](#architecture)
    * [Back-end](#architecture-backend)
    * [Front-end](#architecture-frontend)
    
##<a name="installation">Installation</a>

1. Checkout this repository to /path/to/copus/
2. Checkout Yii 1.1.14 to /path/to/yii-1.1.14.f0fee9/
3. Create a database using /path/to/copus/protected/migrations/initial.sql
4. > cd /path/to/copus/
5. > cp index.php.tmpl index.php
5a. If in development mode, add the following lines before "require_once($yii);" to display errors:
```defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);```
6. > cp protected/config/main.php.tmpl protected/config/main.php
6a. Replace <DB_NAME>, <DB_USERNAME>, <DB_PASSWORD>
7. Run the app (e.g. visit http://localhost/copus in your browser)

##<a name="style-guide">Style Guide</a>
###<a name="style-guide-php">PHP</a>
Use [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)

###<a name="style-guide-javascript">Javascript</a>
Use [Felix's style guide](http://nodeguide.com/style.html) except:

1. The 80 character limit is a *soft rule*.
2. Prefix private methods with an underscore.

###<a name="style-guide-html">HTML/CSS</a>
Use [Google's HTML and CSS style guide](http://google-styleguide.googlecode.com/svn/trunk/htmlcssguide.xml)

###<a name="style-guide-comments">Comments</a>
1. Function documentation should follow [Javadoc](http://www.oracle.com/technetwork/java/javase/documentation/index-137868.html)
2. Try to limit inline comments to one line. Should a section of code require additional documentation, split it into a function and use Javadoc appropriately).

##<a name="architecture">Architecture</a>
###<a name="architecture-backend">Back-end</a>
The back-end uses the following technologies:

PHP 5.2.17
MySQL 14.14
Yii Framework 1.1.14

###<a name="architecture-frontend">Front-end</a>
The front-end of COPUS uses static HTML, CSS and Javascript (non-framework). In addition, the following technologies are utilised:

Bootstrap 2.3.2
jQuery 1.8.3

The front-end is built for Google Chrome but works for other browsers.
