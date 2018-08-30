$(document).ready(function() {
	//����(��˾)
	$("#companyName").val('���Ͷ���');//���������Ĭ�����Ͷ���
	$("#companyId").bind('change', function() {
		var name=$("#companyId").find("option:selected").html();
		$("#companyName").val(name);
	})
	//��ְ����
	leaveTypeCodeArr = getData('HRLZLX');
	addDataToSelect(leaveTypeCodeArr, 'leaveTypeCode');
	
	$("#jobName").yxcombogrid_jobs({
		hiddenId : 'jobId',
		width:350
	});
	validate({
				"schemeName" : {
					required : true,
				},
				"jobName" : {
					required : true,
				},
				"companyName" : {
					required : true
				},
				"schemeTypeName" : {
					required : true
				}
			});
	/*
	 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' } });
	 */$("#itemTable").yxeditgrid({
		objName : 'salarytplate[items]',
		isAddOneRow : true,
		colModel : [ {
			name : 'salaryContent',
			tclass : 'txt',
			display : '��������',
			validation : {
				required : true
			}
		}, {
			name : 'remark',
			tclass : 'txt',
			display : '��ע˵��',
			validation : {
				required : true
			}
		} ]
	})
})