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

//����ʱ�ύ����
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=add";
	}
}

var pageAttr = 'add';//����ҳ�������������Ⱦ����/��Ա������Ϣ

$(document).ready(function(){
	//��ȡʡ�����鲢��ֵ��provinceArr
	provinceArr = getProvince();

	//��ʡ������provinceArr��ֵ��proCode
	addDataToProvince(provinceArr,'proCode');
	
	if($('#proName').val()!=''){
		$("#proCode option").each(function() {
			if( $(this).text() == $("#proName").val() ){
				$(this).attr("selected","selected'");
			}
		}); 
	}

	//��ʼ��ʡ������
	setProName();

	//��ѡ������
	$("#principalName").yxselect_user({
		hiddenId : 'principalId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'outsourcing'
	});

	//��ѡ����
//	$("#deptName").yxselect_dept({
//		hiddenId : 'deptId'
//	});

	if($("#signCompanyName").val() == ''){
//		//ǩԼ��λ
//		$("#signCompanyName").yxcombogrid_outsupplier({
//			hiddenId : 'signCompanyId',
//			isFocusoutCheck : false,
//			
//			gridOptions : {
//				param : {
//					suppGradeStr :'1,2,3'
//				},
//				event : {
//					'row_dblclick' : function(e, row, data) {
//						$("#proName").val(data.province);
//						$("#address").val(data.address);
//						$.ajax({
//							type : "POST",
//							url : "?model=outsourcing_supplier_basicinfo&action=getInfo",
//							data : {
//								id : data.id ,
//								provinceId : data.provinceId
//							},
//							success : function (datas){
//								var dataArr = eval("(" + datas + ")");
//								$("#proCode").val(dataArr[0]['provinceCode']);
//								$("#phone").val(dataArr[0]['phone']);
//								$("#linkman").val(dataArr[0]['linkman']);
//								$("#bank").val(dataArr[0]['bank']);
//								$("#account").val(dataArr[0]['account']);
//							}
//						});
//						
//					}
//				}
//			}
//		});
		
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
	}
	
	
	
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

	//�ж�ϵͳ��ͬ�����ɹ���
	if($("#isSysCode").val() == 0){
		$("#orderCodeNeed").html("[*]");
		$("#orderCode").attr("class","txt").attr("readonly",false);
		// ��֤��Ϣ
		validate({
			"orderCode" : {
				required : true,
				length : [0,100]
			}
		});

		/**
		 * ��ͬ��Ψһ����֤
		 */
		var url = "?model=contract_outsourcing_outsourcing&action=checkRepeat";
		$("#orderCode").ajaxCheck({
			url : url,
			alertText : "* �ú�ͬ���Ѵ���",
			alertTextOk : "* �ú�ͬ�ſ���"
		});
	}

	//����������
	//outsourType();
});

//��Ա����
function itemDetail() {
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'outsourcing[items]',
			tableClass : 'form_in_table',
			colModel : [{
				name : 'personLevel',
				display : '��Ա����',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '��Ա��������',
				width : 80,
				readonly : true,
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
				}
			}, {
				name : 'pesonName',
				display : '����',
				width : 80
			}, {
				name : 'supplierName',
				display : '���������˾',
				width : 80
			}, {
				name : 'beginDate',
				display : '���޿�ʼ����',
				width : 80,
				type : 'date'
			}, {
				name : 'endDate',
				display : '���޽�������',
				width : 80,
				type : 'date'
			}, {
				name : 'selfPrice',
				display : '�����������ɱ�',
				width : 80,
				type : 'money'
			}, {
				name : 'rentalPrice',
				display : '����۸�',
				width : 80,
				type : 'money'
			}, {
				name : 'skillsRequired',
				display : '��������Ҫ��',
				width : 80
			}, {
				name : 'interviewResults',
				display : '�������Խ��',
				width : 80
			}, {
				name : 'interviewName',
				display : '������Ա',
				width : 80
			}, {
				name : 'interviewId',
				display : '������Աid',
				width : 80,
				type : "hidden"
			}, {
				name : 'remark',
				display : '��ע',
				width : 80
			}]
		});
		tableHead();
	}
}