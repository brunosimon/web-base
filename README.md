Installation
-----

Open terminal and go to _/builder_ folder. Install all node dependencies:

`npm install`

Then go to _/site_ folder and install php dependencies: 

`composer install`
 

You'll also need [_imagemagick_](http://imagemagick.org/) and [_GraphicsMagick_](http://www.graphicsmagick.org/).


Features
-----

This bootstrap supports simple website with static content, as multilingual websites with dynamic content.
Everything is made for just doing HTML, CSS and Javascript. No PHP.

### Subdomaisn

You'll need 4 subdomains:

* dev: your website
* manage: the CMS
* services: to get dynamic content from the CMS
* uploads: to get pictures from the CMS

### Config

You have a _site/config.php_ where you have several parameters:

### Static content

All static content are in _site/dev/json_ folder. 
If you have a multilingual website, json files will be in a folder named as the language.
You'll need multiple json files:

* 404.json
* global.json: for all global content such as analytics ID, facebook page link...
* old.json: for the page for old browsers
* routes.json: for routing

Then all pages will be linked thanks to the URL. For example, if the user go to _/en/works_, the bootstrap will look for two files:

* json/en/works/content.json
* json/en/works.json

All content json files are optionnal.

### Dynamic content

Bootstrap will you [Cockpit CMS](http://getcockpit.com/). For now, just collections are used. 

Data can be retrieved thanks to _services_ subodmain. If you have a collection named _blog_ and a multilingual article with title _article in english_, you'll have to call:

 `services.domain/en/blog/article-in-english`

Pictures are uploaded in _manage/uploads_ by default. But in your website, you'll have to get it thanks to _uploads_ subdomain, followed by the name or path of the picture.

Gulp Build
---

We use _gulp_ for building everything.
Just run `gulp`in _builder_ folder to run gulp. 
You can configure everything via the _builder/config.json_ files. You can list sources et destination files for each tasks.

We use coffeescript, but it's working with a full javascript app.

We use scss for the CSS.

Sprites automatically generated and can be displayed through the mixin `@include sprite('filename')`.

Favicons can be generated thanks to `gulp favicons`.

Everything is minimised with `gulp dist`.




