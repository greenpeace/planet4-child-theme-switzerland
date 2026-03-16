# Greenpeace Planet 4 Child Theme for GP Switzerland

Child theme for the Planet 4 Wordpress project.
The related master theme’s code lives at: 

[https://github.com/greenpeace/planet4-master-theme](https://github.com/greenpeace/planet4-master-theme).

Please check the master theme code for more information. 

## Development

### Coding standards

Using mostly WordPress coding standards, with a few exceptions. 

Make sure to use the configured code linters for PHP, JS, CSS and Markdown. See [package.json](package.json) for the commands.

### Stylesheets

Three stylesheets are generated using SCSS:

``` bash
sass src/scss/style.scss style.css --style=compressed
sass src/scss/editor-style.scss admin/css/editor-style.css --style=compressed
```

### Translations

1. Export a pot file using wp-cli (Don't use WPML for the .pot file, it will not contain the references where the strings are used). When used inside the dev environment: `npx wp-env run cli wp i18n make-pot wp-content/themes/planet4-child-theme-switzerland/ wp-content/themes/planet4-child-theme-switzerland/languages/planet4-child-theme-switzerland.pot`

If your most up to date translation is in WPML:

1. Scan the theme using for new translation strings using WPML. Make sure the scanning of Javascript is enabled if you need to find strings in JS.
2. Update the translations in WPML string translation
3. Export .po files for each language from WPML and save them to the theme's /languages folder:
3.1 Go to WPML String Translation
3.2 Filter by the translation domain you want to export
3.3 Select all strings
3.4 Export a .po file without translations and a separate one for each language. The export functionality is below the strings table.
4. Update the po files to also contain the references to where the strings are used using POEdit:
4.1 Open each .po file in POEdit
4.2 Go to Translation -> Update from .pot file and select the .pot file exported in 1.
4.3 Save

If your most up to date translations are in .po files, use the same process, but don't overwrite the .po files from WPML

5. Generate the JSON files needed to translate strings in JS files: `npx wp-env run cli wp i18n make-json wp-content/themes/planet4-child-theme-switzerland/languages/`
6. Rename the generated JSON files. The hashes are not needed, but make sure the language code is exactly the same as the language. So for us `de_CH`, `fr_FR` and `it_CH`. `de` for example doesn't work as a fallback. A valid name is for example: `planet4-child-theme-switzerland-de_CH-planet4-child-theme-switzerland-food-quiz-view-script`



## PHPStorm settings

### File watchers for SCSS

File > Settings > Tools > File Watchers

#### Main style.css

**File Type:** SCSS Style Sheet

**Scope:** Create a scope that:

* Includes `src/scss`
* Excludes  `/src/scss/editor-style.scss`

**Program:** sass

**Arguments:**

`--no-cache --update $FileName$:$ProjectFileDir$/$FileNameWithoutExtension$.css --style compressed`

**Output paths to refresh:**

`$ProjectFileDir$/$FileNameWithoutExtension$.css`

#### editor-style.css

**File Type:** SCSS Style Sheet

**Scope:** Create a scope that:

* Includes `src/scss`
* Excludes  `/src/scss/style.scss`

**Program:** sass

**Arguments:**
`--no-cache --update $FileName$:$ProjectFileDir$/admin/css/$FileNameWithoutExtension$.css --style compressed`

**Output paths to refresh:**
`$ProjectFileDir$/admin/css/$FileNameWithoutExtension$.css`
