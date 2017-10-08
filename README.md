# Intro

The currently available Lang managers are based on parsing translation files into the database then save back to files again, which am not a fan of.

So i decided to make **Lingo** which is file based only and the work flow is
> + get all the folders in `resources/lang`
> + divide them into `default` & `vendor`
> + show the **folder, files, locales** based on active tab `default` or `vendor`

# Installation

- `composer require ctf0/lingo`

- (Laravel < 5.5) add the service provider

```php
'providers' => [
    ctf0\Lingo\LingoServiceProvider::class,
]
```

- install dependencies

```bash
yarn add vue vuemit vue-notif
# or
npm install vue vuemit vue-notif --save
```

- publish the package assets with

`php artisan vendor:publish --provider="ctf0\Lingo\LingoServiceProvider"`

- after installation, package will auto-add
    + package routes to `routes/web.php`
    + package assets compiling to `webpack.mix.js`
    + `MIX_LINGO_FRAMEWORK=bulma` to `.env`

## Features

- add/remove "vendor/locale/file"
- show/hide different elements to avoid noise & keep the user focused
- use localeStorage to remember opened "tab/folder/files"
- validate for "vendor/locale/file" existence on the fly
- show guiding steps while adding new vendor for better UX.

# Usage

- for styling we use ***bulma***

> ***Or Use another Framework***
>
> - duplicate `resources/views/vendor/Lingo/lingo-bulma` and rename it to the framework you want ex.`lingo-bootstrap`
> - duplicate `assets/vendor/Lingo/js/bulma` and rename it to the framework you want ex.`bootstrap`
> - duplicate `assets/vendor/Lingo/sass/bulma.scss` and rename it to the framework you want ex.`bootstrap.scss`
> - set `MIX_LINGO_FRAMEWORK` to the framework name ex.`MIX_LINGO_FRAMEWORK=bootstrap`
> - start editing the new files.

- add this one liner to your main js file and run `npm run watch` to compile your `js/css` files.
    + if you are having issues with `npm run production`, [Check](https://ctf0.wordpress.com/2017/09/12/laravel-mix-es6/)

```js
require('./../vendor/Lingo/js/lingo')

new Vue({
    el: '#app'
})
```

- now visit `localhost:8000/lingo`

# Notes

- we use [Laravel Langman](https://github.com/themsaid/laravel-langman) for scanning for missing translation keys, but if you have better alternative plz open a ticket.

- Atm the package doesn't support multi level/nested arrays so any PRs / ideas are welcomed.

    however, if a file with nested arrays is detected a warning will show up so you don't accidentally save the file as we overwrite files on save.
