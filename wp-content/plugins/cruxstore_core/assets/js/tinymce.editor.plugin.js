(function() {


    /* Register the buttons */
    tinymce.create('tinymce.plugins.KTButtons', {
        init : function(editor, url) {

            /**
             * Inserts shortcode content
             */
            editor.addButton( 'cruxstore_shortcode', {
                title : 'KiteThemes shortcode',
                icon: 'icon dashicons-editor-code',
                type: 'menubutton',
                menu: [
                    {
                        text: 'Structure',
                        menu: [
                            {
                                text: 'Row',
                                onclick: function() {
                                    editor.insertContent('[vc_row fullwidth="false"][vc_column width="1/1"]Place Content Here[/vc_column][/vc_row]');
                                }
                            }, {
                                text: 'Column 1/2',
                                onclick: function() {
                                    editor.insertContent('[vc_column width="1/2"]Place Content Here[/vc_column]');
                                }
                            }, {
                                text: 'Column 1/3',
                                onclick: function() {
                                    editor.insertContent('[vc_column width="1/3"]Place Content Here[/vc_column]');
                                }
                            }, {
                                text: 'Column 1/4',
                                onclick: function() {
                                    editor.insertContent('[vc_column width="1/4"]Place Content Here[/vc_column]');
                                }
                            }, {
                                text: 'Column 1/6',
                                onclick: function() {
                                    editor.insertContent('[vc_column width="1/6"]Place Content Here[/vc_column]');
                                }
                            }, {
                                text: 'Column 2/3',
                                onclick: function() {
                                    editor.insertContent('[vc_column width="2/3"]Place Content Here[/vc_column]');
                                }
                            }, {
                                text: 'Column 3/4',
                                onclick: function() {
                                    editor.insertContent('[vc_column width="3/4"]Place Content Here[/vc_column]');
                                }
                            }, {
                                text: 'Column 5/6',
                                onclick: function() {
                                    editor.insertContent('[vc_column width="5/6"]Place Content Here[/vc_column]');
                                }
                            }, {
                                text: '1/2 + 1/2',
                                onclick: function() {
                                    editor.insertContent('[vc_row][vc_column width="1/2"][/vc_column][vc_column width="1/2"][/vc_column][/vc_row]');
                                }
                            }, {
                                text: '1/3 + 1/3 + 1/3',
                                onclick: function() {
                                    editor.insertContent('[vc_row][vc_column width="1/3"][/vc_column][vc_column width="1/3"][/vc_column][vc_column width="1/3"][/vc_column][/vc_row]');
                                }
                            }, {
                                text: '1/4 + 1/4 + 1/4 + 1/4',
                                onclick: function() {
                                    editor.insertContent('[vc_row][vc_column width="1/4"][/vc_column][vc_column width="1/4"][/vc_column][vc_column width="1/4"][/vc_column][vc_column width="1/4"][/vc_column][/vc_row]');
                                }
                            }, {
                                text: '2/3 + 1/3',
                                onclick: function() {
                                    editor.insertContent('[vc_row][vc_column width="2/3"][/vc_column][vc_column width="1/3"][/vc_column][/vc_row]');
                                }
                            }, {
                                text: '3/4 + 1/4',
                                onclick: function() {
                                    editor.insertContent('[vc_row][vc_column width="3/4"][/vc_column][vc_column width="1/4"][/vc_column][/vc_row]');
                                }
                            }, {
                                text: '1/4 + 3/4',
                                onclick: function() {
                                    editor.insertContent('[vc_row][vc_column width="1/4"][/vc_column][vc_column width="3/4"][/vc_column][/vc_row]');
                                }
                            }, {
                                text: '1/4 + 1/2 + 1/4',
                                onclick: function() {
                                    editor.insertContent('[vc_row][vc_column width="1/4"][/vc_column][vc_column width="1/2"][/vc_column][vc_column width="1/4"][/vc_column][/vc_row]');
                                }
                            }, {
                                text: '1/6 + 3/4 + 1/6',
                                onclick: function() {
                                    editor.insertContent('[vc_row][vc_column width="1/6"][/vc_column][vc_column width="2/3"][/vc_column][vc_column width="1/6"][/vc_column][/vc_row]');
                                }
                            }, {
                                text: '1/6 + 1/6 + 1/6 + 1/6 + 1/6 + 1/6',
                                onclick: function() {
                                    editor.insertContent('[vc_row][vc_column width="1/6"][/vc_column][vc_column width="1/6"][/vc_column][vc_column width="1/6"][/vc_column][vc_column width="1/6"][/vc_column][vc_column width="1/6"][/vc_column][vc_column width="1/6"][/vc_column][/vc_row]');
                                }
                            }
                        ]
                    } ,{
                        text: 'Typography',
                        menu: [
                            {
                                text: 'Dropcaps',
                                onclick: function() {
                                    editor.insertContent('[cruxstore_dropcaps text="#ed8b5c"]D[/cruxstore_dropcaps]');
                                }
                            }, {
                                text: 'Tooltip',
                                onclick: function() {
                                    editor.insertContent('[cruxstore_tooltip text="Text" tooltip_text="Tooltip Text" href="URL"]');
                                }
                            }, {
                                text: 'Highlight Text',
                                onclick: function() {
                                    editor.insertContent('[cruxstore_highlight text="Text" text_color="#ffffff" background="#ed8b5c"]');
                                }
                            }, {
                                text: 'Custom List',
                                onclick: function() {
                                    editor.insertContent('[cruxstore_custom_list style="48" icon_color="#00c8d7"]<ul><li>List Item</li><li>list Item</li></ul>[/cruxstore_custom_list]');
                                }
                            }, {
                                text: 'Message Box',
                                onclick: function() {
                                    editor.insertContent('[cruxstore_message t title="Your message title comes here!"]Content Goes Here[/cruxstore_message]');
                                }
                            }
                        ]
                    }
                ]
            });

        },
        createControl : function(n, cm) {
            return null;
        }
    });
    /* Start the buttons */
    tinymce.PluginManager.add( 'shortcode_button_script', tinymce.plugins.KTButtons );


})();