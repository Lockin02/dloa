$(document).ready(function() {
	// ģ��
	$("#cdetail").yxeditgrid({
		url : '?model=hr_certifyapply_cdetail&action=listJson',
		param : {
			"assessId" : $("#id").val()
		},
		objName : 'cassess[cdetail]',
		tableClass : 'form_in_table',
		title : '��ְ�ʸ�ȼ���׼',
		isAddAndDel : false,
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
				name : 'moduleName',
				type : 'statictext'
			}, {
				display : '��ΪҪ��id',
				name : 'detailId',
				type : 'hidden'
			}, {
				display : '��ΪҪ��',
				name : 'detailName',
				type : 'statictext'
			}, {
				display : 'Ȩ��',
				name : 'weights',
				type : 'statictext',
				process : function(v) {
					return v + " %";
				}
			}, {
				display : '��ְ��׼',
				name : 'standard',
				type : 'statictext'
			}, {
				display : '��Ҫ�ṩ�����۲���',
				name : 'needMaterial',
				type : 'statictext'
			}, {
				display : '��֤����˵��',
				name : 'content',
				type : 'textarea',
				cols : '50',
				rows : '5'

			}, {
				display : '������ظ���',
				name : 'file',
				type : 'file',
				serviceType:'cdetail'
				// html : "<a href='#' onclick=''>�����ϴ�</a>"
			}
		]
	})
})

//�༭ҳ - �ύ����
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=hr_certifyapply_cassess&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=hr_certifyapply_cassess&action=edit";
	}
}
