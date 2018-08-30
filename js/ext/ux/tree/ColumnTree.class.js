/*
 * Ext JS Library 2.2.1 Copyright(c) 2006-2009, Ext JS, LLC. licensing@extjs.com
 * 
 * http://extjs.com/license
 */
$import("Ext.tree.TreePanel");
$package("Ext.ux.tree");
Ext.ux.tree.ColumnTree = Ext.extend(Ext.tree.TreePanel, {
			lines : false,
			borderWidth : Ext.isBorderBox ? 0 : 2, // the combined left/right
													// border for each cell
			cls : 'x-column-tree',

			onRender : function() {
				Ext.ux.tree.ColumnTree.superclass.onRender.apply(this, arguments);
				this.headers = this.body.createChild({
							cls : 'x-tree-headers'
						}, this.innerCt.dom);

				var cols = this.columns, c;
				var totalWidth = 0;

				for (var i = 0, len = cols.length; i < len; i++) {
					c = cols[i];
					totalWidth += c.width;
					this.headers.createChild({
								cls : 'x-tree-hd '
										+ (c.cls ? c.cls + '-hd' : ''),
								cn : {
									cls : 'x-tree-hd-text',
									html : c.header
								},
								style : 'width:' + (c.width - this.borderWidth)
										+ 'px;'
							});
				}
				this.headers.createChild({
							cls : 'x-clear'
						});
				// prevent floats from wrapping when clipped
				this.headers.setWidth(totalWidth);
				this.innerCt.setWidth(totalWidth);
			}
		});
