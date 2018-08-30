$import("Ext");
$package("Ext.ux.form");
Ext.ux.form.DateFieldPlus = Ext.extend(Object, {
			constructor : function(type) {
				// type=='day':以天为单位跳转 type="week"：以周为单位跳转

			},

			init : function(f) {
				f.afterRender = f.afterRender
						.createSequence(this.afterFieldRender);
			},

			afterFieldRender : function() {
				// if (!this.wrap) {
				// this.wrap = this.el.wrap({
				// cls : 'x-form-field-wrap'
				// });
				// }
				// this.wrap = this.el.wrap({cls: 'x-form-field-wrap
				// x-form-field-trigger-wrap'});
				// this.triggerConfig = {
				// tag : 'span',
				// cls : 'x-form-twin-triggers',
				// cn : [{
				// tag : "img",
				// src : Ext.BLANK_IMAGE_URL,
				// cls : "x-form-trigger " + this.trigger1Class
				// }, {
				// tag : "img",
				// src : Ext.BLANK_IMAGE_URL,
				// cls : "x-form-trigger " + this.trigger2Class
				// }]
				// };
				// this.wrap['createChild']({
				// html:'aa'
				// });

				// this.wrap.setWidth(100);

//				this.trigger = this.wrap.createChild({
//							tag : "img",
//							src : Ext.BLANK_IMAGE_URL,
//							cls : "x-tbar-page-prev"
//						});
//				if (!this.width) {
//					this.wrap.setWidth(this.el.getWidth()
//							+ this.trigger.getWidth());
//				}
//				this.resizeEl = this.positionEl = this.wrap;
				//this.updateEditState();
			}
		});
