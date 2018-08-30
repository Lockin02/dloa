$import("Ext");
$package("Ext.ux.form");
Ext.ux.form.FieldHelp = Ext.extend(Object, {
			constructor : function(t, align) {
				this.helpText = t;
				this.align = align;
			},

			init : function(f) {
				f.helpAlign = this.align;
				f.helpText = this.helpText;
				f.afterRender = f.afterRender
						.createSequence(this.afterFieldRender);
			},

			afterFieldRender : function() {
				if (!this.wrap) {
					this.wrap = this.el.wrap({
								cls : 'x-form-field-wrap'
							});
				}
				this.wrap[this.helpAlign == 'top'
						? 'insertFirst'
						: 'createChild']({
							cls : 'x-form-helptext',
							html : '<font color="#0060c5">' + this.helpText
									+ '</font>'
						});
			}
		});
