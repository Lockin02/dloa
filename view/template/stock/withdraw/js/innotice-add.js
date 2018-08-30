$(document).ready(function() {

    $("#consignee").yxselect_user({
		hiddenId : 'consigneeId'
	});
    $("#auditman").yxselect_user({
		hiddenId : 'auditmanId',
		formCode : 'shipAdd'
	});

	$("#linkman").yxcombogrid_linkman({
		hiddenId : 'linkmanId',
		isFocusoutCheck : false,
		gridOptions : {
			reload : true,
			showcheckbox : false,
			param : {'customerId' : $('#customerId').val()},
			// param : param,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#mobil").val(data.phone);
				}
			}
		}
	});
	getWithdrawEqu();

	/**
	 * 验证信息
	 */
	validate({
		"receiveDate" : {
			required : true
		},
		"mailCode" : {
			required : true
		},
		"consignee" : {
			required : true
		},
		"auditman" : {
			required : true
		}
	});
})

function getWithdrawEqu(url) {
	var param = {
    	'mainId' : $("#drawId").val()
    },
	url = url ? url : '?model=stock_withdraw_equ&action=listJsonInnotice';// 产品下物料数据源
	$("#itemTable").yxeditgrid({
		objName : 'innotice[items]',
		title : '物料清单',
		isAdd : false,
		url : url,
		param : param,
		event : {
			reloadData : function(event, g,data) {
				if(!data || data.length == 0){
					alert('没有可下达的数量');
					closeFun();
				}
			}
		},
		colModel : [{
			display : '源单清单id',
			name : 'planEquId'
			,type : 'hidden'
			,process : function($input, rowData) {
				$input.val(rowData.id);
			}
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			name : 'productCode',
			tclass : 'readOnlyTxtItem',
			display : '物料编号',
			validation : {
				required : true
			},
			readonly:true
		},{
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			display : '物料名称',
			validation : {
				required : true
			},
			readonly:true
		},{
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			display : '型号/版本',
			readonly:true
		},{
			name : 'stockName',
			tclass : 'txtmiddle',
			display : '收料仓库',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_stockinfo({
					hiddenId : 'withdrawequ_cmp_stockId' + rowNum,
					nameCol : 'stockName',
					gridOptions : {
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $stockId = g.getCmpByRowAndCol(rowNum, 'stockId');
									var $stockCode = g.getCmpByRowAndCol(rowNum, 'stockCode');
									$stockId.val(rowData.id);
									$stockCode.val(rowData.stockCode);
								}
							})(rowNum)
						}
					}
				});
			},
			readonly:true
		},{
			name : 'stockId',
			type : 'hidden',
			display : '收料仓库id'
		},{
			name : 'stockCode',
			type : 'hidden',
			display : '收料仓库code'
		},{
			name : 'docNumber',
			tclass : 'readOnlyTxtItem',
			display : '源单数量',
			readonly:true
		},{
			name : 'number',
			tclass : 'txtshort',
			display : '数量'
		}]
	})
}