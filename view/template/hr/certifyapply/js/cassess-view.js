$(document).ready(function() {
	//ģ��
	$("#cdetail").yxeditgrid({
		url : '?model=hr_certifyapply_cdetail&action=listJson',
		param : {"assessId" : $("#id").val()},
		tableClass : 'form_in_table',
		type : 'view',
		title : '��ְ�ʸ�ȼ���׼',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'ģ��Id',
			name : 'modeId',
			type : 'hidden'
		}, {
			display : '��Ϊģ��id',
			name : 'moduleId',
			type : 'hidden'
		}, {
			display : '��Ϊģ��',
			name : 'moduleName'
		}, {
			display : '��ΪҪ��id',
			name : 'detailId',
			type : 'hidden'
		}, {
			display : '��ΪҪ��',
			name : 'detailName'
		}, {
			display : 'Ȩ��(%)',
			name : 'weights'
		}, {
			display : '��ְ��׼',
			name : 'standard'
		}, {
			display : '��Ҫ�ṩ�����۲���',
			name : 'needMaterial'
//			,
//			process : function(html, rowData, trObj , g ,inputObj){
//				$.showDump(inputObj.html());
//			}
		}, {
			display : '��֤����˵��',
			name : 'content'
		}, {
			display : '������ظ���',
			name : 'file',
			serviceType:'cdetail',
			type:'file'
		}]
	})

})