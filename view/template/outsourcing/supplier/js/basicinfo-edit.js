$(document).ready(function() {
	var ExaStatus=$("#ExaStatus").val();
	if(ExaStatus=='���'){
		$("#suppGrade").attr("disabled","disabled");
	}
	 /**
			 * ����Ψһ����֤
			 */

			var url = "?model=outsourcing_supplier_basicinfo&action=checkRepeat";
			if ($("#id").val()) {
				url += "&id=" + $("#id").val();
			}
//			$("#suppName").ajaxCheck({
//						url : url,
//						alertText : "* �ù�˾�����Ѵ���",
//						alertTextOk : "* �ù�˾δ���"
//					});


		//��ѡ����
		$("#officeName").yxcombogrid_office({
			hiddenId : 'officeId',
			gridOptions : {
				showcheckbox : false
			}
		});
		//��֤�ȼ�
		$('select[name="basicinfo[suppGrade]"] option').each(function() {
			if( $(this).val() == $("#suppGradeSelect").val() ){
				$(this).attr("selected","selected'");
			}
		});

	$("#linkmanListInfo").yxeditgrid({
		objName : 'basicinfo[linkman]',
		url : '?model=outsourcing_supplier_linkman&action=listJson',
		param : {
			dir : 'ASC',
			suppId :$("#id").val()
		},
//		dir : 'ASC',
		colModel : [{
					display : '����',
					name : 'name',
					type : 'txt',
					width : 100,
					validation : {
						required : true
					}
				},{
					display : 'ְ��',
					name : 'jobName',
					type : 'txt',
					width : 100,
					validation : {
						required : true
					}
				}, {
					display : '�绰',
					name : 'mobile',
					type : 'txt',
					width : 120,
					validation : {
						required : true
					}
				}, {
					display : '����',
					name : 'email',
					type : 'txt',
					width : 200,
					validation : {
						required : true
					}
				}, {
					display : '��ע',
					name : 'remarks',
					type : 'txt',
					width : 200
				},{
					display : '�Ƿ�Ĭ����ϵ��',
					name : 'defaultContact',
					type : 'checkbox',
					width : 70
				}]
	});

	$("#bankListInfo").yxeditgrid({
		objName : 'basicinfo[bankinfo]',
		url : '?model=outsourcing_supplier_bankinfo&action=listJson',
		param : {
			dir : 'ASC',
			suppId :$("#id").val()
		},
//		dir : 'ASC',
		colModel : [{
					display : '�˻�����',
					name : 'suppAccount',
					type : 'txt',
					width : 200,
					validation : {
						required : true
					}
				},{
					display : '������',
					name : 'bankName',
					type : 'txt',
					width : 300,
					validation : {
						required : true
					}
				},{
					display : '�˺�',
					name : 'accountNum',
					type : 'txt',
					width : 300,
					validation : {
						required : true
					},
					process:function($input, rowData){
						var rowNum = $input.data("rowNum");
						$("#bankListInfo_cmp_accountNum" + rowNum).blur(function() {
							if ($(this).val().trim()
							&& !$("#bankListInfo_cmp_suppAccount" + rowNum).val().trim()) {
								$("#bankListInfo_cmp_suppAccount" + rowNum).val($("#suppName").val());
							}
						});
					}
				}, {
					display : '��ע',
					name : 'remark',
					type : 'txt',
					width : 200
				},{
					display : '�Ƿ�Ĭ���˺�',
					name : 'isDefault',
					type : 'checkbox',
					width : 70
				}]
	});

	$("#hrListInfo").yxeditgrid({
		objName : 'basicinfo[hrinfo]',
		url : '?model=outsourcing_supplier_hrinfo&action=listJson',
		param : {
			dir : 'ASC',
			suppId :$("#id").val()
		},
//		dir : 'ASC',
	   event: {
            'removeRow': function() {
				check_all();
				checkProportion();
            }
        },
		colModel : [{
					display : '��������',
					name : 'skillArea',
					type : 'txt',
					width : 200,
					readonly:true,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxcombogrid_skillarea({
							hiddenId: 'hrListInfo_cmp_skillAreaId'+rowNum
						});
					}
				},  {
					display : '��������ID',
					name : 'skillAreaId',
					type:'hidden'
				},{
					display : '����',
					name : 'primaryNum',
					type : 'txt',
					width : 70,
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
								if (isNaN(this.value)) {
									this.value = 0;
								}else{
								}
							}
							var rownum = $(this).data('rowNum');// �ڼ���
							var colnum = $(this).data('colNum');// �ڼ���
							var grid = $(this).data('grid');// ������
							var middleNum=grid.getCmpByRowAndCol(rownum, 'middleNum').val();
							var expertNum=grid.getCmpByRowAndCol(rownum, 'expertNum').val();
							grid.getCmpByRowAndCol(rownum, 'totalNum').val(expertNum*1+middleNum*1+this.value*1);
							check_all();
							checkProportion();
						}
					},
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				}, {
					display : '�м�',
					name : 'middleNum',
					type : 'txt',
					width : 70,
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
								if (isNaN(this.value)) {
									this.value = 0;
								}else{
								}
							}
							var rownum = $(this).data('rowNum');// �ڼ���
							var colnum = $(this).data('colNum');// �ڼ���
							var grid = $(this).data('grid');// ������
							var primaryNum=grid.getCmpByRowAndCol(rownum, 'primaryNum').val();
							var expertNum=grid.getCmpByRowAndCol(rownum, 'expertNum').val();
							grid.getCmpByRowAndCol(rownum, 'totalNum').val(expertNum*1+primaryNum*1+this.value*1);
							check_all();
							checkProportion();
						}
					},
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				}, {
					display : '�߼�',
					name : 'expertNum',
					type : 'txt',
					width : 70,
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
								if (isNaN(this.value)) {
									this.value = 0;
								}else{
								}
							}
							var rownum = $(this).data('rowNum');// �ڼ���
							var colnum = $(this).data('colNum');// �ڼ���
							var grid = $(this).data('grid');// ������
							var middleNum=grid.getCmpByRowAndCol(rownum, 'middleNum').val();
							var primaryNum=grid.getCmpByRowAndCol(rownum, 'primaryNum').val();
							grid.getCmpByRowAndCol(rownum, 'totalNum').val(primaryNum*1+middleNum*1+this.value*1);
							check_all();
							checkProportion();
						}
					},
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				}, {
					display : '����',
					name : 'totalNum',
					type : 'txt',
					readonly:true,
					tclass:"readOnlyTxt",
					width : 70
				},{
					display : 'ռ��(%)',
					name : 'proportion',
					type : 'txt',
					tclass:"readOnlyTxt",
					width : 70,
					readonly:true
				}, {
					display : '��ע',
					name : 'remark',
					type : 'txt',
					width : 250
				}]
	});

		$("#workListInfo").yxeditgrid({
		objName : 'basicinfo[workinfo]',
		url : '?model=outsourcing_supplier_workInfo&action=listJson',
		param : {
			dir : 'ASC',
			suppId :$("#id").val()
		},
	   event: {
            'removeRow': function() {
            		checkWorkAll();
					checkWorkProportion();
            }
        },
		colModel : [{
					display : '��������',
					name : 'experience',
					type : 'txt',
					width : 200,
					validation : {
						required : true
					}
				}, {
					display : '����',
					name : 'personNum',
					type : 'txt',
					width : 70,
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
								if (isNaN(this.value)) {
									this.value = 0;
								}else{
								}
							}
            				checkWorkAll();
							checkWorkProportion();
						}
					},
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				}, {
					display : 'ռ��(%)',
					name : 'proportion',
					type : 'txt',
					tclass:"readOnlyTxt",
					width : 70,
					readonly:true
				}, {
					display : '��ע',
					name : 'remark',
					type : 'txt',
					width : 250
				}]
	});



	validate({
				"suppName" : {
					required : true
				},
				"officeName" : {
					required : true
				},
				"province" : {
					required : true
				},
				"suppTypeCode" : {
					required : true
				},
				"suppGrade" : {
					required : true
				}
			});
     })