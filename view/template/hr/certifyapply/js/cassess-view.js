$(document).ready(function() {
	//模板
	$("#cdetail").yxeditgrid({
		url : '?model=hr_certifyapply_cdetail&action=listJson',
		param : {"assessId" : $("#id").val()},
		tableClass : 'form_in_table',
		type : 'view',
		title : '任职资格等级标准',
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
			name : 'moduleName'
		}, {
			display : '行为要项id',
			name : 'detailId',
			type : 'hidden'
		}, {
			display : '行为要项',
			name : 'detailName'
		}, {
			display : '权重(%)',
			name : 'weights'
		}, {
			display : '任职标准',
			name : 'standard'
		}, {
			display : '需要提供的评价材料',
			name : 'needMaterial'
//			,
//			process : function(html, rowData, trObj , g ,inputObj){
//				$.showDump(inputObj.html());
//			}
		}, {
			display : '认证材料说明',
			name : 'content'
		}, {
			display : '材料相关附件',
			name : 'file',
			serviceType:'cdetail',
			type:'file'
		}]
	})

})