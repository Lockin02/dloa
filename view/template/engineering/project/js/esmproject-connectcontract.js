//����֤
function checkform(){
	if($("#workRate").val()*1 == 0){
		alert('��������ռ�Ȳ���Ϊ0');
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
			param : {'ExaStatusArr' : '���,���������','isTemp' : 0 ,'contractTypeArr' : $("#contractType").val()},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					if(data.objCode == ""){
						alert('��ͬ��ҵ����Ϊ�գ�����ϵ����Ա�ȶԺ�ͬ��ҵ���Ž��и��£�');
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

					//��ȡ���õĹ���ռ��
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
	 * ��֤��Ϣ
	 */
	validate({
		"workRate" : {
			required : true,
			custom : ['percentage']
		}
	});
});

//����֤
function checkForm(){
	if($("#contractCode").val() &&��$("#contractTempCode").val()){
		alert('��ѡ��һ����ͬ');
		return false;
	}

	if($("#workRate").val()*1 >��$("#workRateHidden").val()*1){
		alert('����ռ�Ȳ��ܳ���ʣ�๤��ռ��');
		return false;
	}
}
