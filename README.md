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
