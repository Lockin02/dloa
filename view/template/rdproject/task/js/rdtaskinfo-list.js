Ext.onReady(function() {
			Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
			Ext.QuickTips.init();

			var taskGrid = {
				xtype : 'taskinfocombogrid'
			};
			new Ext.ux.combox.MyGridComboBox({
						applyTo : 'preTask',
						gridName : 'name',// ���������ʾ������
						gridValue : 'id',
						hiddenFieldId : 'preTaskId',
						myGrid : taskGrid
					})
			new Ext.ux.combox.MyGridComboBox({
						applyTo : 'belongTask',
						gridName : 'name',// ���������ʾ������
						gridValue : 'id',
						hiddenFieldId : 'belongTaskId',
						myGrid : taskGrid
					})
		});