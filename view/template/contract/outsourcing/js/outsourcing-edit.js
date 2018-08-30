/**
 * ��ȡʡ������
 */
function getProvince() {
	var responseText = $.ajax({
		url : 'index1.php?model=system_procity_province&action=getProvinceNameArr',
		type : "POST",
		async : false
	}).responseText;
	var o = eval("(" + responseText + ")");
	return o;
}

/**
 * ���ʡ��������ӵ�������
 */
function addDataToProvince(data, selectId) {
	for (var i = 0, l = data.length; i < l; i++) {
		$("#" + selectId).append("<option title='" + data[i].text
			+ "' value='" + data[i].value + "'>" + data[i].text
			+ "</option>");
	}
}
/**
* ��ʡ�ݸı�ʱ�ԣ���esmproject[proCode]��title��ֵ��ֵ��esmproject[proName]
*/
function setProName(){
	$('#proName').val($("#proCode").find("option:selected").attr("title"));
}

//�༭ʱ�ύ����
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=edit";
	}
}

var pageAttr = 'edit';//����ҳ�������������Ⱦ����/��Ա������Ϣ
$(document).ready(function(){
	//������Ϣ��Ⱦ
	var isNeedPayapplyObj = $("#isNeedPayapply");
	if(isNeedPayapplyObj.val() == 1){
		isNeedPayapplyObj.trigger('click');
		showPayapplyInfo(isNeedPayapplyObj[0]);
	}

	//��ȡʡ�����鲢��ֵ��provinceArr
	provinceArr = getProvince();

	//��ʡ������provinceArr��ֵ��proCode
	addDataToProvince(provinceArr,'proCode');

	//��ѡ������
	$("#principalName").yxselect_user({
		hiddenId : 'principalId',
		isOnlyCurDept : false,
		isGetDept : [true, "deptId", "deptName"]
	});

	//��ѡ����
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	//ǩԼ��λ
//	$("#signCompanyName").yxcombogrid_outsupplier({
//		hiddenId : 'signCompanyId',
//		isFocusoutCheck : false,
//		
//		gridOptions : {
//			param : {
//				suppGradeStr :'1,2,3'
//			},
//			event : {
//				'row_dblclick' : function(e, row, data) {
//					$("#proName").val(data.province);
//					$("#address").val(data.address);
//					$.ajax({
//						type : "POST",
//						url : "?model=outsourcing_supplier_basicinfo&action=getInfo",
//						data : {
//							id : data.id ,
//							provinceId : data.provinceId
//						},
//						success : function (datas){
//							var dataArr = eval("(" + datas + ")");
//							$("#proCode").val(dataArr[0]['provinceCode']);
//							$("#phone").val(dataArr[0]['phone']);
//							$("#linkman").val(dataArr[0]['linkman']);
//							$("#bank").val(dataArr[0]['bank']);
//							$("#account").val(dataArr[0]['account']);
//						}
//					});
//					
//				}
//			}
//		}
//	});
	
	$("#signCompanyName").yxcombogrid_signcompany({
		hiddenId : 'signCompanyId',
		isFocusoutCheck : false,
		gridOptions : {
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#proCode").val(data.proCode);
					$("#proName").val(data.proName);
					$("#linkman").val(data.linkman);
					$("#phone").val(data.phone);
					$("#address").val(data.address);
				}
			}
		}
	});
	
	//������˾
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId : 'businessBelong',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					//��ʼ�����ṹ
					initTree();
					//�������η�Χ
					reloadManager();
				}
			}
		}
	});

	// ��֤��Ϣ
	validate({
		"orderName" : {
			required : true,
			length : [0,100]
		},
		"signCompanyName" : {
			required : true,
			length : [0,100]
		},
		"linkman" : {
			required : true,
			length : [0,100]
		},
		"phone" : {
			required : true
		},
		"adderss" : {
			required : false,
			length : [0,300]
		},
		"orderMoney_v" : {
			required : true
		},
		"principalName" : {
			required : true,
			length : [0,20]
		},
		"deptName" : {
			required : true
		},
		"payCondition" : {
			required : true,
			length : [0,300]
		},
		"signDate" : {
			required : true,
			custom : ['date']
		},
		"beginDate" : {
			required : true,
			custom : ['date']
		},
		"endDate" : {
			required : true,
			custom : ['date']
		},
		"businessBelongName" : {
			required : true
		}
	});
});

function itemDetail(){
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'outsourcing[items]',
			url : '?model=contract_personrental_personrental&action=listJson',
			isAddAndDel : true,
			param : {
				mainId : $("#id").val()
			},
			colModel : [{
				name : 'id',
				tclass : 'txt',
				display : 'id',
				sortable : true,
				type : "hidden"
			},{
				name : 'personLevel',
				display : '��Ա����',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '��Ա��������',
				process : function($input, rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_eperson({
						hiddenId : 'importTable_cmp_budgetId' + rowNum,
						width : 600,
						height : 300,
						gridOptions : {
							showcheckbox : false,
							event : {
								row_dblclick : (function(rowNum) {
									return function(e, row, rowData) {
										g.getCmpByRowAndCol(rowNum,'personLevel').val(rowData.id);
									}
								})(rowNum)
							}
						}
					});
				},
				width : 100
			}, {
				name : 'pesonName',
				display : '����',
				width : 100
			}, {
				name : 'beginDate',
				display : '���޿�ʼ����',
				type : 'date',
				width : 100
			}, {
				name : 'endDate',
				display : '���޽�������',
				type : 'date',
				width : 100
			}, {
				name : 'selfPrice',
				display : '�����������ɱ�',
				type : 'money',
				width : 100
			}, {
				name : 'rentalPrice',
				display : '����۸�',
				type : 'money',
				width : 100
			}, {
				name : 'skillsRequired',
				display : '��������Ҫ��',
				width : 100
			}, {
				name : 'interviewResults',
				display : '�������Խ��',
				width : 100
			}, {
				name : 'interviewName',
				display : '������Ա',
				width : 100
			}, {
				name : 'interviewId',
				display : '������Աid',
				type : "hidden",
				width : 100
			}, {
				name : 'remark',
				display : '��ע',
				width : 100
			}]
		});
		tableHead();
	}
}