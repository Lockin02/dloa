/*******************************************************************************
 * 窗口式表单，需要用到Ext.ux.form.MyWinForm 根据传入表格或者树结点不同
 */
$import("Ext.Window");
$package("Ext.ux.form");
Ext.namespace("Ext.ux.form");
Ext.ux.form.MyWinForm = Ext.extend(Ext.Window, {
			/**
			 * 可折叠
			 * 
			 * @type Boolean
			 */
			collapsible : true,
			/**
			 * 是否可以最大化
			 * @type Boolean
			 */
			maximizable : true,
			/**
			 * 是否开启动画效果
			 * @type Boolean
			 */
			animCollapse : true,
			/**
			 * 动画指向div id
			 * @type String
			 */
			animateTarget : 'south',
			/**
			 * windows背景能否编辑
			 * @type Boolean
			 */
			modal : false, 
			/**
			 * 是否显示边框
			 * @type Boolean
			 */
			border : false,
			/**
			 * hide隐藏窗口，colse摧毁对象
			 * @type String
			 */
			closeAction : 'close',
			/**
			 * 摧毁未清除对象
			 * @type 
			 */
			destroyArry : [],
			// autoHeight : false,
			listeners : {
				"maximize" : function(t) {
					var vs = t.container.getViewSize();
					this.items.get(0).setHeight(vs.height - 30);
					// this.items.setWidth(vs.width);
				},
				"restore" : function(t) {
					// var vs = t.container.getViewSize();
					this.items.get(0).setHeight(t.height - 30);
					this.items.get(0).setWidth(t.width);
				},
				"resize" : function(t) {
					// var vs = t.container.getViewSize();
					// this.myForm.setHeight(vs.height - 30);
					// this.myForm.setWidth(vs.width);
				},
				beforedestroy : function(t) { // 摧毁未清除对象
					for (var i = 0, l = this.destroyArry.length; i < l; i++) {
						Ext.ComponentMgr.unregister(this.destroyArry[i]);
					}
					this.destroyArry = [];
				}
			},
			initComponent : function() {
				//this.init();
				Ext.ux.form.MyWinForm.superclass.initComponent.call(this);

			}
		});
