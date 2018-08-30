function editGridCreate(deptIdVal, agencyCode) {
	var param = {
		'useStatusCode' : 'SYZT-XZ',
		'isDel' : '0',
		'belongTo' : '0',
		'machineCodeSearch':'0',
		'isScrap' : '0'
	};
	if( deptIdVal!='' ){
		param.orgId=deptIdVal;
	}
	if( agencyCode!='' ){
		param.agencyCode=agencyCode;
	}

	/**
	 * 选择卡片后自动带出规格，原值等信息
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		g.getCmpByRowAndCol(rowNum, 'sequence').val(rowData.sequence);
		g.getCmpByRowAndCol(rowNum, 'assetId').val(rowData.id);
		g.getCmpByRowAndCol(rowNum, 'assetCode').val(rowData.assetCode);
		g.getCmpByRowAndCol(rowNum, 'assetName').val(rowData.assetName);
		g.getCmpByRowAndCol(rowNum, 'englishName').val(rowData.englishName);
		g.getCmpByRowAndCol(rowNum, 'buyDate').val(rowData.buyDate);
		g.getCmpByRowAndCol(rowNum, 'spec').val(rowData.spec);
		g.getCmpByRowAndCol(rowNum, 'estimateDay').val(rowData.estimateDay);
		g.getCmpByRowAndCol(rowNum, 'alreadyDay').val(rowData.alreadyDay);
		g.getCmpByRowAndCol(rowNum, 'depreciation').val(rowData.depreciation);
		g.getCmpByRowAndCol(rowNum, 'salvage').val(rowData.salvage);
		g.getCmpByRowAndCol(rowNum, 'beforeUse').val(rowData.beforeUse);
		g.getCmpByRowAndCol(rowNum, 'afterUse').val(rowData.afterUse);
		g.getCmpByRowAndCol(rowNum, 'beforePlace').val(rowData.beforePlace);
		g.getCmpByRowAndCol(rowNum, 'afterPlace').val(rowData.afterPlace);
		
		var $equip = g.getCmpByRowAndCol(rowNum, 'equip');
		$equip.children().unbind("click");
		$equip.unbind("click");
		$equip.click((function(assetCode) {
			return function() {
				window.open('?model=asset_assetcard_equip&action=toPage&assetCode=' + assetCode);
			}
		})(rowData.assetCode));

	}
	$("#allocationTable").yxeditgrid({
		objName : 'allocation[allocationitem]',
		title : '卡片信息',
		isAddOneRow : false,
		colModel : [{
//			display : 'id',
//			name : 'id',
//			type : 'hidden'
//		}, {
			display : 'sequence',
			name : 'sequence',
			type : 'hidden'
		}, {
			display : '卡片编号',
			name : 'assetCode',
			tclass : 'txt',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'allocationTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : param,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.assetCode) {
											alert("请不要选择相同的资产.");
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
			display : '资产名称',
			name : 'assetName',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'allocationTable_cmp_assetId' + rowNum,
					gridOptions : {
						param : param,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.assetCode) {
											alert("请不要选择相同的资产.");
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
			display : '资产Id',
			name : 'assetId',
			type : 'hidden'
		}, {
//			display : '使用状态',
//			validation : {
//				required : true
//			},
//			name : 'useStatus',
//			tclass : 'txtshort',
//			type : 'select',
//			datacode : 'SYZT',
//			processData : function(data) {
//				var newData = [{
//					dataName : '',
//					dataCode : ''
//				}];
//				for (var i = 0; i < data.length; i++) {
//					newData.push(data[i]);
//				}
//				return newData;
//			}
//		}, {
			display : '英文名称',
			name : 'englishName',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true
		}, {
			display : '购置日期',
			name : 'buyDate',
			// type:'date',
			type : 'hidden',
			readonly : true
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '附属设备',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				if (data) {
					var $href = $("<a>详细</a>");
					$href.attr("href", "#");
					$href.click(function() {
						window
								.open('?model=asset_assetcard_equip&action=toPage&assetCode='
										+ data.assetCode);
					})
					return $href;
				} else {
					return '<a href="#" >详细</a>';
				}
			}

		}, {
			display : '耐用年限',
			name : 'estimateDay',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true
		}, {
			display : '已经使用期间数',
			name : 'alreadyDay',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true
		},
		// {
		// display : '月折旧额',
		// name : 'monthDepr',
		// tclass : 'txtshort',
		// readonly : true
		// },
		{
			display : '已折旧金额',
			name : 'depreciation',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true
		}, {
			display : '残余价值',
			name : 'salvage',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true
		}, {
			display : '调出前用途',
			name : 'beforeUse',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '调入后用途',
			name : 'afterUse',
			tclass : 'txtshort'

		}, {
			display : '调出前存放地点',
			name : 'beforePlace',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '调入后存放地点',
			name : 'afterPlace',
			tclass : 'txtshort'

		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	//加载选择卡片信息按钮
	$("#allocationTable").find("tr:first td").append("<input type='button' value='选择卡片信息' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
}
$(function() {
	// 选择调拨申请人组件
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
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
	// 选择调入确认人组件
	$("#recipient").yxselect_user({
		hiddenId : 'recipientId',
		mode : 'single'
	});
	/**
	 * 验证信息
	 */
	validate({
//		"billNo" : {
//			required : true
//		},
		"moveDate" : {
			required : true
		},
		"outDeptName" : {
			required : true
		},
		"inDeptName" : {
			required : true
		},
//		"outProName" : {
//			required : true
//		},
//		"inProName" : {
//			required : true
//		},
		"proposer" : {
			required : true
		},
		"recipient" : {
			required : true
		}
	});

});
function indeptAddSelect() {
	// 选择调入部门组件
	$("#inDeptName").yxselect_dept({
		hiddenId : 'inDeptId',
		mode : 'single'
	});
}
function outdeptAddSelect() {
	$("#allocationTable").yxeditgrid("remove");
	// 选择调出部门组件
	$("#outDeptName").yxselect_dept({
		hiddenId : 'outDeptId',
		mode : 'single',
		event : {
			selectReturn : function(e, returnValue) {
				$("#allocationTable").yxeditgrid("remove");
				editGridCreate($("#outDeptId").val(), '');
			}
		}
	});
}

