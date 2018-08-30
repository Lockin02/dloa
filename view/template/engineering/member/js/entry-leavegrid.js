$(document).ready(function() {
	$("#esmentryGrid").yxeditgrid({
		url : '?model=engineering_member_esmmember&action=listJson',
		param : {
			ids :$("#memberIds").val()
		},
		objName : "esmentry",
		isAddAndDel : false,
		colModel : [{
			display : '项目id',
			name : 'projectId',
			sortable : true,
			type : "hidden"
		},{
			display : '人员名称',
			name : 'memberName',
			sortable : true,
			tclass :'readOnlyTxtNormal ',
			readonly : true,
			width : 120
		},{
			display : '人员ID',
			name : 'memberId',
			sortable : true,
			type : "hidden"
		},{
			display : '级别',
			name : 'personLevel',
			sortable : true,
			tclass :'readOnlyTxtNormal ',
			readonly : true,
			width : 80
		},{
			display : '加入项目',
			name : 'beginDate',
			sortable : true,
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '离开项目',
			name : 'endDate',
			sortable : true,
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '备注',
			name : 'remark',
			sortable : true,
			tclass : 'txtlong'
		}]
	})
	
	
	validate({
		
	});
});

function checkForm (){
	var length = $("[id^='esmentryGrid_cmp_memberName']").length;
		for(var i = 0;i<length;i++){
			if($("#esmentryGrid_cmp_endDate"+i).val() < $("#esmentryGrid_cmp_beginDate"+i).val()){
				alert("离开日期大于加入项目日期");
				return false;
			}		
		}
		return true;
}