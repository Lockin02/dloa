var provinceArr; //�����ʡ������

$(document).ready(function() {
	//ʡ��
	provinceArr = getProvince();
	validate({
//				"beginDate" : {
//					required : true
//				},
				"endDate" : {
					required : true
				}
			});
	$("#wholeListInfo").yxeditgrid({
		objName : 'suppVerify[wholeList]',
		dir : 'ASC',
		isAddOneRow : false,
		colModel : [ {
					display : '����',
					name : 'officeName',
					type : 'txt',
					width :50,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxcombogrid_office({
							hiddenId: 'wholeListInfo_cmp_officeId'+rowNum
						});
					}
				},  {
					display : '����ID',
					name : 'officeId',
//					type : 'txt'
					type:'hidden'
				}, {
					display : 'ʡ��',
					name : 'provinceId',
					validation : {
						required : true
					},
					type : 'select',
					width : 70,
					options : provinceArr
				},  {
					display : 'ʡ��ID',
					name : 'province',
					type : 'txt',
					type:'hidden'
				}, {
					display : '����',
					name : 'outsourcing',
					type : 'select',
					datacode:'HTWBFS',
					width : 100
				},{
					display : '��Ŀ����',
					name : 'projectName',
					type : 'txt',
					width : 120,
					validation : {
						required : true
					},
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxcombogrid_esmproject({
							nameCol : 'projectName',
							hiddenId : 'wholeListInfo_cmp_projectId' + rowNum,
							isDown : true,
							height : 250,
							gridOptions : {
								isTitle : true,
								event : {
									row_dblclick : function(e, row, data) {
										$("#wholeListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"projectCode").val(data.projectCode);
										//����
										$("#wholeListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeId").val(data.officeId);
										$("#wholeListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeName").val(data.officeName);

										//ʡ�ݺͳ���
										$("#wholeListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"provinceId").val(data.provinceId);
										$("#wholeListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"province").val(data.province);
									}
								}
							}
						});
					}
				},  {
					display : '��ĿID',
					name : 'projectId',
					type : 'txt',
					type:'hidden'
				},{
					display : '��Ŀ���',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '�����ͬ��',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80
//					validation : {
//						required : true
//					}
				},  {
					display : '�����ͬID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				}, {
					display : '������',
					name : 'principal',
					type : 'txt',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '�ܽ���',
					name : 'scheduleTotal',
					type : 'txt',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '���ڽ���',
					name : 'presentSchedule',
					type : 'txt',
					width : 70,
					validation : {
						required : true
					}
				}]
	});

	$("#rentListInfo").yxeditgrid({
		objName : 'suppVerify[rentList]',
		dir : 'ASC',
		colModel : [ {
					display : '����',
					name : 'officeName',
					type : 'txt',
					width :50,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxcombogrid_office({
							hiddenId: 'rentListInfo_cmp_officeId'+rowNum
						});
					}
				},  {
					display : '����ID',
					name : 'officeId',
//					type : 'txt'
					type:'hidden'
				}, {
					display : 'ʡ��',
					name : 'provinceId',
					validation : {
						required : true
					},
					type : 'select',
					width : 70,
					options : provinceArr
				},  {
					display : 'ʡ��ID',
					name : 'province',
					type : 'txt',
					type:'hidden'
				}, {
					display : '��Ŀ����',
					name : 'projectName',
					type : 'txt',
					width : 120,
					validation : {
						required : true
					},
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxcombogrid_esmproject({
							nameCol : 'projectName',
							hiddenId : 'rentListInfo_cmp_projectId' + rowNum,
							isDown : true,
							height : 250,
							gridOptions : {
								isTitle : true,
								event : {
									row_dblclick : function(e, row, data) {
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"projectCode").val(data.projectCode);
										//����
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeId").val(data.officeId);
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeName").val(data.officeName);

										//ʡ�ݺͳ���
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"provinceId").val(data.provinceId);
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"province").val(data.province);
									}
								}
							}
						});
					}
				},  {
					display : '��ĿID',
					name : 'projectId',
					type : 'txt',
					type:'hidden'
				},{
					display : '��Ŀ���',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '�����ͬ��',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80,
					validation : {
						required : true
					}
				},  {
					display : '�����ͬID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				},{
					display : '������',
					name : 'principal',
					type : 'txt',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '����',
					name : 'userName',
					type : 'txt',
					width :80,
					readonly:true,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxselect_user({
							hiddenId: 'rentListInfo_cmp_userId'+rowNum
						});
					}
				}, {
					display : '����ID',
					name : 'userId',
					type : 'txt',
					type:'hidden'
				},{
					display : '���ڿ�ʼ',
					name : 'beginDate',
					type : 'date',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '���ڽ���',
					name : 'endDate',
					type : 'date',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '�Ƽ�����',
					name : 'feeDay',
					type : 'txt',
					width : 60,
					validation : {
						required : true
					}
				}]
	});

 });

 		function initTemplate(beginDate,endDate){
			$("#rentListInfo").yxeditgrid({
					objName : 'suppVerify[rentList]',
					url : '?model=outsourcing_workverify_suppVerify&action=worklogListJson',
					param : {
						beginDate :beginDate,
						endDate :endDate,
						dir : 'ASC'
					},
					dir : 'ASC',
					realDel : false,
				colModel : [ {
					display : '����',
					name : 'officeName',
					type : 'txt',
					width :50,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxcombogrid_office({
							hiddenId: 'rentListInfo_cmp_officeId'+rowNum
						});
					}
				},  {
					display : '����ID',
					name : 'officeId',
//					type : 'txt'
					type:'hidden'
				},  {
					display : '��־ID',
					name : 'ids',
//					type : 'txt'
					type:'hidden'
				}, {
					display : 'ʡ��',
					name : 'provinceId',
					validation : {
						required : true
					},
					type : 'select',
					width : 70,
					options : provinceArr
				},  {
					display : 'ʡ��ID',
					name : 'province',
					type : 'txt',
					type:'hidden'
				}, {
					display : '��Ŀ����',
					name : 'projectName',
					type : 'txt',
					width : 120,
					validation : {
						required : true
					},
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxcombogrid_esmproject({
							nameCol : 'projectName',
							hiddenId : 'rentListInfo_cmp_projectId' + rowNum,
							isDown : true,
							height : 250,
							gridOptions : {
								action : 'myProjectListPageJson',
								param : {'selectstatus' : 'GCXMZT02','isLog' : 1},
								isTitle : true,
								event : {
									row_dblclick : function(e, row, data) {
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"projectCode").val(data.projectCode);
										//����
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeId").val(data.officeId);
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeName").val(data.officeName);

										//ʡ�ݺͳ���
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"provinceId").val(data.provinceId);
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"province").val(data.province);
									}
								}
							}
						});
					}
				},  {
					display : '��ĿID',
					name : 'projectId',
					type : 'txt',
					type:'hidden'
				},{
					display : '��Ŀ���',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '�����ͬ��',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80
//					validation : {
//						required : true
//					}
				},  {
					display : '�����ͬID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				},{
					display : '������',
					name : 'principal',
					type : 'txt',
					width : 70
				},{
					display : '����',
					name : 'userName',
					type : 'txt',
					width :80,
					readonly:true,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxselect_user({
							hiddenId: 'rentListInfo_cmp_userId'+rowNum
						});
					}
				}, {
					display : '����ID',
					name : 'userId',
					type : 'txt',
					type:'hidden'
				},{
					display : '���ڿ�ʼ',
					name : 'beginDate',
					type : 'date',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '���ڽ���',
					name : 'endDate',
					type : 'date',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '�Ƽ�����',
					name : 'feeDay',
					type : 'txt',
					width : 60,
					validation : {
						required : true
					}
				}]
					});
	}

 	//���ݿ�ʼ���ںͽ������ڣ���ȡ��������Ϣ
		function getworkInfo(){
			var beginDate=$("#beginDate").val();
			var endDate=$("#endDate").val();
			if(endDate!=''){
				var s = plusDateInfo('beginDate','endDate');
				if(s < 0) {
					alert("�������ڲ���С�ڿ�ʼ���ڣ�");
				}else{
					$("#rentListInfo").yxeditgrid('remove');
					initTemplate(beginDate,endDate);
				}
			}
		}

		         //ֱ���ύ
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_workverify_suppVerify&action=add&addType=submit";
}