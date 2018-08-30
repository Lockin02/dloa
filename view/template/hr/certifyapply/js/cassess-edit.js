$(document).ready(function() {
	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		formCode : 'cassessManager'
	});

	$("#memberName").yxselect_user({
		hiddenId : 'memberId',
		mode : 'check',
		formCode : 'cassessMember'
	});

	//模板
	$("#cdetail").yxeditgrid({
		url : '?model=hr_certifyapply_cdetail&action=listJson',
		param : {"assessId" : $("#id").val()},
		objName : 'cassess[cdetail]',
		tableClass : 'form_in_table',
		title : '任职资格等级标准',
		isAdd : false,
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
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '行为要项id',
			name : 'detailId',
			type : 'hidden'
		}, {
			display : '行为要项',
			name : 'detailName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '权重(%)',
			name : 'weights',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '任职标准',
			name : 'standard',
			tclass : 'txtlong',
			validation : {
				required : true
			}
		}, {
			display : '需要提供的评价材料',
			name : 'needMaterial',
			tclass : 'txtlong',
			validation : {
				required : true
			}
		}]
	})
})

//表单验证
function checkform() {

	//判断权重是否为100
	var rowAmountVa = 0;
	var cmps = $("#cdetail").yxeditgrid("getCmpByCol", "weights");
	cmps.each(function () {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	if(rowAmountVa != 100){
		alert('当前权重和为 ' + rowAmountVa + ",请重新调整权重");
		return false;
	}

	return true;
}