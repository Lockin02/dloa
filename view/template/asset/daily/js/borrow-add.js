function checkAsset(assetCode){
	var flag=0
	 $.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=checkAsset',
		data : {
			assetCode : assetCode
		},
	    async: false,
		success : function(data) {
			if(data==0){
				return flag=0;
			}else{
				return flag=1;
			}
		}
	})
	return flag
}
$(function() {
	// /**
	// * ���Ψһ����֤
	// */
	//
	//
	// var url = "?model=asset_daily_borrow&action=checkRepeat";
	// $("#billNo").ajaxCheck({
	// url : url,
	// alertText : "* �ñ���Ѵ���",
	// alertTextOk : "* �ñ�ſ���"
	// });
//	itemAddFun();
	// ѡ��ʹ�������
	$("#chargeMan").yxselect_user({
		hiddenId : 'chargeManId',
		isGetDept : [true, "deptId", "deptName"],
		mode : 'single',
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#applyCompanyCode').val(returnValue.companyCode)
					$('#applyCompanyName').val(returnValue.companyName)
				}
			}
		}
	});
	//���´�
	$("#agencyName").yxcombogrid_agency({
		hiddenId : 'agencyCode',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					var rowNum = $("#borrowTable").yxeditgrid('getCurShowRowNum');
					if( typeof(rowNum)=='number' ){
						for( var i=0;i<rowNum;i++ ){
							$('#borrowTable_cmp_productName'+i).yxcombogrid_assetrequireitem('remove');
						}
					}
					$('#agencyCode').val(data.agencyCode);
					$("#borrowTable").yxeditgrid("remove");
					itemAddFun(data.agencyCode);
				}
			}
		}
	});
	// ѡ��ʹ�ò������
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});
	// ѡ�����������
	$("#reposeMan").yxselect_user({
		hiddenId : 'reposeManId',
		isGetDept : [true, "reposeDeptId", "reposeDept"],
		mode : 'single'
	});
	// ѡ�����������
	$("#reposeDept").yxselect_dept({
		hiddenId : 'reposeDeptId'
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"billNo" : {
			required : true
		},

//		"borrowCustome" : {
//			required : true
//		},
		"deptId" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"chargeManId" : {
			required : true
		},
		"chargeMan" : {
			required : true
		},
		"reposeDeptId" : {
			required : true
		},
		"reposeDept" : {
			required : true
		},
		"reposeManId" : {
			required : true
		},
		"reposeMan" : {
			required : true
		},
		"borrowDate" : {
			required : true
		},
		"predictDate" : {
			required : true
		},
		"agencyName" : {
			required : true
		}
	});
	// �����ͻ����
	$("#borrowCustome").yxcombogrid_customer({
		hiddenId : 'borrowCustomeId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});

	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=listByRequireJson',
		objName : 'requirement[items]',
		type:'view',
		title : '�����豸�嵥',
		param : {
			mainId : $("#requireId").val()
		},
		isAddOneRow : true,
		colModel : [{
			display : 'id',
			name : 'id',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'productId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'description',
			validation : {
				required : true
			},
			tclass : 'txt'
		}, {
			display : '����',
			name : 'number',
			validation : {
				required : true ,
				custom : ['onlyNumber']
			},
			tclass : 'txtshort'
		}, {
			display : '�ѷ�������',
			name : 'executedNum',
			validation : {
				required : true ,
				custom : ['onlyNumber']
			},
			tclass : 'txtshort'
		}, {
			display : 'Ԥ�ƽ��',
			name : 'expectAmount',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'dateHope',
			type:'date',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	})

});

/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_daily_borrow&action=add&actType=audit");
		$("#form1").submit();
	} else {
		return false;
	}
}

/**
 *
 * ����֤
 */
function checkForm(obj) {
	var s = plusDateInfo('borrowDate', 'predictDate');

	if (s <= 0) {
		alert("Ԥ�ƹ黹���ڲ������ڽ������ڣ�"); // ʱ����֤
		$(obj).val('');
	}

}

/**
 *
 * ����֤
 */
function checkSubmit(obj) {	
	var itemTable = $("#itemTable");
	var number = 0;
	var executedNum = 0;
	var itemId = 0;
	var itemIdArr = [];
	var countArr = [];
	//��������豸�嵥δ��������
	for(var i = 0;i < itemTable.yxeditgrid("getAllAddRowNum");i++){
		number = itemTable.yxeditgrid("getCmpByRowAndCol",i,"number").val();
		executedNum = itemTable.yxeditgrid("getCmpByRowAndCol",i,"executedNum").val();
		itemId = itemTable.yxeditgrid("getCmpByRowAndCol",i,"id").val();
		itemIdArr[itemId] = accSub(number,executedNum);
	}
	var borrowTable = $("#borrowTable");
	//����ѡ��Ŀ�Ƭ����
	for(var i = 0;i < borrowTable.yxeditgrid("getAllAddRowNum");i++){
		itemId = borrowTable.yxeditgrid("getCmpByRowAndCol",i,"itemId").val();
		if(!countArr[itemId]){
			countArr[itemId] = 1;
		}else{
			countArr[itemId] = ++countArr[itemId];
		}
	}
	//��֤ѡ��Ŀ�Ƭ�����Ƿ�������Χ�ڣ�δ��������=����-�ѷ�������
	for (var i in countArr) { 
		if(itemIdArr[i] && itemIdArr[i]<countArr[i]){
			alert("ѡ��Ŀ�Ƭ�������������豸�嵥δ����������");
			return false;
		}
	}
	var rowNum = borrowTable.yxeditgrid('getCurShowRowNum');
	if(rowNum == '0'){
	  alert("�ʲ��嵥����Ϊ��");
	  return false;
	}else{
		return true;
	}
}

