# Visual Editor Field for Backpack

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

This package provides a visual editor field type for the [Backpack for Laravel](https://backpackforlaravel.com/) administration panel. The visual editor field allows admins to use a visual composer like editor in a field to create their own content.

## Screenshots

![Backpack Visual Editor Field Addon](url)

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
    'label' => "Visual Editor",
    'type' => 'visual-editor',
    'view_namespace' => 'visual-editor-for-backpack::fields',
]);
```

Notice the ```view_namespace``` attribute - make sure that is exactly as above, to tell Backpack to load the field from this package, instead of assuming it's inside the _Backpack\CRUD package_.


## Extending

If you need to add more custom fields to editor, you need to follow these steps.
1. Create views<br>
    You need to create 2 files, ```backend.blade.php``` and ```frontend.blade.php``` within a subfolder on ```views/vendor/visual-editor-for-backpack/blocks```.<br>
Example: ```views/vendor/visual-editor-for-backpack/blocks/newField/backend.blade.php``` and ```views/vendor/visual-editor-for-backpack/blocks/newField/frontend.blade.php```<br/>
The ```backend.blade.php``` have everything you need to manage admin input<br>
The ```frontend.blade.php``` have everything you need to show result on site
2. If you have some translations to load into your field create a file inside ```resources/lang/vendor/visual-editor-for-backpack/LANG/blocks``` with the name of the field like ```resources/lang/vendor/visual-editor-for-backpack/LANG/blocks/newField.php```, remember to create a new file for every language you use
3. Create a new class with the name of you field in ```app/Blocks``` like ```app/Blocks/NewField.php```
    start with something like:
    ```php
    <?php
    namespace app\Blocks;
    
    use Bedoz\VisualEditorForBackpack\Blocks\Block;
    
    class Newfield extends Block {
      public static $name = 'newField';
      public static $label = 'New Field Label';
    
      static public function pushStyle() {
          return "";
      }
    
      static public function pushScripts() {
          return "";
      }
    }
    ```
    Here you can use pushStyle and pushScripts to load your CSS and JS.
4. Final you must add your class inside ```visual-editor.php``` config file like below:
    ```BOH
   return [
       'blocks' => [
            \Bedoz\VisualEditorForBackpack\Blocks\Slideshow::class,
    ++      \App\Blocks\Newfield::class,
       ],
   ];
   ```

## Change log

Please see the [changelog](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](CONTRIBUTING.md) for details and a todolist.

## Security

If you discover any security related issues, please email [the author](composer.json) instead of using the issue tracker.

## Credits

- [Bedoz][link-author] - creator of this package;
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/digitallyhappy/toggle-field-for-backpack.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/digitallyhappy/toggle-field-for-backpack.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/digitallyhappy/toggle-field-for-backpack
[link-downloads]: https://packagist.org/packages/digitallyhappy/toggle-field-for-backpack
[link-author]: https://github.com/bedoz
[link-contributors]: ../../contributors