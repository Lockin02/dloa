$import("Ext.QuickTip");
$import("Ext.EventManager");
$package("Ext.ux.util");
Ext.ux.util.MyQuickTip = Ext.extend(Ext.QuickTip, {
			// private
			tagConfig : {
				namespace : "ext",
				attribute : "mytip",
				width : "qwidth",
				target : "target",
				title : "qtitle",
				hide : "hide",
				cls : "qclass",
				align : "qalign"
			},
			closable : true,
			autoHide : false,
			draggable : true,
			// private
			initComponent : function() {
				Ext.ux.util.MyQuickTip.superclass.initComponent.call(this);
			}
		});
Ext.ux.util.MyQuickTips = function() {
	var tip, locks = [];
	return {
		init : function(autoRender) {
			if (!tip) {
				if (!Ext.isReady) {
					Ext.onReady(function() {
								Ext.ux.util.MyQuickTips.init(autoRender);
							});
					return;
				}
				tip = new Ext.ux.util.MyQuickTip({
							elements : 'header,body'
						});
				if (autoRender !== false) {
					tip.render(Ext.getBody());
				}
			}
		},

		enable : function() {
			if (tip) {
				locks.pop();
				if (locks.length < 1) {
					tip.enable();
				}
			}
		},

		disable : function() {
			if (tip) {
				tip.disable();
			}
			locks.push(1);
		},

		isEnabled : function() {
			return tip !== undefined && !tip.disabled;
		},

		getQuickTip : function() {
			return tip;
		},
		register : function() {
			tip.register.apply(tip, arguments);
		},

		unregister : function() {
			tip.unregister.apply(tip, arguments);
		},

		tips : function() {
			tip.register.apply(tip, arguments);
		}
	}
}();
