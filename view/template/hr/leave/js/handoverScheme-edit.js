$(document).ready(function() {

	//����(��˾)
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
					display : '������Ŀ',
					name : 'items',
					type : 'txt',
					width : 130,
					validation : {
						required : true
					}
				}, {
					display : '������',
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
					display : '������ID',
					name : 'recipientId',
					type : 'txt',
					type:'hidden'
				},{
					display : '��ע',
					name : 'remark',
					type : 'txt',
					width : 130
				},{
					name : 'advanceAffirm',
					type : 'checkbox',
					display : '�Ƿ������ǰȷ��',
					checked : false,
					checkVal : '1',
					width : 50,
					value : 1
				},{
					display : '����',
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
					display : '�Ƿ����ʼ�',
					name : 'mailAffirm',
					type : 'checkbox',
					checked : false,
					checkVal : '1',
					width : 40
				},{
					display : '����ǰ��',
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