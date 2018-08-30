$(document).ready(function() {

	//µ¥Ñ¡ÇøÓò
		$("#outContractCode").yxcombogrid_outsourccontract({
			hiddenId : 'outContractId',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e,row,data) {
	                                    $("#projectId").val(data.projectId);
	                                    $("#projectCode").val(data.projectCode);
	                                    $("#projectName").val(data.projectName);
	                                    $("#outsourcingName").val(data.outsourcingName);
	                                    $("#outsourcing").val(data.outsourcing);
	                                    $("#beginDate").val(data.beginDate);
	                                    $("#endDate").val(data.endDate);
	                                    $("#totalMoney").val(data.orderMoney);
					}
				}
			}
		});
	validate({
				"outContractCode" : {
					required : true
				},
				"personNum" : {
					required : true
				}
			});
  })