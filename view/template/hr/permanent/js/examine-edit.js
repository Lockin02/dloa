$(document).ready(function() {
	GongArr = getData('HRGZJB');
	addDataToSelect(GongArr, 'schemeTypeCode');
	$("#summaryTable").yxeditgrid({
		objName : 'examine[summaryTable]',
		isAddOneRow : true,
		url : '?model=hr_permanent_linkcontent&action=listJson',
		param : {
			parentId : $("#parentId").val(),
			ownType : 1
		},
		colModel : [{
			display : '����Ҫ�����[*]',
			name : 'workPoint',
			width:'40%',
			validation : {
				required : true
			}
		}, {
			display : '����ɹ�[*]',
			name : 'outPoint',
			width:'40%',
			validation : {
				required : true
			}
		}, {
			display : '���ʱ��ڵ�[*]',
			name : 'finishTime',
			width:'15%',
			event : {
				focus : function() {
					WdatePicker();
				}
			},
			validation : {
				required : true
			}
		}, {
			name : 'ownType',
			type : 'hidden',
			value : 1
		},{
			type : 'hidden',
			name : 'id',
			display : 'id'
		}]
	});

	$("#planTable").yxeditgrid({
		objName : 'examine[planTable]',
		url : '?model=hr_permanent_linkcontent&action=listJson',
		param : {
			parentId : $("#parentId").val(),
			ownType : 2
		},
		isAddOneRow : true,
		colModel : [{
			display : '����Ҫ�����[*]',
			name : 'workPoint',
			width:'40%',
			validation : {
				required : true
			}
		}, {
			display : '����ɹ�������׼[*]',
			name : 'outPoint',
			width:'40%',
			validation : {
				required : true
			}
		}, {
			display : '���ʱ��ڵ�[*]',
			name : 'finishTime',
			width:'15%',
			event : {
				focus : function() {
					WdatePicker();
				}
			},
			validation : {
				required : true
			}
		}, {
			name : 'ownType',
			type : 'hidden',
			value : 2
		},{
			type : 'hidden',
			name : 'id',
			display : 'id'
		}]
	});
	$("#schemeTable").yxeditgrid({
		objName : 'examine[schemeTable]',
		url : '?model=hr_permanent_detail&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},
		isAddOneRow : true,
		isAddAndDel : false,
		realDel : true,
		colModel : [{
			type : 'hidden',
			name : 'id',
			display : 'id'
		},{
								name : 'standardId',
								type : 'hidden'
							},{
								display : '������Ŀ',
								name : 'standard',
								width:'15%',
								readonly : 'readonly',
								type : 'statictext'
							}, {
								display : '���˷���',
								name : 'standarScore',
								width:'7%',
								readonly : 'readonly',
								type : 'statictext'
							},{
								display : '����Ȩ��',
								name : 'standardProportion',
								width:'7%',
								readonly : 'readonly',
								type : 'statictext'
							},{
								display : '��������',
								name : 'standardContent',
								width:'35%',
								readonly : 'readonly',
								type : 'statictext',
								align:'left'
							}, {
								display : '����Ҫ��',
								name : 'standardPoint',
								width:'35%',
								readonly : 'readonly',
								type : 'statictext',
								align:'left'
							},{
								display : '����[*]',
								name : 'selfScore',
								validation : {
									required : true,
									custom : ['onlyNumber']
								},
								event : {
									blur : function() {
										caculate();
									}
								}
							}]
						});
		validate({
//					"phone" : {
//						required : true
//					},
//					"workSeniority" : {
//						required : true
//					}
			});
     })

 function caculate() {
	var rowAmountVa = 0;
	var cmps = $("#schemeTable").yxeditgrid("getCmpByCol", "selfScore");
	var portions = $("#schemeTable").yxeditgrid("getCmpByCol", "standarScore");
	var standardProportion = $("#schemeTable").yxeditgrid("getCmpByCol", "standardProportion");
	for(var i=0;i<cmps.length;i++){
	if(parseInt(cmps[i].value)>parseInt(portions[i].value)){
		alert("���ֲ��ܸ��ڿ��˷������ֵ");
		cmps[i].value='';
		$("#selfScore").val("");
		return false;}
	else{
		if(cmps[i].value.indexOf(".")!=-1){
			alert("����������")
			cmps[i].value='';
			$("#selfScore").val("");
			return false;
		}
	}
	}
// cmps.each(function() {
// rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
// });
	for(var i=0;i<cmps.length;i++){
		var percent=accDiv(standardProportion[i].value, 10);
		var mark=accMul(cmps[i].value,percent);// ��ðٷֱȺ�ķ���
		rowAmountVa = accAdd(rowAmountVa, mark, 2);// �������
	}
	if(rowAmountVa>100){
		alert("�ܺͲ��ܳ���100��");return false;
		}
	$("#selfScore").val(rowAmountVa);
	return true;
}