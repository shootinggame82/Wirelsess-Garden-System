/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function(config) {
  // Define changes to default configuration here. For example:
  // config.language = 'fr';
  // config.uiColor = '#AADC6E';
  config.toolbar = [{
      name: 'document',
      items: ['Preview', 'Print']
    },
    {
      name: 'clipboard',
      items: ['Cut', 'Copy', 'Paste', 'PasteText', '-', 'Undo', 'Redo']
    },
    {
      name: 'editing',
      items: ['Find', 'Replace', '-', 'SelectAll']
    },
    '/',
    {
      name: 'basicstyles',
      items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat']
    },
    {
      name: 'paragraph',
      items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']
    },
    {
      name: 'links',
      items: ['Link', 'Unlink', 'Anchor']
    },
    {
      name: 'insert',
      items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']
    },
    '/',
    {
      name: 'styles',
      items: ['Styles', 'Format', 'Font', 'FontSize']
    },
    {
      name: 'colors',
      items: ['TextColor', 'BGColor']
    },
    {
      name: 'tools',
      items: ['Maximize', 'ShowBlocks']
    },
    {
      name: 'about',
      items: ['About']
    }
  ];
};