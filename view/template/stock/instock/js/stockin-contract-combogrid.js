$(function() {
			// $("#contractCode").yxcombogrid_order({
			// hiddenId : 'contractId',
			// nameCol : 'orderCode',
			// gridOptions : {
			// param : {
			// ExaStatus : '完成'
			// },
			// showcheckbox : false,
			// event : {
			// 'row_dblclick' : function(e, row, data) {
			// $("#contractName").val(data.orderName);
			// if (data.orderCode == "") {
			// $("#contractCode")
			// .val(data.orderTempCode);
			// }
			// }
			// }
			// }
			// });
			$("#contractCode").yxcombogrid_allcontract({
						hiddenId : 'contractId',
						nameCol : 'contractCode',
						valueCol : 'id',
						isDown : false,
						gridOptions : {
							param : {
								ExaStatus : '完成'
							},
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#contractName").val(data.contractName);
									$("#contractType").val(data.contractType);
									$("#contractObjCode").val(data.objCode);

								}
							}
						}
					});
		});