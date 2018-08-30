$(function() {
	/**
	 * ѡ��Ƭ���Զ��������ԭֵ����Ϣ
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		g.getCmpByRowAndCol(rowNum, 'assetId').val(rowData.id);
		g.getCmpByRowAndCol(rowNum, 'spec').val(rowData.spec);
		g.getCmpByRowAndCol(rowNum, 'englishName').val(rowData.englishName);
		g.getCmpByRowAndCol(rowNum, 'buyDate').val(rowData.buyDate);
		g.getCmpByRowAndCol(rowNum, 'alreadyDay').val(rowData.alreadyDay);
		g.getCmpByRowAndCol(rowNum, 'beforeUse').val(rowData.useType);
		if(rowData.salvage){
			g.setRowColValue(rowNum,'salvage',rowData.salvage,true);
		}else{
			g.setRowColValue(rowNum,'salvage',0,true);
		}
		if(rowData.depreciation){
			g.setRowColValue(rowNum,'depreciation',rowData.depreciation,true);
		}else{
			g.setRowColValue(rowNum,'depreciation',0,true);
		}

		var $equip = g.getCmpByRowAndCol(rowNum, 'equip');
		$equip.children().unbind("click");
		$equip.unbind("click");
		$equip.click((function(assetCode) {
			return function() {
				window
						.open('?model=asset_assetcard_equip&action=toPage&assetCode='
								+ assetCode);
			}
		})(rowData.assetCode));
	}

	$("#purchaseProductTable").yxeditgrid({
		objName : 'sell[item]',
		// url:'?model_asset_disposal_sellitem',
		title : '��Ƭ��Ϣ',
		isAddOneRow : false,
		event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
		colModel : [{
			display : '��Ƭ���',
			name : 'assetCode',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
							'isDel' : '0',
							'isSell' : '0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetName = g.getCmpByRowAndCol(
												rowNum, 'assetName');
										$assetName.val(rowData.assetName);
										var $assetCode = g.getCmpByRowAndCol(
												rowNum, 'assetCode');
										$assetCode.val(rowData.assetCode);
										selectAssetFn(g, rowNum, rowData);
										countAmount();
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
					hiddenId : 'cmp_assetId' + rowNum,
					gridOptions : {
						param : {
							'isDel' : '0',
							'isSell' : '0'
						},
						event : {
							'row_dblclick' : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetName = g.getCmpByRowAndCol(
												rowNum, 'assetName');
										$assetName.val(rowData.assetName);
										var $assetCode = g.getCmpByRowAndCol(
												rowNum, 'assetCode');
										$assetCode.val(rowData.assetCode);
										selectAssetFn(g, rowNum, rowData);
										countAmount();
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
			display : 'Ӣ������',
			name : 'englishName',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '��������',
			name : 'buyDate',
			type : 'date',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '�����豸',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				if (data) {
					var $href = $("<a>��ϸ</a>");
					$href.attr("href", "#");
					$href.click(function() {
						window
								.open('?model=asset_assetcard_equip&action=toPage&assetCode='
										+ data.assetCode);
					})
					return $href;
				} else {
					return '<a href="#" >��ϸ</a>';
				}
			}
		},
				// {
				// display:'��������',
				// name : 'estimateDay',
				// tclass : 'txtshort'
				// },
				{
					display : '�Ѿ�ʹ���ڼ���',// ��ʹ���ڼ���alreadyDay
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '�۳�����',// ����
					name : 'deptName',
					tclass : 'txtshort'
				}, {
					display : '�۳�ǰ��;',// ��;useType
					name : 'beforeUse',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '���۾ɽ��',// �ۼ��۾�depreciation
					name : 'depreciation',
					tclass : 'txtshort',
					type : 'money',
					readonly : true
				}, {
					display : '�����ֵ',// Ԥ�ƾ���ֵsalvage
					name : 'salvage',
					tclass : 'txtshort',
					type : 'money',
					readonly : true
				}
				// , {
				// display:'���۾ɶ�',//��������������ġ�
				// name : 'monthDepr',
				// tclass : 'txtshort'
				// }
				, {
					display : '��ע',// �������ɸ�
					name : 'remark',
					tclass : 'txt'
				}]
	});
	//����ѡ��Ƭ��Ϣ��ť
	$("#purchaseProductTable").find("tr:first td").append("<input type='button' value='ѡ��Ƭ��Ϣ' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
	// ѡ����Ա���
	$("#seller").yxselect_user({
		hiddenId : 'sellerId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"billNo" : {
			required : true

		},
		"seller" : {
			required : true
		},
		"sellNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"sellAmount" : {
			required : true,
			custom : ['money']
		},
		"donationDate" : {
			required : true
		}
	});

});

// ���ݴӱ�Ĳ�ֵ��̬����Ӧ���ܽ��
function countAmount() {
	// ��ȡ��ǰ����������Ƭ���ʲ���
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurShowRowNum")
	$("#sellNum").val(curRowNum);
		var rowAmountVa = 0;
		var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
		cmps.each(function() {
			rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
		});
		$("#sellAmount").val(rowAmountVa);
		$("#sellAmount_v").val(moneyFormat2(rowAmountVa));
	return true;
}
/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_disposal_sell&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
//ѡ��Ƭ��Ϣ
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=sell"
			,1,500,900);
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
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetCode",rows[i].assetCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetName",rows[i].assetName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"spec",rows[i].spec);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"englishName",rows[i].englishName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"buyDate",rows[i].buyDate);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"alreadyDay",rows[i].alreadyDay);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"beforeUse",rows[i].useType);
		if(rows[i].salvage){
			objGrid.yxeditgrid("setRowColValue",tbRowNum,"salvage",rows[i].salvage,true);
		}else{
			objGrid.yxeditgrid("setRowColValue",tbRowNum,"salvage",0,true);	
		}
		if(rows[i].depreciation){
			objGrid.yxeditgrid("setRowColValue",tbRowNum,"depreciation",rows[i].depreciation,true);
		}	
		else{
			objGrid.yxeditgrid("setRowColValue",tbRowNum,"depreciation",0,true);
		}
		var $equip = objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum, 'equip');
		$equip.children().unbind("click");
		$equip.unbind("click");
		$equip.click((function(assetCode) {
			return function() {
				window.open('?model=asset_assetcard_equip&action=toPage&assetCode=' + assetCode);
			}
		})(rows[i].assetCode));
		countAmount();
	}
}