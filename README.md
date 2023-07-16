# Information

Интеграция рейтинга в статью.

## Install

1. Загрузите папки и файлы в директорию `extensions/MW_EXT_Rating`.
2. В самый низ файла `LocalSettings.php` добавьте строку:

```php
wfLoadExtension( 'MW_EXT_Rating' );
```

## Syntax

```html
{{#rating: title = [TITLE]
  |count = [NUMBER]
  |icon-plus = fas fa-[ICON]
  |icon-minus = fas fa-[ICON]
}}
```
