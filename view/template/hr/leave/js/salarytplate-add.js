$(document).ready(function() {
	//编制(公司)
	$("#companyName").val('世纪鼎利');//如果不改则默认世纪鼎利
	$("#companyId").bind('change', function() {
		var name=$("#companyId").find("option:selected").html();
		$("#companyName").val(name);
	})
	//离职类型
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
			display : '工资内容',
			validation : {
				required : true
			}
		}, {
			name : 'remark',
			tclass : 'txt',
			display : '备注说明',
			validation : {
				required : true
			}
		} ]
	})
})