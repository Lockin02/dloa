Ext.onReady(function() {
	Ext.QuickTips.init();

	// �̶�����
	// var channelData = [[1, 'ȫ��'], [2, '����'], [3, '����'], [4, '�ʼ�'], [5,
	// '����']];
	//
	// // ����ComboBox ������Դ
	// var channelStore = new Ext.data.SimpleStore({
	// fields : ['channel_id', 'channel_name'],
	// data : channelData
	// });
	var domainStore = new Ext.data.Store({
		autoLoad : true,
		proxy : new Ext.data.HttpProxy({
					url : '?model=stock_stockinfo_stockinfo&action=getStockInfoCombo' // action�ж���ķ���
				}),

		reader : new Ext.data.JsonReader({
					root : 'Result',
					totalProperty : 'totalProperty'
				}, [{
							name : 'stockId'

						}, {
							name : 'stockName'

						}])
	});

	domainStore.load();
	// alert(eval("("+domainStore+")"))
	// alert ("test -- " + JSON.stringify(domainStore.reader, null, ' '));
	// alert(domainStore.indexOf(0))
	// lert(domainStore.data.id);

	// store: new Ext.data.JsonStore({
	// proxy: new Ext.data.HttpProxy({
	// method: 'GET',
	// url: 'GoodsList.aspx'//���·��
	// }),
	// fields: [{ name: 'ID' }, { name: 'Name'}],
	// root: 'data',
	// autoLoad: true
	// })

	// ����������
	var channelCombo = new Ext.form.ComboBox({
				listeners : {
					select : function(combo, record, index) {
						// ���¼��᷵��ѡ�е����Ӧ�� store�е� recordֵ index���������кţ�����hidden��ֵ
						document.getElementById("stockName").value = record
								.get('stockName');
					},
					// expand : function() {
					// this.list.setWidth(this.width);
					// },
					render : function(cmb) {
						var store = this.getStore();
						store.on('load', function() {
									channelCombo.setValue(channelCombo
											.getValue());
								}, this); // ��ʼ����ʾ
					}

				},
				//width:2000,
				applyTo : 'stockId',
				typeAhead : true,//��ʱ��ѯ
				id : 'stockCombo',
				triggerAction : 'all',
				typeAhead : true,
				forceSelection : true,
				selectOnFocus : true,
				mode : 'local',
				emptyText : '��ѡ��',
				width : 100,
				store : domainStore,
				// value:'producttype[stockName]',
				// name : 'producttype[stockName]',
				hiddenName : 'producttype[stockId]',// ����һ��hiddeninput name
				// hiddenName
				valueField : 'stockId', // ����������ֵ
				displayField : 'stockName'// ��������ʾ����

			});

});