var provinceArr; //�����ʡ������

$(document).ready(function() {
	//ʡ��
	provinceArr = getProvince();
	$("#wholeListInfo").yxeditgrid({
		objName : 'workVerify[wholeList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {
			parentId :$("#id").val(),
			outsourcingArr :'HTWBFS-01,HTWBFS-03'
		},
		dir : 'ASC',
		isAddAndDel : false,
		realDel : false,
		colModel : [{
					type : 'hidden',
					name : 'id',
					display : 'id'
				}, {
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
				},{
					display : '�����˾',
					name : 'outsourceSupp',
					type : 'txt',
					width : 80
//					validation : {
//						required : true
//					}
				},  {
					display : '�����˾ID',
					name : 'outsourceSuppId',
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
				objName : 'workVerify[rentList]',
				url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
				param : {
					dir : 'ASC',
					parentId :$("#id").val(),
					outsourcingArr :'HTWBFS-02'
				},
				isAddAndDel : true,
				realDel : false,
				colModel : [ {
					type : 'hidden',
					name : 'id',
					display : 'id'
				},{
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
					display : '�����˾',
					name : 'outsourceSupp',
					type : 'txt',
					width : 80
//					validation : {
//						required : true
//					}
				},  {
					display : '�����˾ID',
					name : 'outsourceSuppId',
					type : 'txt',
					type:'hidden'
				}, {
					display : '������',
					name : 'principal',
					type : 'txt',
					width : 70
				},{
					display : '��Ա����',
					name : 'personnelType',
					type : 'select',
					datacode:'RYLX',
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

 });
