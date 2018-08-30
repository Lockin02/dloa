/**
 * 下拉多选控件
 */
if ('function' !== typeof RegExp.escape) {
	RegExp.escape = function(s) {
		if ('string' !== typeof s) {
			return s;
		}
		// Note: if pasting from forum, precede ]/\ with backslash manually
		return s.replace(/([.*+?^=!:${}()|[\]\/\\])/g, '\\$1');
	}; // eo function escape
}
Ext.ns('Ext.ux.form');
Ext.ux.form.MultiSelect = Ext.extend(Ext.form.ComboBox, {
	checkField : 'checked',
	separator : ',',
	initComponent : function() {

		// template with checkbox
		if (!this.tpl) {
			this.tpl = '<tpl for=".">' + '<div ext:qtip="{' + this.tips
					+ '}" class="x-combo-list-item">' + '<img src="'
					+ Ext.BLANK_IMAGE_URL + '" '
					+ 'class="ux-MultiSelect-icon ux-MultiSelect-icon-'
					+ '{[values.' + this.checkField + '?"checked":"unchecked"'
					+ ']}">' + '{[values.' + this.displayField + ']}'
					+ '</div>' + '</tpl>';
		}

		// call parent
		Ext.ux.form.MultiSelect.superclass.initComponent.apply(this, arguments);

		// install internal event handlers
		this.on({
					scope : this,
					beforequery : this.onBeforeQuery,
					blur : this.onRealBlur
				});

		// remove selection from input field
		this.onLoad = this.onLoad.createSequence(function() {
					if (this.el) {
						var v = this.el.dom.value;
						this.el.dom.value = '';
						this.el.dom.value = v;
					}
				});

	},
	initEvents : function() {
		Ext.ux.form.MultiSelect.superclass.initEvents.apply(this, arguments);

		// disable default tab handling - does no good
		this.keyNav.tab = false;

	},
	clearValue : function() {
		this.value = '';
		this.setRawValue(this.value);
		this.store.clearFilter();
		this.store.each(function(r) {
					r.set(this.checkField, false);
				}, this);
		if (this.hiddenFieldId) {
			document.getElementById(this.hiddenFieldId).value = '';
		}
		this.applyEmptyText();
	},
	getCheckedDisplay : function() {
		var re = new RegExp(this.separator, "g");
		return this.getCheckedValue(this.displayField).replace(re,
				this.separator + ' ');
	},
	getCheckedValue : function(field) {
		field = field || this.valueField;
		var c = [];

		// store may be filtered so get all records
		var snapshot = this.store.snapshot || this.store.data;

		snapshot.each(function(r) {
					if (r.get(this.checkField)) {
						c.push(r.get(field));
					}
				}, this);

		return c.join(this.separator);
	},
	onBeforeQuery : function(qe) {
		qe.query = qe.query.replace(new RegExp(RegExp.escape(this
						.getCheckedDisplay())
						+ '[ ' + this.separator + ']*'), '');
	},
	onRealBlur : function() {
		this.list.hide();
		var rv = this.getRawValue();
		var rva = rv.split(new RegExp(RegExp.escape(this.separator) + ' *'));
		var va = [];
		var snapshot = this.store.snapshot || this.store.data;

		// iterate through raw values and records and check/uncheck
		// items
		Ext.each(rva, function(v) {
					snapshot.each(function(r) {
								if (v === r.get(this.displayField)) {
									va.push(r.get(this.valueField));
								}
							}, this);
				}, this);
		this.setValue(va.join(this.separator));
		this.store.clearFilter();
	},
	onSelect : function(record, index) {
		if (this.fireEvent('beforeselect', this, record, index) !== false) {

			// toggle checked field
			record.set(this.checkField, !record.get(this.checkField));

			// display full list
			if (this.store.isFiltered()) {
				this.doQuery(this.allQuery);
			}
			// set (update) value and fire event
			this.setValue(this.getCheckedValue());
			this.fireEvent('select', this, record, index);
		}
	},
	setValue : function(v) {
		if (v) {
			v = '' + v;
			if (this.valueField) {
				this.store.clearFilter();
				this.store.each(function(r) {
							var checked = !(!v.match('(^|' + this.separator
									+ ')'
									+ RegExp.escape(r.get(this.valueField))
									+ '(' + this.separator + '|$)'));
							r.set(this.checkField, checked);
						}, this);
				this.value = this.getCheckedValue();
				this.setRawValue(this.getCheckedDisplay());
				// // tip
				// if (!Ext.isEmpty(this.getRawValue())) {
				// if (!this.toolTip || !this.toolTip.body) {
				// this.toolTip = Class.forName("Ext.ToolTip")
				// .newInstance({
				// target : this.id,
				// html : this.getRawValue()
				// });
				// } else {
				// this.toolTip.body.update(this.getRawValue());
				// }
				// }
				if (this.hiddenFieldId) {
					document.getElementById(this.hiddenFieldId).value = this.value;
				}
			} else {
				this.value = v;
				this.setRawValue(v);
				if (this.hiddenFieldId) {
					document.getElementById(this.hiddenFieldId).value = v;
				}
			}
			if (this.el) {
				this.el.removeClass(this.emptyClass);
			}
		} else {
			// this.clearValue();
		}
	},
	selectAll : function() {
		this.store.each(function(record) {
					// toggle checked field
					record.set(this.checkField, true);
				}, this);

		// display full list
		this.doQuery(this.allQuery);
		this.setValue(this.getCheckedValue());
	},
	deselectAll : function() {
		this.clearValue();
	},
	beforeBlur : function() {
//		var val = this.getRawValue(), rec = this.findRecord(this.displayField,
//				val);
//		if (!rec && this.forceSelection) {
//			if (val.length > 0 && val != this.emptyText) {
//				this.el.dom.value = Ext.isEmpty(this.lastSelectionText)
//						? ''
//						: this.lastSelectionText;
//				this.applyEmptyText();
//			} else {
//				this.clearValue();
//			}
//		} else {
//			if (rec) {
//				val = rec.get(this.valueField || this.displayField);
//			}
//			this.setValue(val);
//		}
	}

}); // eo extend

// register xtype
Ext.reg('multiSelect', Ext.ux.form.MultiSelect);
