//初始化从表信息
$(document).ready(function() {
    var sourceType=$("#sourceType").val();
    if(sourceType=="YFQTYD03"){
        $(".finalAmount").removeAttr('style');
    }

	$("#innerTable").yxeditgrid({
		url: '?model=finance_invother_invotherdetail&action=listJson',
		objName: 'invother[items]',
		title: '发票明细',
		param: {mainId: $("#id").val()},
		tableClass: 'form_in_table',
		type: 'view',
		colModel: [{
			display: '发票名目',
			name: 'productName'
		}, {
			display: '数量',
			name: 'number',
			tclass: 'txtshort'
		}, {
			display: '单价',
			name: 'price',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 130
		}, {
			display: '含税单价',
			name: 'taxPrice',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 130
		}, {
			display: '金额',
			name: 'amount',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 130
		}, {
			display: '税额',
			name: 'assessment',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 130
		}, {
			display: '价税合计',
			name: 'allCount',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 130
		}, {
			display: '源单编号',
			name: 'objCode',
			width: 200,
			process: function(v, row) {
				if (v) {
					return "<a href='javascript:void(0)' onclick='openSource(\"" + row.objId + "\",\"" + row.objType + "\")'>" + v + "</a>";
				}
			}
		}],
		event: {
			reloadData: function() {
				$("#innerTable tbody").append("<tr class='tr_count'><td colspan='2'>合计</td>"
				+ "<td>"
				+ $("#formNumber").val()
				+ "<td colspan='2'></td>"
				+ "<td>"
				+ moneyFormat2($("#amount").val())
				+ "</td>"
				+ "<td>"
				+ moneyFormat2($("#formAssessment").val())
				+ "</td>"
				+ "<td>"
				+ moneyFormat2($("#formCount").val())
				+ "</td>"
				+ "<td></td></tr>");
			}
		}
	});

	//其他类才加载费用分摊信息
	var sourceType = $("#sourceType").val();
	if (sourceType == "YFQTYD02" || sourceType == '其他合同') {
		//显示费用分摊明细
		$("#shareGrid").costShareGrid({
			url: "?model=finance_cost_costshare&action=hookJson",
			param: {objType: 2, hookId: $("#id").val()},
			type: 'view',
			isShowCountRow: true,
			event: {
				reloadData: function(e, g, data) {
					if (data) {
						$("#payDetailTr").show();
						g.costShareMoneyView(data);
					}
				}
			}
		});
	}
});

//查看源单
function openSource(objId, objType) {
	if (objType == 'YFQTYD01') {
		var skey = "";
		$.ajax({
			type: "POST",
			url: "?model=contract_outsourcing_outsourcing&action=md5RowAjax",
			data: {id: objId},
			async: false,
			success: function(data) {
				skey = data;
			}
		});
		showModalWin("?model=contract_outsourcing_outsourcing&action=viewTab&id=" + objId + "&skey=" + skey, 1);
	} else if (objType == 'YFQTYD02') {
		var skey = "";
		$.ajax({
			type: "POST",
			url: "?model=contract_other_other&action=md5RowAjax",
			data: {id: objId},
			async: false,
			success: function(data) {
				skey = data;
			}
		});
		showModalWin("?model=contract_other_other&action=viewTab&id=" + objId + "&skey=" + skey, 1);
	} else if (objType == 'YFQTYD03') {
		var skey = "";
		$.ajax({
			type: "POST",
			url: "?model=outsourcing_contract_rentcar&action=md5RowAjax",
			data: {id: objId},
			async: false,
			success: function(data) {
				skey = data;
			}
		});
		showModalWin("?model=outsourcing_contract_rentcar&action=viewTab&id=" + objId + "&skey=" + skey, 1);
	} else {
		alert('未配置的源单类型，请联系管理员');
	}
}