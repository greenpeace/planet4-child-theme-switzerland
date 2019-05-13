# Greenpeace Planet 4 Child Theme for GP Switzerland

Child theme for the Planet 4 Wordpress project.
The related master theme’s code lives at: 

https://github.com/greenpeace/planet4-master-theme.

Please check the master theme code for more information. 

# Development

## Stylesheets

Two stylesheets are generated using SCSS:
* src/scss/style.scss --> style.css
* src/scss/editor-style.scss --> admin/css/editor-style.css

## Autoloader

Composer autoloader is used. Use `composer dump-autoload` after adding new classes.

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
