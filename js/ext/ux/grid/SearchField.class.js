/*
 * 搜索控件
 */
$import("Ext.form.TwinTriggerField");
$package("Ext.ux.grid");
Ext.ux.grid.SearchField = function(config) {
	// config params
	var cfg = {
		validationEvent : false,
		validateOnBlur : false,
		trigger1Class : 'x-form-clear-trigger',
		trigger2Class : 'x-form-search-trigger',
		hasSearch : true,
		paramName : 'search',
		width : 180,
		hideTrigger1 : false
	};
	config = Ext.applyIf(config || {}, cfg);
	
	// 公布属性..
	this.onSearchClick = config.onSearchClick || Ext.emptyFn;
	this.onSearchCleanClick = config.onSearchCleanClick || Ext.emptyFn;

	// call super constructor
	Ext.ux.grid.SearchField.superclass.constructor.call(this, config);

	// add event..
	this.on('specialkey', function(f, e) {
		if (e.getKey() == e.ENTER) {
			this.onTrigger2Click();
		}
	}, this);
}
Ext.extend(Ext.ux.grid.SearchField, Ext.form.TwinTriggerField, {
	onTrigger1Click : function() {
		//if (this.hasSearch) {
			this.el.dom.value = '';
			this.hasSearch = false;
			//this.triggers[0].hide();

			this.onSearchCleanClick();
		//}
	},
	onTrigger2Click : function() {
		
		var v = this.getRawValue();
		if (v.length < 1) {
			this.onTrigger1Click();
			return;
		}
		this.hasSearch = true;
		this.triggers[0].show();

		this.onSearchClick(v);
	}
});