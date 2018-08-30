// 业务类型数据
$(function(){
	$("#objCode").yxcombogrid_other({
		hiddenId : 'objId',
		height : 300,
		width : 700,
		searchName : 'docCode',
		gridOptions : {
			param : {'ExaStatus' : '完成','fundType' : 'KXXZA'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#province").val(data.proName);
					$("#incomeUnitName").val(data.signCompanyName);
					$("#incomeUnitId").val(data.signCompanyId);
					$("#contractUnitName").val(data.signCompanyName);
					$("#contractUnitId").val(data.signCompanyId);
					$("#rObjCode").val(data.objCode);
					$("#objType").val('KPRK-09');
				}
			}
		}
	});
	
	//归属公司
	$("#businessBelongName").yxcombogrid_branch('remove').yxcombogrid_branch({
		hiddenId : 'businessBelong',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					//初始化树结构
					initTree();
					//重置责任范围
					reloadManager();
				}
			}
		}
	});
});


/**
 * 检查分配金额与到款金额规则
 */
function toSubmit() {
	if($("#incomeUnitName").val() == ''){
		alert('请填写客户名称');
		return false;
	}

	if($("#incomeMoney").val() == '' || $("#incomeMoney").val()*1 == 0){
		alert('请输入单据金额');
		return false;
	}
	
	if($("#businessBelongName").val() == ''){
		alert('请输入归属公司');
		return false;
	}
}
