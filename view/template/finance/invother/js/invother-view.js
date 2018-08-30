//��ʼ���ӱ���Ϣ
$(document).ready(function() {
    var sourceType=$("#sourceType").val();
    if(sourceType=="YFQTYD03"){
        $(".finalAmount").removeAttr('style');
    }

	$("#innerTable").yxeditgrid({
		url: '?model=finance_invother_invotherdetail&action=listJson',
		objName: 'invother[items]',
		title: '��Ʊ��ϸ',
		param: {mainId: $("#id").val()},
		tableClass: 'form_in_table',
		type: 'view',
		colModel: [{
			display: '��Ʊ��Ŀ',
			name: 'productName'
		}, {
			display: '����',
			name: 'number',
			tclass: 'txtshort'
		}, {
			display: '����',
			name: 'price',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 130
		}, {
			display: '��˰����',
			name: 'taxPrice',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 130
		}, {
			display: '���',
			name: 'amount',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 130
		}, {
			display: '˰��',
			name: 'assessment',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 130
		}, {
			display: '��˰�ϼ�',
			name: 'allCount',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 130
		}, {
			display: 'Դ�����',
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
				$("#innerTable tbody").append("<tr class='tr_count'><td colspan='2'>�ϼ�</td>"
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

	//������ż��ط��÷�̯��Ϣ
	var sourceType = $("#sourceType").val();
	if (sourceType == "YFQTYD02" || sourceType == '������ͬ') {
		//��ʾ���÷�̯��ϸ
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

//�鿴Դ��
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
		alert('δ���õ�Դ�����ͣ�����ϵ����Ա');
	}
}