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
							$("#suppBankName").val("");		//将银行的数据字典转换成中文后替换相应的值，并在页面显示
							$("#suppBank").val("");
							//如果供应商存银行信息，则默认带出第一个信息
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
									// 根据供应商ID及选中的开户银行，过滤出对应的银行帐号
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
											$("#suppBank").val(data.depositbank);				//银行的数据字典编码，隐藏域，用于保存数据时写入数据库
										}
									}
								}
							});
						}
					}
				}
			});
});