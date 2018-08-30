$(function() {
	var hasSource = false;// �Ƿ���Դ����ʶ
	/**
	 * ѡ��Ƭ���Զ��������ԭֵ����Ϣ
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $assetId = g.getCmpByRowAndCol(rowNum, 'assetId');
		$assetId.val(rowData.id);
		// //����ֻ�����˹���������Կ���Ա���մ�ģʽ���
		// //��ƬrowData.origina���ԭֵ��(rowNum, 'original')Ϊ�ӱ��ԭֵ.
		var $buyDate = g.getCmpByRowAndCol(rowNum, 'buyDate');
		$buyDate.val(rowData.buyDate);
		var $alreadyDay = g.getCmpByRowAndCol(rowNum, 'alreadyDay');
		$alreadyDay.val(rowData.alreadyDay);
		var $estimateDay = g.getCmpByRowAndCol(rowNum, 'estimateDay');
		$estimateDay.val(rowData.estimateDay);
		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);
		var $residueYears = g.getCmpByRowAndCol(rowNum, 'residueYears');
		$residueYears.val(rowData.estimateDay - rowData.alreadyDay);
	}
	$("#purchaseProductTable").yxeditgrid({
		objName : 'return[item]',
		url : '?model=asset_daily_returnitem&action=listJson',
		param : {
			allocateID : $("#allocateID").val(),
			assetId : $("#assetId").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��Ƭ���',
			name : 'assetCode',
			tclass : 'txt',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				if (!hasSource) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_asset({
						hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
						nameCol : 'assetCode',
						gridOptions : {
							param : {
								'useStatusCode' : 'SYZT-SYZ',
								'isDel' : '0',
								'isScrap':'0'
							},
							event : {
								row_dblclick : (function(rowNum) {
									return function(e, row, rowData) {
										var $cmps = g.getCmpByCol('assetCode');
										var isReturn = false;
										$cmps.each(function() {
											if ($(this).val() == rowData.assetCode) {
												alert("�벻Ҫѡ����ͬ���ʲ�.");
												isReturn = true;
											}
										});
										if (!isReturn) {
											var $assetName = g
													.getCmpByRowAndCol(rowNum,
															'assetName');
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
			}
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				if (!hasSource) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_asset({
						hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
						gridOptions : {
							param : {
								'useStatusCode' : 'SYZT-SYZ',
								'isDel' : '0',
								'isScrap':'0'
							},
							event : {
								row_dblclick : (function(rowNum) {
									return function(e, row, rowData) {
										var $cmps = g.getCmpByCol('assetCode');
										var isReturn = false;
										$cmps.each(function() {
											if ($(this).val() == rowData.assetCode) {
												alert("�벻Ҫѡ����ͬ���ʲ�.");
												isReturn = true;
											}
										});
										if (!isReturn) {
											var $assetCode = g
													.getCmpByRowAndCol(rowNum,
															'assetCode');
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
			}
		},
				// {
				// display:'sequence',
				// name : 'sequence',
				// type:'hidden'
				// },
				{
					display : '�ʲ�Id',
					name : 'assetId',
					type : 'hidden'
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
				}, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}]

	});

	// �黹���ͣ�����ѡ��ͬ�Ĺ黹���ͳ��ֶ�Ӧ�����ͱ��
	// $(function() {
	// if ($("#returnType").val() == "0") {
	// $('.nullType').hide();
	// }
	// })
	// ѡ����Ա���
	$("#returnMan").yxselect_user({
		hiddenId : 'returnManId',
		isGetDept : [true, "deptId", "deptName"]
	});

	$(function() {
		$("#purchaseProductTable").yxeditgrid("removeAll");
		if ($("#returnTypeVal").val() == "other") {
			hasSource = false;
			$("#borrowNo").yxcombogrid_charge("remove");
			$("#borrowNo").yxcombogrid_borrow("remove");
			$('.nullType').hide();
			$("#purchaseProductTable").yxeditgrid("addRow", 1, {});
			$("#purchaseProductTable").yxeditgrid("showAddBn");
		}
		if ($("#returnTypeVal").val() == "oa_asset_borrow") {
			hasSource = true;
			borrowFun();
			$('#borrowType').show();
			$('#chargeType').hide();
			$('#relNo').show();
			$("#purchaseProductTable").yxeditgrid("hideAddBn");
		}
		if ($("#returnTypeVal").val() == "oa_asset_charge") {
			hasSource = true;
			chargeFun();
			$('#chargeType').show();
			$('#borrowType').hide();
			$('#relNo').show();
			$("#purchaseProductTable").yxeditgrid("hideAddBn");
		}
	});

	$('#returnType').change(function() {
		$("#purchaseProductTable").yxeditgrid("removeAll");

		if ($("#returnType").val() == "other") {
			clearFun();
			hasSource = false;
			$("#borrowNo").yxcombogrid_charge("remove");
			$("#borrowNo").yxcombogrid_borrow("remove");
			$('.nullType').hide();
			$("#purchaseProductTable").yxeditgrid("addRow", 1, {});
			$("#purchaseProductTable").yxeditgrid("showAddBn");
		}
		if ($("#returnType").val() == "oa_asset_borrow") {
			clearFun();
			$('.nullType').hide();
			hasSource = true;
			borrowFun();
			$('#borrowType').show();
			$('#chargeType').hide();
			$('#relNo').show();
			$("#purchaseProductTable").yxeditgrid("hideAddBn");
		}
		if ($("#returnType").val() == "oa_asset_charge") {
			clearFun();
			$('.nullType').hide();
			hasSource = true;
			chargeFun();
			$('#chargeType').show();
			$('#borrowType').hide();
			$('#relNo').show();
			$("#purchaseProductTable").yxeditgrid("hideAddBn");
		}
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"billNo" : {
			required : true
		}
	});

	function clearFun() {
		$('#borrowId').val("");
		$('#borrowNo').val("");
	}
	// ѡ����õ�id�����ɹ�������ϸ��Ϣ
	function borrowFun() {
		$("#borrowNo").yxcombogrid_charge("remove")
		$("#borrowNo").yxcombogrid_borrow({
			hiddenId : 'borrowId',
			gridOptions : {
				param : {
					'unDocStatus' : 'YGH',
					'ExaStatus':'���'
				},
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳�� ����DESC ����ASC
				sortorder : "DESC",
				showcheckbox : false,
				event : {
					row_dblclick : function(t, row, rowData) {
						$("#purchaseProductTable").yxeditgrid("removeAll");// ���Ƴ�������
						$.ajax({
							type : "POST",
							async : false,
							url : "?model=asset_daily_borrowitem&action=listpageJson&borrowId="
									+ rowData.id,
							dataType : 'json',
							success : function(result) {
								for (var i = 0; i < result.length; i++) {
									var rowData = {
										assetCode : result[i].assetCode,
										assetName : result[i].assetName,
										spec : result[i].spec,
										buyDate : result[i].buyDate,
										estimateDay : result[i].estimateDay,
										alreadyDay : result[i].alreadyDay,
										residueYears : result[i].estimateDay
												- result[i].alreadyDay
									};
									$("#purchaseProductTable").yxeditgrid(
											"addRow", i, rowData);
								}
							}
						})

					}
				}
			}
		});
	}
	// ѡ�����õ�id�����ɹ�������ϸ��Ϣ
	function chargeFun() {
		$('#borrowNo').yxcombogrid_borrow("remove");
		$("#borrowNo").yxcombogrid_charge({
			hiddenId : 'borrowId',
			gridOptions : {
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳�� ����DESC ����ASC
				sortorder : "DESC",
				//��������,ֻ��ʾ��ǩ�յ����õ�
				param : {
					'unDocStatus' : 'YGH',
					'ExaStatus':'���',
					'isSign':'��ǩ��'
				},
				showcheckbox : false,
				event : {
					row_dblclick : function(t, row, rowData) {
						$("#purchaseProductTable").yxeditgrid("removeAll");// ���Ƴ�������
						$.ajax({
							type : "POST",
							async : false,
							url : "?model=asset_daily_chargeitem&action=listpageJson&allocateID="
									+ rowData.id,
							dataType : 'json',
							success : function(result) {
								for (var i = 0; i < result.length; i++) {
									var rowData = {
										assetCode : result[i].assetCode,
										assetName : result[i].assetName,
										spec : result[i].spec,
										buyDate : result[i].buyDate,
										estimateDay : result[i].estimateDay,
										alreadyDay : result[i].alreadyDay,
										residueYears : result[i].estimateDay
												- result[i].alreadyDay
									};
									$("#purchaseProductTable").yxeditgrid(
											"addRow", i, rowData);
								}
							}
						})

					}
				}
			}
		});
	}

});
/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_daily_return&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
