
$(document).ready(function() {
	var showType = $("#showType").val();
	if(showType == '������ʾ'){
		$("#typePart").show();
	}
	else{
		$("#typePart").hide();
	}
	$("#categoryItem").yxeditgrid( {
		objName : 'category[items]',
		url : '?model=yxlicense_license_categoryitem&action=listJson',
		type : 'view',
		title : '�ӱ���Ϣ',
		param : {
			categoryId : $("#id").val(),
			'dir' : 'ASC'
		},
		colModel : [
			{
			name : 'itemName',
			tclass : 'txt',
			display : '��ϸ����',
			sortable : true,
			validation : {
				required : true
			},
			process : function(v, row) {
				if(showType == '����ʾ'){
				   return '<a href="javascript:void(0)" onclick="showOpenWin(\'?model=yxlicense_license_categoryform&action=page&id='
						+ row.id
						+ '\',1,650,1000,'+$("#id").val()+')">'
						+ v
						+ '</a>';
				}
				else{
					return v;
				}
			}

		}, {
			name : 'groupName',
			display : '������',
			tclass : 'txt',
			sortable : true
		}, {
			name : 'appendShow',
			display : '��չ��ʾ',
			tclass : 'txt',
			sortable : true
		}]
	});
});