//表单验证
function checkform(){
	if($("#workRate").val()*1 == 0){
		alert('关联工作占比不能为0');
		return false;
	}
}


$(document).ready(function(){

	$("#contractCode").yxcombogrid_allcontract({
		hiddenId : "contractId",
		valueCol : 'orgId',
		height : 300,
		width : 700,
		isDown : false,
		gridOptions : {
			param : {'ExaStatusArr' : '完成,变更审批中','isTemp' : 0 ,'contractTypeArr' : $("#contractType").val()},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					if(data.objCode == ""){
						alert('合同的业务编号为空，请联系管理员先对合同的业务编号进行更新！');
						$("#rObjCode").val('');
						$("#customerName").val('');
						$("#customerId").val('');
						$("#contractId").val('');
						$("#contractCode").val('');
						return false;
					}
					$("#rObjCode").val(data.objCode);
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#customerType").val(data.customerType);
					$("#contractType").val(data.tablename);

					//获取可用的工作占比
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_project_esmproject&action=getWorkrateCanUse",
					    data: {
			    			"contractId" : data.orgid,
			    			"contractType" : data.tablename
		    			},
					    async: false,
					    success: function(data){
					   		$("#workRate").val(data);
					   		$("#workRateHidden").val(data);
						}
					});
				}
			}
		}
	});

	/**
	 * 验证信息
	 */
	validate({
		"workRate" : {
			required : true,
			custom : ['percentage']
		}
	});
});

//表单验证
function checkForm(){
	if($("#contractCode").val() &&　$("#contractTempCode").val()){
		alert('请选择一个合同');
		return false;
	}

	if($("#workRate").val()*1 >　$("#workRateHidden").val()*1){
		alert('工作占比不能超过剩余工作占比');
		return false;
	}
}
