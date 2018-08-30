$(document).ready(function() {
	// 模板
	$("#cdetail").yxeditgrid({
		url : '?model=hr_certifyapply_cdetail&action=listJson',
		param : {
			"assessId" : $("#id").val()
		},
		objName : 'cassess[cdetail]',
		tableClass : 'form_in_table',
		title : '任职资格等级标准',
		isAddAndDel : false,
		colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden'
			}, {
				display : '模版Id',
				name : 'modeId',
				type : 'hidden'
			}, {
				display : '行为模块id',
				name : 'moduleId',
				type : 'hidden'
			}, {
				display : '行为模块',
				name : 'moduleName',
				type : 'statictext'
			}, {
				display : '行为要项id',
				name : 'detailId',
				type : 'hidden'
			}, {
				display : '行为要项',
				name : 'detailName',
				type : 'statictext'
			}, {
				display : '权重',
				name : 'weights',
				type : 'statictext',
				process : function(v) {
					return v + " %";
				}
			}, {
				display : '任职标准',
				name : 'standard',
				type : 'statictext'
			}, {
				display : '需要提供的评价材料',
				name : 'needMaterial',
				type : 'statictext'
			}, {
				display : '认证材料说明',
				name : 'content',
				type : 'textarea',
				cols : '50',
				rows : '5'

			}, {
				display : '材料相关附件',
				name : 'file',
				type : 'file',
				serviceType:'cdetail'
				// html : "<a href='#' onclick=''>附件上传</a>"
			}
		]
	})
})

//编辑页 - 提交审批
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=hr_certifyapply_cassess&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=hr_certifyapply_cassess&action=edit";
	}
}
