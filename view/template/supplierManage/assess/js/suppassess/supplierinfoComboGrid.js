Ext.onReady(function() {
			Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
			Ext.QuickTips.init();

			var supplierGrid = {
				xtype : 'supplierinfocombogrid',
				selectType : 'check'
			};
			new Ext.ux.combox.MyGridComboBox({
						applyTo : 'suppName',
						gridName : 'suppName',// 下拉表格显示的属性
						gridValue : 'id',
						hiddenFieldId : 'suppId',
						myGrid : supplierGrid
					})
		});