Ext.onReady(function() {
			Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
			Ext.QuickTips.init();

			var taskGrid = {
				xtype : 'planinfocombogrid'
			};
			new Ext.ux.combox.MyGridComboBox({
						applyTo : 'planName',
						gridName : 'planName',// ���������ʾ������
						gridValue : 'id',
						hiddenFieldId : 'planId',
						myGrid : planGrid
					})
		});