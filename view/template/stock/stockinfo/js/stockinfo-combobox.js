Ext.onReady(function() {
	Ext.QuickTips.init();

	// 固定数据
	// var channelData = [[1, '全部'], [2, '短信'], [3, '传真'], [4, '邮件'], [5,
	// '语音']];
	//
	// // 定义ComboBox 的数据源
	// var channelStore = new Ext.data.SimpleStore({
	// fields : ['channel_id', 'channel_name'],
	// data : channelData
	// });
	var domainStore = new Ext.data.Store({
		autoLoad : true,
		proxy : new Ext.data.HttpProxy({
					url : '?model=stock_stockinfo_stockinfo&action=getStockInfoCombo' // action中定义的方法
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
	// url: 'GoodsList.aspx'//相对路径
	// }),
	// fields: [{ name: 'ID' }, { name: 'Name'}],
	// root: 'data',
	// autoLoad: true
	// })

	// 定义下拉框
	var channelCombo = new Ext.form.ComboBox({
				listeners : {
					select : function(combo, record, index) {
						// 该事件会返回选中的项对应在 store中的 record值 index参数是排列号，更改hidden的值
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
								}, this); // 初始化显示
					}

				},
				//width:2000,
				applyTo : 'stockId',
				typeAhead : true,//延时查询
				id : 'stockCombo',
				triggerAction : 'all',
				typeAhead : true,
				forceSelection : true,
				selectOnFocus : true,
				mode : 'local',
				emptyText : '请选择',
				width : 100,
				store : domainStore,
				// value:'producttype[stockName]',
				// name : 'producttype[stockName]',
				hiddenName : 'producttype[stockId]',// 创建一个hiddeninput name
				// hiddenName
				valueField : 'stockId', // 下拉框具体的值
				displayField : 'stockName'// 下拉框显示内容

			});

});