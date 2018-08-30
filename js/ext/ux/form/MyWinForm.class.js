/*******************************************************************************
 * ����ʽ������Ҫ�õ�Ext.ux.form.MyWinForm ���ݴ������������㲻ͬ
 */
$import("Ext.Window");
$package("Ext.ux.form");
Ext.namespace("Ext.ux.form");
Ext.ux.form.MyWinForm = Ext.extend(Ext.Window, {
			/**
			 * ���۵�
			 * 
			 * @type Boolean
			 */
			collapsible : true,
			/**
			 * �Ƿ�������
			 * @type Boolean
			 */
			maximizable : true,
			/**
			 * �Ƿ�������Ч��
			 * @type Boolean
			 */
			animCollapse : true,
			/**
			 * ����ָ��div id
			 * @type String
			 */
			animateTarget : 'south',
			/**
			 * windows�����ܷ�༭
			 * @type Boolean
			 */
			modal : false, 
			/**
			 * �Ƿ���ʾ�߿�
			 * @type Boolean
			 */
			border : false,
			/**
			 * hide���ش��ڣ�colse�ݻٶ���
			 * @type String
			 */
			closeAction : 'close',
			/**
			 * �ݻ�δ�������
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
				beforedestroy : function(t) { // �ݻ�δ�������
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