function itemAddFun(agency){
	/**
	 * ѡ��Ƭ���Զ��������ԭֵ����Ϣ
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		g.getCmpByRowAndCol(rowNum, 'assetId').val(rowData.id);
		g.getCmpByRowAndCol(rowNum, 'assetCode').val(rowData.assetCode);
		g.getCmpByRowAndCol(rowNum, 'assetName').val(rowData.assetName);
		g.getCmpByRowAndCol(rowNum, 'buyDate').val(rowData.buyDate);
		g.getCmpByRowAndCol(rowNum, 'spec').val(rowData.spec);
		g.getCmpByRowAndCol(rowNum, 'estimateDay').val(rowData.estimateDay);
		g.getCmpByRowAndCol(rowNum, 'residueYears').val(rowData.estimateDay - rowData.alreadyDay);
	}
	$("#borrowTable").yxeditgrid({
		objName : 'borrow[borrowitem]',
		// url:'?model=asset_daily_borrowitem',
		title : '��Ƭ��Ϣ',
		isAddOneRow : false,
		colModel : [{
			display : '�豸�嵥Id',
			name : 'itemId',
			type : 'hidden'
		}, {
			display : '��������Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��Ӧ��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_assetrequireitem({
					isFocusoutCheck : false,
					hiddenId : 'borrowTable_cmp_productId' + rowNum,
					nameCol : 'productName',
					gridOptions : {
						 param : {
							 'mainId' : $('#requireId').val()
						 },
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.productId);
									g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.description);
									g.getCmpByRowAndCol(rowNum, 'itemId').val(rowData.id);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '��Ƭ���',
			name : 'assetCode',
			tclass : 'txt',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'borrowTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'agencyCode' : agency,
							'machineCodeSearch':'0',
							'isDel' : '0',
							'belongTo' : '0',
							'idle' : '0',
							'isScrap':'0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
//									if(checkAsset(rowData.assetCode)!=0){
//										alert("���ʲ��ѽ������ѡ�������ʲ�.");
//										return false;
//									}
									$cmps.each(function() {
										if ($(this).val() == rowData.assetCode) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetName = g.getCmpByRowAndCol(
												rowNum, 'assetName');
										$assetName.val(rowData.assetName);
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			validation : {
				required : true
			},
			process : function($input, rowData) {

				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'borrowTable_cmp_assetId' + rowNum,
					gridOptions : {
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'agencyCode' : agency,
							'machineCodeSearch':'0',
							'isDel' : '0',
							'belongTo' : '0',
							'idle' : '0',
							'isScrap':'0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
//									if(checkAsset(rowData.assetCode)!=0){
//										alert("���ʲ��ѽ������ѡ�������ʲ�.");
//										return false;
//									}
									$cmps.each(function() {
										if ($(this).val() == rowData.assetCode) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetCode = g.getCmpByRowAndCol(
												rowNum, 'assetCode');
										$assetCode.val(rowData.assetCode);
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '�ʲ�Id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'buyDate',
			// type:'date',
			readonly : true
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : 'Ԥ��ʹ���ڼ���',
			name : 'estimateDay',
			tclass : 'txtshort',
			readonly : true
		}, {
//			display : '�Ѿ�ʹ���ڼ���',
//			name : 'alreadyDay',
//			tclass : 'txtshort',
//			readonly : true
//		}, {
			display : 'ʣ��ʹ���ڼ���',
			name : 'residueYears',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}],
		event : {
//			'clickAddRow' : function(e, rowNum, g) {
//				g.removeRow(rowNum);
//				return false;
//			},
			beforeAddRow : function(e, rowNum, rowData, g) {
				if( $('#agencyCode').val()=='' ){
					alert('��ѡ����������!');
					g.removeRow(rowNum);
				}
			}
		}
	});
	//����ѡ��Ƭ��Ϣ��ť
	$("#borrowTable").find("tr:first td").append("<input type='button' value='ѡ��Ƭ��Ϣ' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
}
//ѡ��Ƭ��Ϣ
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=borrow&agencyCode="
			+$("#agencyCode").val(),1,500,900);
}
//���ÿ�Ƭ����
function setDatas(rows){
	var objGrid = $("#borrowTable");
	for(var i = 0; i < rows.length ; i++){
		//�жϿ�Ƭ�����Ƿ��Ѵ���
		var assetIdArr = objGrid.yxeditgrid("getCmpByCol","assetCode");
		var isExist = false;
		if(assetIdArr.length > 0){
			assetIdArr.each(function(){
				if(this.value == rows[i].assetCode){
					isExist = true;
					alert("�벻Ҫѡ����ͬ���ʲ�" );
					return false;
				}
			});
		}
		//����Ѿ��ظ��ˣ��Ͳ��ܼ���ѡ��
		if(isExist){
			return false;
		}
		//���»�ȡ����
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//������
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"productId",rows[i].productId);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"productName",rows[i].productName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetCode",rows[i].assetCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetName",rows[i].assetName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"buyDate",rows[i].buyDate);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"spec",rows[i].spec);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"estimateDay",rows[i].estimateDay);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"residueYears",rows[i].estimateDay-rows[i].alreadyDay);
	}
}