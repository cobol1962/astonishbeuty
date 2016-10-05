/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview The "groupedcolorbutton" plugin that makes it possible to 
 *               assign text and background colors to editor contents, and
 *               adds group headers to the colors.
 *
 */
CKEDITOR.plugins.add( 'groupedcolorbutton',
{
	requires : [ 'panelbutton', 'floatpanel', 'styles' ],

	init : function( editor )
	{
		var config = editor.config,
			lang = editor.lang.colorButton;

		var clickFn;

		if ( !CKEDITOR.env.hc )
		{
			addButton( 'TextColor', 'fore', lang.textColorTitle );
			addButton( 'BGColor', 'back', lang.bgColorTitle );
		}

		function addButton( name, type, title )
		{
			var colorBoxId = CKEDITOR.tools.getNextId() + '_colorBox';
			editor.ui.add( name, CKEDITOR.UI_PANELBUTTON,
				{
					label : title,
					title : title,
					className : 'cke_button_' + name.toLowerCase(),
					modes : { wysiwyg : 1 },

					panel :
					{
						css : editor.skin.editor.css,
						attributes : { role : 'listbox', 'aria-label' : lang.panelTitle }
					},

					onBlock : function( panel, block )
					{
						block.autoSize = true;
						block.element.addClass( 'cke_colorblock' );
						block.element.setHtml( renderColors( panel, type, colorBoxId ) );
						// The block should not have scrollbars (#5933, #6056)
						block.element.getDocument().getBody().setStyle( 'overflow', 'hidden' );

						CKEDITOR.ui.fire( 'ready', this );

						var keys = block.keys;
						var rtl = editor.lang.dir == 'rtl';
						keys[ rtl ? 37 : 39 ]	= 'next';					// ARROW-RIGHT
						keys[ 40 ]	= 'next';					// ARROW-DOWN
						keys[ 9 ]	= 'next';					// TAB
						keys[ rtl ? 39 : 37 ]	= 'prev';					// ARROW-LEFT
						keys[ 38 ]	= 'prev';					// ARROW-UP
						keys[ CKEDITOR.SHIFT + 9 ]	= 'prev';	// SHIFT + TAB
						keys[ 32 ]	= 'click';					// SPACE
					},

					// The automatic colorbox should represent the real color (#6010)
					onOpen : function()
					{
						var selection = editor.getSelection(),
							block = selection && selection.getStartElement(),
							path = new CKEDITOR.dom.elementPath( block ),
							color;

						// Find the closest block element.
						block = path.block || path.blockLimit || editor.document.getBody();

						// The background color might be transparent. In that case, look up the color in the DOM tree.
						do
						{
							color = block && block.getComputedStyle( type == 'back' ? 'background-color' : 'color' ) || 'transparent';
						}
						while ( type == 'back' && color == 'transparent' && block && ( block = block.getParent() ) );

						// The box should never be transparent.
						if ( !color || color == 'transparent' )
							color = '#ffffff';

						this._.panel._.iframe.getFrameDocument().getById( colorBoxId ).setStyle( 'background-color', color );
					}
				});
		}


		function renderColors( panel, type, colorBoxId )
		{
			var output = [];
			var colors = [];
			var total = config.colorButton_enableMore ? 2 : 1;
			
			for (var i = 0; i < config.groupedColorButton_colors.length; i++)
			{
				var source = config.groupedColorButton_colors[i];
				var dest = { title: source.title, colors: source.colors.split(",") };
				if (dest.colors.length === 0) continue;
				total += dest.colors.length;
				colors.push(dest);
			}

			var clickFn = CKEDITOR.tools.addFunction( function( color, type )
				{
					if ( color == '?' )
					{
						var applyColorStyle = arguments.callee;
						function onColorDialogClose( evt )
						{
							this.removeListener( 'ok', onColorDialogClose );
							this.removeListener( 'cancel', onColorDialogClose );

							evt.name == 'ok' && applyColorStyle( this.getContentElement( 'picker', 'selectedColor' ).getValue(), type );
						}

						editor.openDialog( 'colordialog', function()
						{
							this.on( 'ok', onColorDialogClose );
							this.on( 'cancel', onColorDialogClose );
						} );

						return;
					}

					editor.focus();

					panel.hide( false );

					editor.fire( 'saveSnapshot' );

					// Clean up any conflicting style within the range.
					new CKEDITOR.style( config['colorButton_' + type + 'Style'], { color : 'inherit' } ).remove( editor.document );

					if ( color )
					{
						var colorStyle = config['colorButton_' + type + 'Style'];

						colorStyle.childRule = type == 'back' ?
							function( element )
							{
								// It's better to apply background color as the innermost style. (#3599)
								// Except for "unstylable elements". (#6103)
								return isUnstylable( element );
							}
							:
							function( element )
							{
								// Fore color style must be applied inside links instead of around it. (#4772,#6908)
								return !( element.is( 'a' ) || element.getElementsByTag( 'a' ).count() ) || isUnstylable( element );
							};

						new CKEDITOR.style( colorStyle, { color : color } ).apply( editor.document );
					}

					editor.fire( 'saveSnapshot' );
				});

			// Render the color groups.
			output.push('<table role="presentation" cellspacing=0 cellpadding=0 width="100%">');
			for (var i = 0; i < colors.length; i++)
			{
				var group = colors[i];
				
				if (0 !== group.title.length)
				{
					output.push("<tr><td colspan=\"8\">");
					output.push("<h1 role=\"presentation\" class=\"cke_panel_grouptitle\">", group.title, "</h1>");					
					output.push("</td></tr>");
				}
				
				for (var j = 0; j < group.colors.length; j++)
				{
					if (j === 0)
					{
						output.push("<tr>");
					}
					else if ((j % 8) === 0)
					{
						output.push("</tr><tr>");
					}
					
					var parts = group.colors[j].split("/");
					var colorName = parts[0];
					var colorCode = parts[1] || colorName;

					// The data can be only a color code (without #) or colorName + color code
					// If only a color code is provided, then the colorName is the color with the hash
					// Convert the color from RGB to RRGGBB for better compatibility with IE and <font>. See #5676
					if (!parts[1])
					{
						colorName = '#' + colorName.replace( /^(.)(.)(.)$/, '$1$1$2$2$3$3' );
					}

					var colorLabel = editor.lang.colors[ colorCode ] || colorCode;
					output.push(
						'<td>' +
							'<a class="cke_colorbox" _cke_focus=1 hidefocus=true' +
								' title="', colorLabel, '"' +
								' onclick="CKEDITOR.tools.callFunction(', clickFn, ',\'', colorName, '\',\'', type, '\'); return false;"' +
								' href="javascript:void(\'', colorLabel, '\')"' +
								' role="option" aria-posinset="', ( i + 2 ), '" aria-setsize="', total, '">' +
								'<span class="cke_colorbox" style="background-color:#', colorCode, '"></span>' +
							'</a>' +
						'</td>' );
				}
				
				output.push("</tr>");
			}


			// Render the "Automatic" button.
			output.push(
				'<tr><td colspan=8 align=center>' +
					'<a class="cke_colorauto" _cke_focus=1 hidefocus=true' +
						' title="', lang.auto, '"' +
						' onclick="CKEDITOR.tools.callFunction(', clickFn, ',null,\'', type, '\');return false;"' +
						' href="javascript:void(\'', lang.auto, '\')"' +
						' role="option" aria-posinset="1" aria-setsize="', total, '">' +
						'<table role="presentation" cellspacing=0 cellpadding=0 width="100%">' +
							'<tr>' +
								'<td>' +
									'<span class="cke_colorbox" id="', colorBoxId, '"></span>' +
								'</td>' +
								'<td colspan=7 align=center>',
									lang.auto,
								'</td>' +
							'</tr>' +
						'</table>' +
					'</a>' +
				'</td></tr>' );
			
			// Render the "More Colors" button.
			if ( config.colorButton_enableMore === undefined || config.colorButton_enableMore )
			{
				output.push(
					'<tr><td colspan=8 align=center>' +
						'<a class="cke_colormore" _cke_focus=1 hidefocus=true' +
							' title="', lang.more, '"' +
							' onclick="CKEDITOR.tools.callFunction(', clickFn, ',\'?\',\'', type, '\');return false;"' +
							' href="javascript:void(\'', lang.more, '\')"',
							' role="option" aria-posinset="', total, '" aria-setsize="', total, '">',
							lang.more,
						'</a>' +
					'</td></tr>' );
			}

			output.push( '</table>' );

			return output.join( '' );
		}

		function isUnstylable( ele )
		{
			return ( ele.getAttribute( 'contentEditable' ) == 'false' ) || ele.getAttribute( 'data-nostyle' );
		}
	}
});

CKEDITOR.config.groupedColorButton_colors = 
[
	{
		title: "Default Colors",
		colors: CKEDITOR.config.colorButton_colors
	}
];