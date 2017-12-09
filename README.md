
# EDD Software Licensing Translations Updater

* Contributors: [Andy Fragen](https://github.com/afragen)
* Tags: edd software licensing, plugin, theme, language pack, updater
* Requires at least: 4.6
* Requires PHP: 5.6
* Tested up to: 4.9
* Stable tag: master
* Donate link: http://thefragens.com/translations-updater-donate
* License: MIT
* License URI: http://www.opensource.org/licenses/MIT

This plugin is an EDD Software Licensing extension that will allow for decoupled language pack updates for your plugins utilizing EDD Software Licensing that are hosted on public repositories in GitHub, Bitbucket, or GitLab.

## Description

### Plugins

You must add and additional element to the array in your `EDD_SL_Plugin_Updater` setup. The array will be similar to the following from the `edd-sample-plugin.php`.

```php
	$edd_updater = new EDD_SL_Plugin_Updater( EDD_SAMPLE_STORE_URL, __FILE__, array(
			'version' 	=> '1.0',                // current version number
			'license' 	=> $license_key,         // license key (used get_option above to retrieve from DB)
			'item_name' => EDD_SAMPLE_ITEM_NAME, // name of this plugin
			'author' 	=> 'Pippin Williamson',  // author of this plugin
			'beta'		=> false,
			'languages' => 'https://github.com/<USER>/my-language-pack',
		)
```

 The URI should point to a repository that contains the translations files. It is created using the [Language Pack Maker](https://github.com/afragen/language-pack-maker). The repo **must** be a public repo.
