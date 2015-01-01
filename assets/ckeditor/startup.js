(function() {
    var MSG_EDITABLE = 'You can edit this field by a click with ';
    var MSG_CONTENTINFO = 'The content will be edited is: ';

    var extras = {
        anchor: {
            name: 'links',
            items: ['Link', 'Unlink', '-', 'Anchor']
        },
        links: {
            name: 'links',
            items: ['Link', 'Unlink']
        },
        subsuper: {
            name: 'subsuper',
            items: ['Subscript', 'Superscript']
        },
        embed: {
            name: 'embed',
            items: ['oembed']
        },
        align: {
            name: 'align',
            items: ['JustifyLeft',
                    'JustifyCenter',
                    'JustifyRight',
                    'JustifyBlock']
        },
        colors: {
            name: 'colors',
            items: ['TextColor', 'BGColor']
        },
        tools: {
            name: 'tools',
            items: ['SpecialChar',
                    '-',
                    'RemoveFormat',
                    'Maximize',
                    '-',
                    'Source']
        },
        image: {
            name: 'image',
            items: ['Image']
        }
    };
    var toolbar = [{
        name: 'inlinesave',
        items: ['EditableSave']
    }, {
        name: 'styles',
        items: ['Format']
    }, {
        name: 'basicstyles',
        items: ['Bold', 'Italic', 'Underline', 'Strike']
    }, {
        name: 'paragraph',
        items: ['NumberedList',
                'BulletedList',
                'Indent',
                'Outdent',
                '-',
                'Blockquote']
    },
    {
        name: 'table',
        items: ['Table']
    }];

    if (CKEDITOR) {
        CKEDITOR.plugins.addExternal('editable', '/extensions/vendor/bolt/editable/assets/ckeditor/plugins/editable/', 'plugin.js');
        CKEDITOR.config.extraPlugins = 'editable';
        CKEDITOR.config.autoParagraph = false;

        $(document).ready(function() {
            $('body').append('<div id="ext-editable-popup" style="display:none"/>');
        });

        CKEDITOR.on('instanceCreated', function(event) {
            var editor = event.editor;
            var $element = $(editor.element.$);
            var options = $element.data('options') || [];
            var tbItems = toolbar;

            if (typeof options == "string") {
                options = options.split(',');
            }
            for (var i = 0; i < options.length; i++) {
                var menuItem = extras[options[i]];
                if (typeof menuItem == "object") {
                    tbItems = tbItems.concat(menuItem);
                }
            }

            editor.on('configLoaded', function() {
                editor.config.toolbar = tbItems;
            });

            var parameters = $element.attr('data-parameters');
            if (parameters) {
                editor.on('instanceReady', function() {
                    var target = JSON.parse(parameters);
                    $element.attr('title', MSG_EDITABLE + $element.attr('title')
                                           + '.\n' + MSG_CONTENTINFO
                                           + target.contenttypeslug + '@'
                                           + target.fieldname);
                });
            }

        });
    }
    /*
     * @todo Implement snapshot capturing of a block and following changes
     */
})();
