$(function(){
	//����(��˾)
	$("#companyName").val('���Ͷ���');//���������Ĭ�����Ͷ���
	$("#companyId").bind('change', function() {
		var name=$("#companyId").find("option:selected").html();
		$("#companyName").val(name);
	})
	//��ְ����
	leaveTypeCodeArr = getData('YGZTLZ');
	addDataToSelect(leaveTypeCodeArr, 'leaveTypeCode');

$("#schemeTemplate").yxcombogrid_handoverscheme({
		hiddenId : 'schemeTemplateId',
		width:600,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data){
					$("#schemeAttrList").yxeditgrid('remove');
					initTemplate(data.id);
				}
			}
		}
	});

		function initTemplate(schemeId){
				$("#schemeAttrList").yxeditgrid({
						objName : 'handoverScheme[attrvals]',
						tableClass : 'form_in_table',
						url : '?model=hr_leave_formwork&action=listJson',
						param : {
							parentCode :schemeId,
							dir : 'ASC'
						},
						sort :'id',
						dir : 'ASC',
						realDel : true,
						colModel : [ {
									display : '������Ŀ',
									name : 'items',
									type : 'txt',
									width : 150,
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
									width : 100
								},{
									display : '�Ƿ������ǰȷ��',
									name : 'advanceAffirm',
									type : 'checkbox',
									checked : false,
									checkVal : '1',
									value : 1
								},{
									display : '����',
									name : 'sort',
									type : 'txt',
									width : 20,
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
									value : 1,
									width : 70
								},{
									display : '����ǰ��',
									name : 'sendPremise',
									type : 'txt',
									width : 80
								}]
					});
	}

	$("#schemeAttrList").yxeditgrid({
		objName : 'handoverScheme[attrvals]',
		tableClass : 'form_in_table',
		sort :'orderIndex',
		dir : 'ASC',
		colModel : [ {
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
					width : 100
				},{
					display : '�Ƿ������ǰȷ��',
					name : 'advanceAffirm',
					type : 'checkbox',
					checked : false,
					checkVal : '1',
					value : 1
				},{
					display : '����',
					name : 'sort',
					type : 'txt',
					width : 20,
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
					width : 70
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