$(function() {
	$("#supplierName").yxcombogrid_supplier({
				hiddenId : 'supplierId',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#suppAddress").val(data.address);
							$("#suppTel").val(data.plane);
							$('#suppAccount1').yxcombogrid_suppAccount("remove");
							$("#suppAccount1").val("");
							$("#suppBankName").val("");		//�����е������ֵ�ת�������ĺ��滻��Ӧ��ֵ������ҳ����ʾ
							$("#suppBank").val("");
							//�����Ӧ�̴�������Ϣ����Ĭ�ϴ�����һ����Ϣ
							$.post(
									"?model=supplierManage_formal_bankinfo&action=getBankInfo",
									{
										suppId : data.id
									}, function(bank) {
										if(bank){
        									var o = eval("(" + bank + ")");
											$("#suppAccount1").val(o.accountNum);
											$("#suppBankName").val(o.bankName);
										}
									});
							$('#suppAccount1').yxcombogrid_suppAccount({
								isFocusoutCheck:false,
								gridOptions : {
									reload : true,
									showcheckbox : false,
									// ���ݹ�Ӧ��ID��ѡ�еĿ������У����˳���Ӧ�������ʺ�
									param : {
										suppId : $("#supplierId").val()
									},
									event : {
										'row_dblclick' : function(e, row, data) {
											var getGrid = function() {
												return $("#suppAccount1").yxcombogrid_suppAccount("getGrid");
											}
											var getGridOptions = function() {
												return $("#suppAccount1").yxcombogrid_suppAccount("getGridOptions");
											}
											if (!$('#supplierId').val()) {
											} else {
												if (getGrid().reload) {
													getGridOptions().param = {
														suppId : $('#supplierId').val()
													};
													getGrid().reload();
												} else {
													getGridOptions().param = {
														suppId : $('#supplierId').val()
													}
												}
											}
											$("#suppAccount1").val(data.accountNum);
											$("#suppBankName").val(data.bankName);
											$("#suppBank").val(data.depositbank);				//���е������ֵ���룬���������ڱ�������ʱд�����ݿ�
										}
									}
								}
							});
						}
					}
				}
			});
});