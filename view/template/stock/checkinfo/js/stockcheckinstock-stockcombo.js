Ext.onReady(function() {
			Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
			Ext.QuickTips.init();
						// �ֿ�
			var stockGrid = {
				xtype : 'stockcombogrid',
				listeners : {
					'dblclick' : function(e) { // mydelAll(); var record =
						this.getSelectionModel().getSelected();
						// $("#productNo1").val(record.get('sequence'));
					}
				}
			};

			new Ext.ux.combox.MyGridComboBox({
						applyTo : 'stockName', //
						gridName : 'stockName',// ���������ʾ������
						gridValue : 'id',
						hiddenFieldId : 'stockId',
						myGrid : stockGrid
					});
})