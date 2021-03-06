Editable In Place Editor extension for Bolt CMS
===============================================

This extension extends administation functionality of Bolt on Frontend with enabling in-place editing some content.

Administrator or chief editor of the site may think there are some type of content on the site which may be editable in place
where it is. This function could be useful if editor wants to see instantly how the edited content would looks like in its page
context, or some of them can be touched quickly without entering dashboard.

Installation under Bolt 1.x
===========================

  - Download and extract the extension to a directory called Visitors in your
    Bolt extension directory.
  - Copy `config.yml.dist` to `config.yml` in the same directory.
  - Align configuration setting as comments shows in
  - Use `editable()` twig function in template to define which content can be edited

and under Bolt v2.x
===================

  - ensure that `/extensions` folder exists and is writable under Bolt's home
  - On admin backend click `Extend`
  - edit `config.yml` or `config_local.yml` accordingly as written below
  - Use `editable()` twig function in template to define which content can be edited

Usage
=====

The function `{{ editable(field, record, options) }}` will place the editable content in the output page. The content is a
`value` of a `field` in a specified `record`. If you have a contenttype like below


```
pages:
    name: Pages
    singular_name: Page
    fields:
        title:
            type: text
            class: large
        slug:
            type: slug
            uses: title
        image:
            type: image
        teaser:
            type: html
            height: 150px
```

you may set the `teaser` field of a record to editable with the following twig command:

``{{ editable('teaser', record) }}``

`record` can be any content object available either implicitly or explicitly.
If optional `record` parameter can be omitted by default then record from the template context will be selected.

If the actual visitor has logged in and has corresponding permissions to change then can edit the content.
Moving the mouse over the editable area of the page an `Edit` button will float over that should raise the editor toolbar.

Options in CKeditor
-------------------

Options in CKeditor just configures toolbar button groups and doesn't adds or removes any plugin.
Toolbar functions are grouped by group name which defines available toolbar functions internally.
Following toolbar functions enabled under a group name by default and these can't be turned off:

* inlinesave: EditableSave
* styles: Format
* basicstyles: Bold, Italic, Underline, Strike
* paragraph: NumberedList, BulletedList, Indent, Outdent, Blockquote
* table: Table

These are the optional toolbar elements:

* anchor: Link, Unlink, Anchor
* links: Link, Unlink
* subsuper: Subscript, Superscript
* embed: MediaEmbed
* align: JustifyLeft, JustifyCenter, JustifyRight, JustifyBlock
* colors: TextColor, BGColor
* tools: SpecialChar, RemoveFormat, Maximize, Source

Enable a group in editor toolbar just list the group name in the `option` parameter this way:

``{{ editable('teaser', record, 'anchor, subsuper') }}`` or ``{{ editable('teser', record, [ 'anchor', 'subsuper' ]) }}``

About CKEditor
--------------

Extension has made to support internal CKEditor boundled in Bolt but may use with your custom build with CKEditor download site.
This case just copy your distribution to ``Editable/assets/ckeditor`` and (I hope) no any special settings required.

About Raptor
------------

<a href="https://www.raptor-editor.com/" target="_blank">Raptor</a> is LGPL licensed Javascript in-place editor.
 About its configuration and API please visit the site.

To keep Bolt project size low, Raptor editor has removed from extension.
If you need to reimplement, check ``Raptor.php`` and ``config.yml.dist`` and put html related assets in ``assets/raptor/``.

Notes
=====

Please feel free to make modifications or changes. Theoretically any kind of in-place WYSYWYG editor can be used
with a little alignment.
I hope my code may contains bugs or mistakes that would means I'm still alive. :-)

Release Notes
=============

* 06-10-2014
** No option for editing images, corrected.
** Replacing annoying alert messages on save result

* 06-10-2014
** Display a brief info on mouse over about the area which is editable

* 08-18-2014
** Moving to Bolt v2.0

* 12-31-2014
** Bugs with Bolt final

* 01-02-2014
** https://github.com/rixbeck/bolt-extension-editable/issues/4

