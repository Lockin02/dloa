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
	 * ��֤��Ϣ
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
    	'id' : $("#docId").val()
    },
	url = url ? url : '?model=produce_plan_produceplan&action=listJsonInnotice';
	$("#itemTable").yxeditgrid({
		objName : 'innotice[items]',
		title : '�����嵥',
		isAdd : false,
		url : url,
		param : param,
		event : {
			reloadData : function(event, g,data) {
				if(!data || data.length == 0){
					alert('û�п��´������');
					closeFun();
				}
			}
		},
		colModel : [{
			display : 'Դ���嵥id',
			name : 'planEquId',
			type : 'hidden',
			process : function($input, rowData) {
				$input.val(rowData.id);
			}
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			name : 'productCode',
			tclass : 'readOnlyTxtItem',
			display : '���ϱ��',
			validation : {
				required : true
			},
			readonly:true
		},{
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			display : '��������',
			validation : {
				required : true
			},
			readonly:true
		},{
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			display : '�ͺ�/�汾',
			readonly:true
		},{
			name : 'stockName',
			tclass : 'txtmiddle',
			display : '���ϲֿ�',
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
			display : '���ϲֿ�id'
		},{
			name : 'stockCode',
			type : 'hidden',
			display : '���ϲֿ�code'
		},{
			name : 'number',
			tclass : 'txtshort',
			display : '���´�����',
			event : {
				blur : function(e){
					var rownum = $(this).data('rowNum');// �ڼ���
					var grid = $(this).data('grid');// ������

					var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();
					if(!isNum($(this).val())){
						alert("�������벻�Ϸ�");
						$(this).focus().val(maxNum);
					}
					if($(this).val() *1 > maxNum *1){
						alert("�´��������ܴ���" + maxNum);
						$(this).focus().val(maxNum);
					}
				}
			}
		}, {
			display : '�������',
			name : 'maxNum',
			type : "hidden"
		}, {
			display : 'Դ������',
			name : 'docType',
			type : "hidden",
			process : function($input) {
				$input.val('oa_produce_plan');
			}
		}]
	})
}