$(document).ready(function() {

	$("#schemeAttrList").yxeditgrid({
		objName : 'handoverScheme[attrvals]',
		type : 'view',
		url : '?model=hr_leave_formwork&action=listJson',
		param : {
			parentCode : $("#id").val(),
			sort :'sort',
			dir : 'ASC'
		},
		tableClass : 'form_in_table',
		colModel : [{
					display : 'id',
					name : 'id',
					type : 'txt',
					type:'hidden'
				}, {
					display : '交接项目',
					name : 'items',
					type : 'txt',
					width : 130
				}, {
					display : '交接人',
					name : 'recipientName',
					type : 'txt',
					width : 130
				}, {
					display : '交接人ID',
					name : 'recipientId',
					type : 'txt',
					type:'hidden'
				},{
					display : '备注',
					name : 'remark',
					type : 'txt',
					width : 130
				},{
					display : '是否必须提前确认',
					name : 'advanceAffirm',
					type : 'txt',
					width : 60,
					process : function(v, row) {
						if (v == "1") {
							return "<span>是</span>";
						} else {
							return "<span>否</span>";
						}
					}
				},{
					display : '排序',
					name : 'sort',
					type : 'txt',
					width : 50
				},{
					display : '是否发送邮件',
					name : 'mailAffirm',
					type : 'txt',
					process : function(v, row) {
						if (v == "1") {
							return "<span>是</span>";
						} else {
							return "<span>否</span>";
						}
					},
					width : 70
				},{
					display : '发送前提',
					name : 'sendPremise',
					type : 'txt',
					width : 80
				}]
	});

        })