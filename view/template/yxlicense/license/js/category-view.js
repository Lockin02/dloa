
$(document).ready(function() {
	var showType = $("#showType").val();
	if(showType == '分组显示'){
		$("#typePart").show();
	}
	else{
		$("#typePart").hide();
	}
	$("#categoryItem").yxeditgrid( {
		objName : 'category[items]',
		url : '?model=yxlicense_license_categoryitem&action=listJson',
		type : 'view',
		title : '从表信息',
		param : {
			categoryId : $("#id").val(),
			'dir' : 'ASC'
		},
		colModel : [
			{
			name : 'itemName',
			tclass : 'txt',
			display : '明细名称',
			sortable : true,
			validation : {
				required : true
			},
			process : function(v, row) {
				if(showType == '表单显示'){
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
			display : '分组名',
			tclass : 'txt',
			sortable : true
		}, {
			name : 'appendShow',
			display : '扩展显示',
			tclass : 'txt',
			sortable : true
		}]
	});
});