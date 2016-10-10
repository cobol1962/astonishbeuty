/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.


config.toolbar = 'Post';
config.extraPlugins = 'font';
config.toolbar_Post = [
	//{ name: 'styles', items: [ 'Styles', 'Format' ] },
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup', 'spellchecker' ], items: [ 'Undo', 'Redo', 'Bold', 'Italic', 'Strike','Underline', 'Styles', 'Font', 'FontSize', 'TextColor'] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter' , 'JustifyRight' , 'JustifyBlock' ] },
	{ name: 'insert', items: [ 'Image', 'Link', 'Anchor', 'Table', 'HorizontalRule', 'SpecialChar', 'Scayt' ] },
	{ name: 'source', items: [ 'Source' ] },
  { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
  { name: 'save', items: [ 'Save' ] }
];

config.allowedContent = true;
config.toolbar = 'Full';
config.toolbar_Full = [
	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Scayt' ] },
	{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
	{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
	{ name: 'source', items: [ 'Source' ] },
	'/',
	{ name: 'styles', items: [ 'Styles', 'Format','Font','FontSize','TextColor', 'BGColor' ] },
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'left', 'right', 'center', 'justify' ] }
	
];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	//config.removeButtons = 'Underline,Subscript,Superscript';

	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Make dialogs simpler.
	//config.removeDialogTabs = 'image:advanced;link:advanced';
	
};


CKEDITOR.stylesSet.add( 'custom',
[

    // Inline styles
    { name : 'Header', element : 'span', attributes : { 'class': 'font1 head1' } },
    { name : 'Sub Header' , element : 'h2', styles : { 'font-size' : '20pt' } },
    { name : 'Sub Header2', element : 'span', styles : { 'font-size' : '25pt', 'color' : '#17536f', 'font-family' : 'Lusitana, serif'   } },
    { name : 'Paragrah Header' , element : 'span', styles : { 'font-size' : '17pt', 'font-weight' : 'bold' } } ,
    { name : 'cols2', element : 'div', attributes : { 'class': 'cols2' } },
    { name : 'venueContainer', element : 'div', attributes : { 'class': 'venueContainer' } },
    { name : 'col1', element : 'div', attributes : { 'class': 'col1' } },
    { name : 'col2', element : 'div', attributes : { 'class': 'col2' } }
]);