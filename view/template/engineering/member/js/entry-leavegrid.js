$(document).ready(function() {
	$("#esmentryGrid").yxeditgrid({
		url : '?model=engineering_member_esmmember&action=listJson',
		param : {
			ids :$("#memberIds").val()
		},
		objName : "esmentry",
		isAddAndDel : false,
		colModel : [{
			display : '��Ŀid',
			name : 'projectId',
			sortable : true,
			type : "hidden"
		},{
			display : '��Ա����',
			name : 'memberName',
			sortable : true,
			tclass :'readOnlyTxtNormal ',
			readonly : true,
			width : 120
		},{
			display : '��ԱID',
			name : 'memberId',
			sortable : true,
			type : "hidden"
		},{
			display : '����',
			name : 'personLevel',
			sortable : true,
			tclass :'readOnlyTxtNormal ',
			readonly : true,
			width : 80
		},{
			display : '������Ŀ',
			name : 'beginDate',
			sortable : true,
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '�뿪��Ŀ',
			name : 'endDate',
			sortable : true,
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '��ע',
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
				alert("�뿪���ڴ��ڼ�����Ŀ����");
				return false;
			}		
		}
		return true;
}