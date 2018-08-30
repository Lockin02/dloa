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
	// var url = "?model=asset_daily_charge&action=checkRepeat";
	// $("#billNo").ajaxCheck({
	// url : url,
	// alertText : "* �ñ�����Ѵ���",
	// alertTextOk : "* �ñ���ſ���"
	// });

	//���´�
	$("#agencyName").yxcombogrid_agency({
		hiddenId : 'agencyCode',
		gridOptions : {
			showcheckbox : false
			,event : {
				'row_dblclick' : function(e, row, data) {
					var rowNum = $("#purchaseProductTable").yxeditgrid('getCurShowRowNum');
					if( typeof(rowNum)=='number' ){
						for( var i=0;i<rowNum;i++ ){
							$('#purchaseProductTable_cmp_productName'+i).yxcombogrid_assetrequireitem('remove');
						}
					}
					$('#agencyCode').val(data.agencyCode);
					$("#purchaseProductTable").yxeditgrid("remove");
					itemAddFun(data.agencyCode);
				}
			}
		}
	});
	// ѡ����Ա���
	$("#chargeMan").yxselect_user({
		hiddenId : 'chargeManId',
		isGetDept : [true, "deptId", "deptName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#applyCompanyCode').val(returnValue.companyCode)
					$('#applyCompanyName').val(returnValue.companyName)
				}
			}
		}
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"billNo" : {
			required : true
		},
		"chargeMan" : {
			required : true
		},
		"agencyName" : {
			required : true
		},
		"chargeDate" : {
			required : true,
			custom : ['date']
		}
	});

	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=listByRequireJson',
		objName : 'requirement[items]',
		type:'view',
		param : {
			mainId : $("#requireId").val()
		},
		title : '�����豸�嵥',
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
	if (confirm("��ȷ��Ҫ�ύ��?")) {
		$("#form1").attr("action",
				"?model=asset_daily_charge&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
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
	var purchaseProductTable = $("#purchaseProductTable");
	//����ѡ��Ŀ�Ƭ����
	for(var i = 0;i < purchaseProductTable.yxeditgrid("getAllAddRowNum");i++){
		itemId = purchaseProductTable.yxeditgrid("getCmpByRowAndCol",i,"itemId").val();
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
	var rowNum = purchaseProductTable.yxeditgrid('getCurShowRowNum');
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
		g.getCmpByRowAndCol(rowNum, 'alreadyDay').val(rowData.alreadyDay);
		g.getCmpByRowAndCol(rowNum, 'residueYears').val(rowData.estimateDay - rowData.alreadyDay);
	}

	$("#purchaseProductTable").yxeditgrid({
		objName : 'charge[item]',
		// url:'?model_asset_daily_loseitem',
		title : '��Ƭ��Ϣ',
		isAddOneRow : false,
		colModel : [{
			display : '�豸�嵥Id',
			name : 'itemId',
			type : 'hidden'
		},{
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
					hiddenId : 'purchaseProductTable_cmp_productId' + rowNum,
					nameCol : 'productName',
					gridOptions : {
						// ���˿�Ƭ,ֻ��ʾ����״̬�Ŀ�Ƭ
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
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						// ���˿�Ƭ,ֻ��ʾ����״̬�Ŀ�Ƭ
						param : {
							'useStatusCode' : 'SYZT-XZ', //����
							'agencyCode' : agency,//���´�
							'machineCodeSearch':'0',//������
							'isDel' : '0',//�Ƿ�ɾ��
							'belongTo' : '0',//�Ƿ����
							'idle' : '0',//�Ƿ����
							'isScrap' : '0'//�Ƿ񱨷�
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
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					gridOptions : {
						// ���˿�Ƭ,ֻ��ʾ����״̬�Ŀ�Ƭ
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'agencyCode' : agency,
							'machineCodeSearch':'0',
							'isDel' : '0',
							'idle' : '0',
							'belongTo' : '0',
							'isScrap' : '0'
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
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '��������',
			name : 'buyDate',
			// type : 'date',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : 'Ԥ��ʹ���ڼ���',
			name : 'estimateDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '�Ѿ�ʹ���ڼ���',
			name : 'alreadyDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : 'ʣ��ʹ���ڼ���',
			name : 'residueYears',
			tclass : 'txtshort',
			readonly : true
				// event : {
				// blur : function(e) {
				// //����ʣ��ʹ������:��Ƭ��Ԥ��ʹ���ڼ�����ȥ��ʹ���ڼ���
				// var rownum = $(this).data('rowNum');// �ڼ���
				// var colnum = $(this).data('colNum');// �ڼ���
				// var grid = $(this).data('grid');// ������
				// var estimateDay =
				// grid.getCmpByRowAndCol(rownum,'estimateDay').val();
				// var alreadyDay =
				// grid.getCmpByRowAndCol(rownum,'alreadyDay').val();
				// var $residueYears = grid.getCmpByRowAndCol(rownum,
				// 'residueYears');
				// $residueYears.val(accSub(estimateDay,alreadyDay));
				// }
				// }
				}, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}]
	});
	//����ѡ��Ƭ��Ϣ��ť
	$("#purchaseProductTable").find("tr:first td").append("<input type='button' value='ѡ��Ƭ��Ϣ' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
}
//ѡ��Ƭ��Ϣ
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=charge&agencyCode="
			+$("#agencyCode").val(),1,500,900);
}
//���ÿ�Ƭ����
function setDatas(rows){
	var objGrid = $("#purchaseProductTable");
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
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"alreadyDay",rows[i].alreadyDay);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"residueYears",rows[i].estimateDay-rows[i].alreadyDay);
	}
}