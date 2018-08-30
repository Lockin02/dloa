$(document).ready(function() {
		/**
	 * 验证信息
	 */
	validate({
//		"amounts" : {
//			required : true
//		},
		"agencyName" : {
			required : true
		}
	});
		//单选区域
		$("#agencyName").yxcombogrid_agency({
			hiddenId : 'agencyCode',
			width:400,
			gridOptions : {
				showcheckbox : false
			}
		});

	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			applyId : $("#applyId").val()
		},
		colModel : [{
			display : '采购部门',
			name : 'purchDept'
			,process : function(v){
				if(v=='1'){
					return '交付部';
				}else {
					return '行政部';
				}
			}
		},{
			display : '物料名称',
			name : 'inputProductName'
		}, {
			display : '规格',
			name : 'pattem'
		}, {
			display : '申请数量',
			name : 'applyAmount',
			tclass : 'txtshort'
		}, {
			display : '单位',
			name : 'unitName',
			tclass : 'txtshort'
		}, {
			display : '希望交货日期',
			name : 'dateHope',
			type : 'date'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	})

	// 根据采购类型来判断是否显示部分的字段
//	 alert($("#purchaseType").text());
	if ($("#purchaseType").text() != "计划内 ") {
		$("#hiddenA").hide();
		// $("#hiddenB").hide();
	}

	// 根据采购种类为“研发类”时来显示部分字段（采购分类、重大专项名称、募集资金项目、其它研发项目）
//	 alert($("#purchCategory").text());
	if ($("#purchCategory").text() == "研发类") {
		$("#hiddenC").hide();
	} else {
		$("#hiddenD").hide();
		$("#hiddenE").hide();
	}
	// 判断是否显示关闭按钮
	// alert($("#showBtn").val());
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
		$("#hiddenF").hide();
	}
});