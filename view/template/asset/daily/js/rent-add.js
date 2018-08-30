$(function() {
	// /**
	// * ���Ψһ����֤
	// */
	//
	// var url = "?model=asset_daily_rent&action=checkRepeat";
	// $("#billNo").ajaxCheck({
	// url : url,
	// alertText : "* �ñ���Ѵ���",
	// alertTextOk : "* �ñ�ſ���"
	// });

	/**
	 * ѡ��Ƭ���Զ��������ԭֵ����Ϣ
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);

		var $buyDate = g.getCmpByRowAndCol(rowNum, 'buyDate');
		$buyDate.val(rowData.buyDate);

		var $unit = g.getCmpByRowAndCol(rowNum, 'unit');
		$unit.val(rowData.unit);

		var $origina = g.getCmpByRowAndCol(rowNum, 'origina');
		$origina.val(rowData.origina);

	}
	$("#rentTable").yxeditgrid({
		objName : 'rent[rentitem]',
		// url:'?model=asset_daily_borrowitem',
		colModel : [{
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
					hiddenId : 'rentTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
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
					hiddenId : 'rentTable_cmp_assetId' + rowNum,
					gridOptions : {
						param : {
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
			type : 'date',
			readonly : true
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : 'ԭֵ',
			name : 'origina',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '����۸�',
			name : 'rentValue',
			type : "money",
			tclass : 'txtshort'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}],
		data : [{},

		]
	});
	// ѡ����Ա�������
	$("#applicat").yxselect_user({
		hiddenId : 'applicatId',
		isGetDept : [true, "deptId", "deptName"],
		mode : 'single'
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"billNo" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"lesseeName" : {
			required : true
		},
		"lessee" : {
			required : true
		},
		"contractNo" : {
			required : true
		},
		"rentNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"rentAmount" : {
			required : true,
			custom : ['onlyNumber']
		},
		"reason" : {
			required : true
		},
		"applicat" : {
			required : true
		},
		"applicatDate" : {
			required : true
		},
		"beginDate" : {
			required : true
		},
		"endDate" : {
			required : true

		}
	});
	// �����ͻ����
	$("#lesseeName").yxcombogrid_customer({
		hiddenId : 'lesseeid',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});

});
