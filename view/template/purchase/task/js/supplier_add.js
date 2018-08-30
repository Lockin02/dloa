Ext.onReady(function() {
			Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
			Ext.QuickTips.init();

			var contractGrid = {
				xtype : 'suppliercombogrid',
				listeners : {
					'dblclick' : function(e) {
						var record = this.getSelectionModel().getSelected();
						$("#suppTel").val(record.get('Tell'));
						$("#suppBank").val(record.get('AccBank'));
						$("#suppAccount").val(record.get('Acc'));
						$("#billingType").val(record.get('BillType'));
						$("#suppAddress").val(record.get('Address'));
					}
				}
			};

			new Ext.ux.combox.MyGridComboBox({
						applyTo : 'SupplierName',
						// renderTo : 'contractName',
						gridName : 'Name',// 下拉表格显示的属性
						gridValue : 'ID',
						hiddenFieldId : 'SupplierId',
						myGrid : contractGrid
					})
		});