Ext.onReady(function() {
	Ext.QuickTips.init();

	 //�ֿ���;����
	 var stockuseData = [["�豸����", '�豸����'], ['�豸����', '�豸����'], ['�豸�黹', '�豸�黹'], ['�豸ά��', '�豸ά��'], ['��ͬ����',
	 '��ͬ����'],['�ɹ����','�ɹ����']];

	 // ����ComboBox ������Դ
	 var stockuseStore = new Ext.data.SimpleStore({
	 fields : ['useId', 'useName'],
	 data : stockuseData
	 });

	 var stockuseCombo = new Ext.form.ComboBox({
				listeners : {
					render : function(cmb) {
						var store = this.getStore();
						store.on('load', function() {
									stockuseCombo.setValue(stockuseCombo
											.getValue());
								}, this); // ��ʼ����ʾ
					}

				},
				applyTo : 'stockUse',
				typeAhead : true,//��ʱ��ѯ
				id : 'stockuseCombo',
				triggerAction : 'all',
				typeAhead : true,
				forceSelection : true,
				selectOnFocus : true,
				mode : 'local',
				emptyText : '��ѡ��',
				width : 100,
				store : stockuseStore,
				hiddenName : 'stockinfo[stockUse]',// ����һ��hiddeninput name
				valueField : 'useId', // ����������ֵ
				displayField : 'useName'// ��������ʾ����

			});

})