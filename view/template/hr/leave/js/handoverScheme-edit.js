$(document).ready(function() {

	//编制(公司)
	$("#companyId").bind('change', function() {
		var name=$("#companyId").find("option:selected").html();
		$("#companyName").val(name);
	})
	$("#schemeAttrList").yxeditgrid({
		objName : 'handoverScheme[attrvals]',
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
					width : 130,
					validation : {
						required : true
					}
				}, {
					display : '交接人',
					name : 'recipientName',
					type : 'txt',
					width : 130,
					readonly:true,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");

						$input.yxselect_user({
							mode : 'check',
							hiddenId: 'schemeAttrList_cmp_recipientId'+rowNum
						});
					}
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
					name : 'advanceAffirm',
					type : 'checkbox',
					display : '是否必须提前确认',
					checked : false,
					checkVal : '1',
					width : 50,
					value : 1
				},{
					display : '排序',
					name : 'sort',
					type : 'txt',
					width : 50,
					process : function($input) {
						var rowNum = $input.data("rowNum");
						var itemTableObj = $("#schemeAttrList");
						itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"sort").val(rowNum+1);
					},
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				},{
					display : '是否发送邮件',
					name : 'mailAffirm',
					type : 'checkbox',
					checked : false,
					checkVal : '1',
					width : 40
				},{
					display : '发送前提',
					name : 'sendPremise',
					type : 'txt',
					width : 80
				}]
	});
	$("#jobName").yxcombogrid_jobs({
		hiddenId : 'jobId',
		width:350
	});
	validate({
				"schemeName" : {
					required : true
				},
				"companyName" : {
					required : true
				},
				"schemeTypeName" : {
					required : true
				}
			});
  })