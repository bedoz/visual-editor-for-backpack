# Visual Editor Field for Backpack

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

This package provides a visual editor field type for the [Backpack for Laravel](https://backpackforlaravel.com/) administration panel. The visual editor field allows admins to use a visual composer like editor in a field to create their own content.

## Screenshots

![Backpack Toggle Field Addon](url)

## Installation

Via Composer

``` bash
composer require bedoz/visual-editor-for-backpack
```

## Usage

Inside your custom CrudController:

```php
$this->crud->addField([
    'name' => 'editor',
    'label' => 'Visual Editor',
    'type' => 'editor',
    'view_namespace' => 'visual-editor-for-backpack::editor',
]);
```

Notice the ```view_namespace``` attribute - make sure that is exactly as above, to tell Backpack to load the field from this _addon package_, instead of assuming it's inside the _Backpack\CRUD package_.


## Overwriting

If you need to add more custom fields to editor, ...


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email [the author](composer.json) instead of using the issue tracker.

## Credits

- [Bedoz][link-author] - creator of this editor;
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/digitallyhappy/toggle-field-for-backpack.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/digitallyhappy/toggle-field-for-backpack.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/digitallyhappy/toggle-field-for-backpack
[link-downloads]: https://packagist.org/packages/digitallyhappy/toggle-field-for-backpack
[link-author]: https://github.com/bedoz
[link-contributors]: ../../contributors