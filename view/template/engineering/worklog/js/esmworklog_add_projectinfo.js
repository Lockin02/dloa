Ext.onReady(function() {
			Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
			Ext.QuickTips.init();

			var taskGrid = {
				xtype : 'projectinfocombogrid'
			};
			new Ext.ux.combox.MyGridComboBox({
						applyTo : 'projectName',
						gridName : 'projectName',// 下拉表格显示的属性
						gridValue : 'id',
						hiddenFieldId : 'projectId',
						myGrid : taskGrid
					})
		});