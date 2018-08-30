$(document).ready(function() {
	//��Ա��Ⱦ
//	$("#applyUser").yxselect_user({
//		hiddenId : 'applyUserId',
//		isGetDept : [true, "deptId", "deptName"],
//		formCode : 'resourceapply'
//	});

	//�ʼ���������Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'resourceapply'
	});

	//��ȡʡ�����鲢��ֵ��provinceArr
	var provinceArr = getProvince();
	//��ʡ������provinceArr��ֵ��proCode
	addDataToProvince(provinceArr,'placeId');

	//������Ŀ��Ⱦ
	$("#projectCode").yxcombogrid_esmproject({
		hiddenId : 'projectId',
		nameCol : 'projectCode',
		isShowButton : false,
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'statusArr' : 'GCXMZT02,GCXMZT01'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectName").val(data.projectName);
					$("#place").val(data.place);
					$("#managerName").val(data.managerName);
					$("#managerId").val(data.managerId);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#projectName").val('');
				$("#place").val('');
				$("#managerName").val('');
				$("#managerId").val('');
			}
		}
	});

	//��applyType���¼�
	$("#applyType").bind('change',showProjectNeed);

	//������������
	function showProjectNeed(){
		if($("#applyType").val() == "GCSBSQ-01"){//��Ŀ����
			$("#projectCodeShow").addClass("blue");
			$("#projectCode").addClass("validate[required]");
			$("#placeShow").addClass("blue");		//ʡ�ݲ���
			$("#placeId").addClass("validate[required]");
		}else{//��������
			$("#projectCodeShow").removeClass("blue");
			$("#projectCode").removeClass("validate[required]");
			$("#placeShow").removeClass("blue");
			$("#placeId").removeClass("validate[required]");
		}
	}

	//��ʼ��
	showProjectNeed();

	//�ӱ��ʼ��
	$("#importTable").yxeditgrid({
		url : "?model=engineering_resources_resourceapplydet&action=listJson",
		param : {"mainId" : $("#id").val(),"status" : '0'},
		objName : 'resourceapply[resourceapplydet]',
		tableClass : 'form_in_table',
		isAdd : false,
		async : false,
		hideRowNum : true,
		colModel : [{
			display : 'ȷ��',
			name : 'isChecked',
			type : 'checkbox',
			checkVal : '1',
			process : function ($input ,rowData) {
				var rowNum = $input.data("rowNum");
				$("#importTable_cmp_isChecked" + rowNum).attr('checked' ,'checked');
			}
		}, {
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			width : 80
		}, {
			display : '�豸����',
			name : 'resourceName',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_esmdevice({
					hiddenId : g.el.attr('id')+ '_cmp_resourceId' + rowNum,
					width : 600,
					isFocusoutCheck : false,
					gridOptions : {
						showcheckbox : false,
						param : {
							'resourceTypeId' : g.getCmpByRowAndCol(rowNum,'resourceTypeId').val()
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'resourceTypeName').val(rowData.deviceType);
									g.getCmpByRowAndCol(rowNum,'resourceTypeId').val(rowData.typeid);

									g.getCmpByRowAndCol(rowNum,'unit').val(rowData.unit);
									g.setRowColValue(rowNum, 'price', rowData.budgetPrice, true);
									//�����豸���
									calResourceBatch(rowNum);
									calAmount();
								}
							})(rowNum)
						}
					}
				}).attr("readonly",false);
			}
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			width : 50
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			process : function ($input, rowData) {
				var oldNum = $input.val();
				$input.change(function () {
					if ($(this).val() > oldNum) {
						alert('�豸�����������ӣ�');
						$(this).val(oldNum);
					} else if ($(this).val() < 1) {
						alert('�豸������Ч��');
						$(this).val(oldNum);
					}
				})
			},
			event : {
				blur : function() {
					//�����豸���
					calResourceBatch($(this).data("rowNum"));
					calAmount();
				}
			},
			width : 50
		}, {
			display : '��������',
			name : 'planBeginDate',
			tclass : 'txtshort Wdate',
			type : 'date',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planBeginDate = $(this).val();
					var planEndDate = g.getCmpByRowAndCol(rowNum,'planEndDate').val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'useDays').val(days);
						calResourceBatch(rowNum);
					}
					calAmount();
				}
			},
			validation : {
				required : true
			},
			width : 90
		}, {
			display : '�黹����',
			name : 'planEndDate',
			tclass : 'txtshort Wdate',
			type : 'date',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planBeginDate = g.getCmpByRowAndCol(rowNum,'planBeginDate').val();
					var planEndDate = $(this).val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'useDays').val(days);
						calResourceBatch(rowNum);
					}
					calAmount();
				}
			},
			validation : {
				required : true
			},
			width : 90
		}, {
			display : 'ʹ������',
			name : 'useDays',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 60
		}, {
			display : '�豸�ۼ�',
			name : 'price',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : 'Ԥ�Ƴɱ�',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : '��ע˵��',
			name : 'remark',
			tclass : 'txtmiddle'
		}]
	});

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({
		"applyUser" : {
			required : true
		},
		"applyDate" : {
			required : true
		},
		"applyType" : {
			required : true
		},
		"getType" : {
			required : true
		},
		"reason" : {
			required : true
		},
		"proCode" :{
			required : true
		},
		"mobile" :{
			required : true
		}
	});
	var divDocument = document.getElementById("importTable");
	var tbody = divDocument.getElementsByTagName("tbody");
	var $tbody = $(tbody)
	$tbody.after('<tr class="tr_count"><td colspan="3">�ϼ�</td>'+
			'<td></td><td></td><td></td><td></td><td></td><td></td><td></td>'
			+'<td><input type="text" id="view_amount" name="resourceapply[amount]" class="readOnlyTxtShortCount formatMoney" readonly="readonly"/></td>'
			+'<td></td></tr>');
	calAmount();
	//��ȡ���÷�ʽ��ʼֵ���ж��Ƿ���ʾ�ʼĵ�ַ
	if($("#getType").val() == 'GCSBLY-01'){
		$("#address").parents("tr:first").hide();
	}else if($("#getType").val() == 'GCSBLY-02'){
		$("#address").addClass("validate[required]");
	}
	//���÷�ʽΪ���ʱ����ʾ�ʼĵ�ַ�򣬲�Ҫ�����
	$("#getType").change(function(){
		$("#address").parents("tr:first").toggle();
		if($("#address").parents("tr:first").is(":visible")){
			$("#address").addClass("validate[required]");
		}else{
			$("#address").removeClass("validate[required]");
			$("#address").val("");
		}
	})
});

//���ñ�����״̬
function setAudit(thisVal){
	$("#audit").val(thisVal);
}