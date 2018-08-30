Ext.onReady(function() {
	Ext.QuickTips.init();

	 //仓库用途数据
	 var stockuseData = [["设备借用", '设备借用'], ['设备赠送', '设备赠送'], ['设备归还', '设备归还'], ['设备维修', '设备维修'], ['合同销售',
	 '合同销售'],['采购入库','采购入库']];

	 // 定义ComboBox 的数据源
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
								}, this); // 初始化显示
					}

				},
				applyTo : 'stockUse',
				typeAhead : true,//延时查询
				id : 'stockuseCombo',
				triggerAction : 'all',
				typeAhead : true,
				forceSelection : true,
				selectOnFocus : true,
				mode : 'local',
				emptyText : '请选择',
				width : 100,
				store : stockuseStore,
				hiddenName : 'stockinfo[stockUse]',// 创建一个hiddeninput name
				valueField : 'useId', // 下拉框具体的值
				displayField : 'useName'// 下拉框显示内容

			});

})