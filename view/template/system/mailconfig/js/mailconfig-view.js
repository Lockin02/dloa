$(document).ready(function() {
	//文本域高度自适应
	$("textarea").each(function(){
		$(this).height($(this)[0].scrollHeight);
	});

	//渲染从表
	$("#mainconfigitem").yxeditgrid({
		objName : 'mailconfig[mainconfigitem]',
		url : '?model=system_mailconfig_mainconfigitem&action=listJson',
		param : { "mainId" : $("#id").val() ,'dir' : 'ASC' ,'sort' : 'orderNum'},
		title : '从表渲染',
		tableClass : 'form_in_table',
		isAddOneRow : false,
		type : 'view',
		colModel : [{
			display : '字段名称',
			name : 'fieldName',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '字段编码',
			name : 'fieldCode',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '显示类型',
			name : 'showType',
			tclass : 'txtmiddle'
		}, {
			display : '排序号',
			name : 'orderNum',
			tclass : 'txtmiddle'
		}]
	});
});