//��ʼ���ӱ���Ϣ
$(document).ready(function() {
    var sourceType=$("#sourceType").val();
    if(sourceType=="YFQTYD03"){
        $(".finalAmount").removeAttr('style');
    }
	//������˾
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId: 'businessBelong',
		height: 250,
		isFocusoutCheck: false,
		gridOptions: {
			showcheckbox: false,
			event: {
				'row_dblclick': function(e, row, data) {
					//��ʼ�����ṹ
					initTree();
					//�������η�Χ
					reloadManager();
				}
			}
		}
	});

	$("#innerTable").yxeditgrid({
		url: '?model=finance_invother_invotherdetail&action=listJson',
		objName: 'invother[items]',
		title: '��Ʊ��ϸ',
		param: {'mainId': $("#id").val()},
		tableClass: 'form_in_table',
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '��Ʊ��Ŀ',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '��Ʊ��Ŀ����',
			name: 'productCode',
			type: 'hidden'
		}, {
			display: '��Ʊ��Ŀ',
			name: 'productName',
			validation: {
				required: true
			},
			tclass: 'txt'
		}, {
			display: '����',
			name: 'number',
			tclass: 'txtshort',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '����',
			name: 'price',
			type: 'money',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"), 'price');
				}
			}
		}, {
			display: '��˰����',
			name: 'taxPrice',
			type: 'money',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"), 'taxPrice');
				}
			}
		}, {
			display: '���',
			name: 'amount',
			validation: {
				required: true
			},
			type: 'money',
			event: {
				blur: function() {
					//��ʼ�����ۺ�����
					initNumAndPrice($(this).data("rowNum"), 'amount');
					countAccount($(this).data("rowNum"));
					countForm();
				}
			}
		}, {
			display: '˰��',
			name: 'assessment',
			type: 'money',
			event: {
				blur: function() {
					countAccount($(this).data("rowNum"));
					countForm();
				}
			}
		}, {
			display: '��˰�ϼ�',
			name: 'allCount',
			type: 'money',
			event: {
				blur: function() {
					//��ʼ�����ۺ�����
					initNumAndPrice($(this).data("rowNum"), 'allCount');
					countForm();
				}
			}
		}, {
			display: 'Դ�����',
			name: 'objCode',
			readonly: true,
			tclass: 'readOnlyTxtNormal'
		}, {
			display: 'Դ��id',
			name: 'objId',
			type: 'hidden'
		}, {
			display: 'Դ������',
			name: 'objType',
			type: 'hidden'
		}],
		event: {
			'reloadData': function() {
				$("#innerTable").find("tbody").after("<tr class='tr_count'><td colspan='3'>�ϼ�</td>"
				+ "<td><input type='text' class='readOnlyTxtShortCount' id='listNumber' value='"
				+ $("#formNumber").val()
				+ "' readonly='readonly'/></td>"
				+ "<td colspan='2'></td>"
				+ "<td><input type='text' class='readOnlyTxtMiddleCount' id='listAmount' value='"
				+ moneyFormat2($("#allAmount").val())
				+ "' readonly='readonly'/></td>"
				+ "<td><input type='text' class='readOnlyTxtMiddleCount' id='listAssessment' value='"
				+ moneyFormat2($("#formAssessment").val())
				+ "' readonly='readonly'/></td>"
				+ "<td><input type='text' class='readOnlyTxtMiddleCount' id='listCount' value='"
				+ moneyFormat2($("#formCount").val())
				+ "' readonly='readonly'/></td>"
				+ "<td></td></tr>");
			},
			removeRow: function(t, rowNum, rowData) {
				countForm();
			},
			clickAddRow: function (e, rowNum, g) {
				//����Դ����Ϣ
				if($('#sourceType').val() != ''){
					g.setRowColValue(rowNum, "objCode", $('#menuNo').val());
					g.setRowColValue(rowNum, "objId", $('#sourceId').val());
					g.setRowColValue(rowNum, "objType", $('#sourceType').val());
				}
	        }
		}
	});

	// ��ȡԴ������
	var sourceType = $("#sourceType").val();

	if (sourceType != "") {
		$("#invType").hide();
		$("#invTypeCN").show();

		$("#taxRate").attr('class', 'readOnlyTxtNormal').attr('readonly', 'readonly');
	} else {
		// ��Ʊ�¼���
		$("#invType").bind("change", invoiceTypeChange);

		// ˰��ʱ���
		$("#taxRate").bind('blur', countAll);
	}

	//update chenrf �����ͬ����Ҫ���÷�̯
	if ((sourceType == 'YFQTYD02' || sourceType == '������ͬ') && $("#isShare").val() == "1") {
		//��ʾ���÷�̯��ϸ
		$("#shareGrid").costShareGrid({
			objName: 'invother[costshare]',
			url: "?model=finance_cost_costshare&action=hookJson",
			type: "hook",
			showSelectWin: true,
			param: {objType: 2, hookId: $("#id").val()},
			isShowCountRow: true,
			countKey: getMoneyKey()
		});
	}
});