function inAgencyAddSelect() {
	// 选择调出部门组件
	$("#inAgencyName").yxcombogrid_agency({
		hiddenId : 'inAgencyCode'
	});
}

function outAgencyAddSelect() {
	$("#allocationTable").yxeditgrid("remove");
	// 选择调出部门组件
	$("#outAgencyName").yxcombogrid_agency({
		hiddenId : 'outAgencyCode',
		gridOptions : {
			showcheckbox : false
			,event : {
				'row_dblclick' : function(e, row, data) {
				$("#allocationTable").yxeditgrid("remove");
				editGridCreate('', data.agencyCode);
				}
			}
		}
	});
}
/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_daily_allocation&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}

$(function() {
	indeptAddSelect();
	outdeptAddSelect();
	editGridCreate($('#outDeptId').val());
//	$("#inProName").yxcombogrid_esmproject({
//		hiddenId : 'inProId',
//		gridOptions : {
//			param : {
//				'ExaStatus' : '完成'
//			},
//			showcheckbox : false,
//			event : {
//				'row_dblclick' : function(e, row, data) {
//					$("#inDeptId").val(data.depId);
//					$("#inDeptName").val(data.depName);
//				}
//			}
//		}
//	});
//	$("#outProName").yxcombogrid_esmproject({
//		hiddenId : 'outProId',
//		gridOptions : {
//			param : {
//				'ExaStatus' : '完成'
//			},
//			showcheckbox : false,
//			event : {
//				'row_dblclick' : function(e, row, data) {
//					$("#allocationTable").yxeditgrid("remove");
//					editGridCreate($("#outDeptId").val(), $("#outProId").val());
//					$("#outDeptId").val(data.depId);
//					$("#outDeptName").val(data.depName);
//				}
//			}
//		}
//	});
	$('#outAgencyType').removeClass("validate[required]")
	$('#inAgencyType').removeClass("validate[required]")
	$('.outAgencyType').hide();
	$('.inAgencyType').hide();
	$('#alloType').change(function() {
		if ($('#alloType').val() == 'DTD') {
			//显示区域
			$('.inDeptType').show();
			$('.outDeptType').show();
			//移除区域下拉列表
			$("#outAgencyName").yxcombogrid_agency("remove");
			$("#inAgencyName").yxcombogrid_agency("remove");
			//取消区域必填
			$('#outAgencyName').removeClass("validate[required]")
			$('#inAgencyName').removeClass("validate[required]")
			//设置部门必填
			$('#outDeptName').addClass("validate[required]")
			$('#inDeptName').addClass("validate[required]")
			//部门设置可写
			$('#outDeptName').removeClass("readOnlyTxtNormal").addClass("txt");
			$('#inDeptName').removeClass("readOnlyTxtNormal").addClass("txt");
			//加载组织机构组件
			indeptAddSelect();
			outdeptAddSelect();
			//隐藏区域并置空
			$('.inAgencyType > input').val("");
			$('.inAgencyType').hide();
			$('.outAgencyType > input').val("");
			$('.outAgencyType').hide();
		} else if ($('#alloType').val() == 'ATA') {
			//显示区域
			$('.inAgencyType').show();
			$('.outAgencyType').show();
			//取消部门必填
			$('#outDeptName').removeClass("validate[required]")
			$('#inDeptName').removeClass("validate[required]")
			//设置区域必填
			$('#outAgencyName').addClass("validate[required]")
			$('#inAgencyName').addClass("validate[required]")
			//设置组织机构不可写
			$('#outDeptName').removeClass("txt").addClass("readOnlyTxtNormal");
			$('#inDeptName').removeClass("txt").addClass("readOnlyTxtNormal");
			//移除组织机构组件
			$("#inDeptName").yxselect_dept("remove");
			$("#outDeptName").yxselect_dept("remove");
			//载入区域下拉列表
			inAgencyAddSelect();
			outAgencyAddSelect();
			//隐藏部门并置空
			$('.inDeptType > input').val("");
			$('.inDeptType').hide();
			$('.outDeptType > input').val("");
			$('.outDeptType').hide();
		}
	});
})
//选择卡片信息
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=allocation&agencyCode="
			+$("#outAgencyCode").val()
			+"&deptId="
			+$("#outDeptId").val(),1,500,900);
}
//设置卡片内容
function setDatas(rows){
	var objGrid = $("#allocationTable");
	for(var i = 0; i < rows.length ; i++){
		//判断卡片编码是否已存在
		var assetIdArr = objGrid.yxeditgrid("getCmpByCol","assetCode");
		var isExist = false;
		if(assetIdArr.length > 0){
			assetIdArr.each(function(){
				if(this.value == rows[i].assetCode){
					isExist = true;
					alert("请不要选择相同的资产" );
					return false;
				}
			});
		}
		//如果已经重复了，就不能继续选择
		if(isExist){
			return false;
		}
		//重新获取行数
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//新增行
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"sequence",rows[i].sequence);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetCode",rows[i].assetCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetName",rows[i].assetName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"englishName",rows[i].englishName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"buyDate",rows[i].buyDate);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"spec",rows[i].spec);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"estimateDay",rows[i].estimateDay);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"alreadyDay",rows[i].alreadyDay);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"depreciation",rows[i].depreciation);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"salvage",rows[i].salvage);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"beforeUse",rows[i].beforeUse);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"afterUse",rows[i].afterUse);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"beforePlace",rows[i].beforePlace);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"afterPlace",rows[i].afterPlace);
		
		var $equip = objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum, 'equip');
		$equip.children().unbind("click");
		$equip.unbind("click");
		$equip.click((function(assetCode) {
			return function() {
				window.open('?model=asset_assetcard_equip&action=toPage&assetCode=' + assetCode);
			}
		})(rows[i].assetCode));
	}
}