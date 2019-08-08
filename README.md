# Glossary Plugin

The **Glossary** Plugin for [Grav CMS](http://github.com/getgrav/grav) allows you to maintain a glossary of terms that can be formatted into a searchable page, as well as optionally inserting `<abbrev>` tags into your content via Markdown Extra.

## Example output

![Example output of the Glossary plugin in details/summary mode, with the search bar](assets/example_output.png)


## Installation

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm), either through the Admin panel, or via your system's terminal by navigating to your site root and running:

```sh
  bin/gpm install glossary
```

### Manual Installation

Alternatively, you can download a zip of this repository, unzip it to `/your/site/grav/user/plugins`, and rename the folder to `glossary`.


## Usage

### Glossary terms

Glossary terms are defined in the plugin configuration via the `definitions` key. This should contain a list of key-value pairs that define each term, as shown below:

```yaml
definitions:
  -
    term: Some long complicated term
    abbrev: SLCT
    definition: "Definition of the term (can include markdown)"
```

The `term` is required, and you should define at least one of `abbrev` and `definition`. Items that have only a term and abbreviation are excluded from the glossary, but will be applied to your pages as abbreviations if this functionality is enabled.

The definition will be formatted with Markdown when inserted it into the template, so you can include links, emphasis, etc.


### Glossary page

Adding a glossary page to your site is as simple as creating a page with the `glossary_plugin` template.

There are two modes for printing the glossary contents, which are set in the plugin configuration via the `item_template` key:

- `dt_dd`: This uses HTML definition lists (ie. `<dl>`, `<dt>`, `<dd>`).
- `details_summary` makes use of the accordion panel-like functionality of `<details>` and `<summary>` tags. Some browsers, such as Edge, don't support the opening and closing of `details` tags, but fallback to having them always open, so the content should always be accessible to visitors.

Glossary items are listed in alphabetical order. If a glossary item has no definition, it is ignored by the template and thus excluded from the glossary page. If an abbreviation is defined, this will be included in brackets after the term.

You are of course free to write your own templates based on the ones provided if you want. If you think that your changes are more widely useful, a pull request would be welcomed.


### Site-wide abbreviations

You can add abbreviation tags `<abbrev>` to all abbreviations on your site by enabling Markdown Extra and the `abbreviations` configuration option of this plugin. When a user hovers on these elements, the long form of the abbreviated term is shown.

**Note:** Currently, abbreviations only work within the page content, and so tags aren't applied to parts of the page that are generated by other means.


## Credits

The implementation of the abbreviation insertion is taken from the [acronyms](https://github.com/asmeikal/grav-plugin-acronyms) plugin originally developed by [Michele Laurenti](https://github.com/asmeikal). If you just want the acronym component of this plugin, you could use the original [acronyms](https://github.com/asmeikal/grav-plugin-acronyms) plugin, although it is not currently in the GPM.


## License

This plugin is licensed under the [MIT License](LICENSE)
