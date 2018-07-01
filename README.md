
# EDD Translations Updater

* Contributors: [Andy Fragen](https://github.com/afragen)
* Tags: edd software licensing, language pack, updater
* Requires at least: 4.6
* Requires PHP: 5.4
* Tested up to: 4.9
* Stable tag: master
* Donate link: <http://thefragens.com/translations-updater-donate>
* License: MIT
* License URI: <http://www.opensource.org/licenses/MIT>

## Description

This plugin is an EDD Software Licensing extension that will allow for decoupled language pack updates for your plugins or themes utilizing EDD Software Licensing that are hosted on public repositories in GitHub, Bitbucket, or GitLab.

You will need to update to the latest versions of the updaters in the EDD Software Licensing sample code to ensure the appropriate action hooks are present.

You will need to add a key/value pair to your setup array similar to the following,
`'languages' => 'https://github.com/<USER>/my-language-pack'`

### Plugins

You must add an additional key/value pair to the setup array in your `EDD_SL_Plugin_Updater` setup. The array will be similar to the following from the `edd-sample-plugin.php` file.

```php
	$edd_updater = new EDD_SL_Plugin_Updater( EDD_SAMPLE_STORE_URL, __FILE__, array(
			'version'   => '1.0',                // current version number
			'license'   => $license_key,         // license key (used get_option above to retrieve from DB)
			'item_name' => EDD_SAMPLE_ITEM_NAME, // name of this plugin
			'author'    => 'Pippin Williamson',  // author of this plugin
			'beta'      => false,
			'languages' => 'https://github.com/<USER>/my-language-pack',
		)
```

### Themes

You must add an additional key/value pair to the setup array in your `EDD_Theme_Updater_Admin` setup. The array will be similar to the following from the `edd-sample-theme/updater/theme-updater.php` file.

```php
$updater = new EDD_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => 'https://easydigitaldownloads.com', // Site where EDD is hosted
		'item_name'      => 'Theme Name', // Name of theme
		'theme_slug'     => 'theme-slug', // Theme slug
		'version'        => '1.0.0', // The current version of this theme
		'author'         => 'Easy Digital Downloads', // The author of this theme
		'download_id'    => '', // Optional, used for generating a license renewal link
		'renew_url'      => '', // Optional, allows for a custom license renewal link
		'beta'           => false, // Optional, set to true to opt into beta versions
		'languages'      => 'https://github.com/<USER>/my-language-pack',
	),
	...
```

 The URI should point to a repository that contains the translations files. Refer to [GitHub Updater Translations](https://github.com/afragen/github-updater-translations) as an example. It is created using the [Language Pack Maker](https://github.com/afragen/language-pack-maker). The repo **must** be a public repo.
