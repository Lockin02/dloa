// �������������¼��ķ���
function xq_approval(){
    var itemType = $("#xq_itemType").val();
    var pid = $("#xq_pid").val();
    var relDocId = $("#xq_relDocId").val();
    var isChange = $("#isChange").val();
    var gdbtable = getQueryString('gdbtable');
    if ($("#isChange").length == 0) {//�Ƿ��������������
        var isChange = 0;
    } else {
        var isChange = $("#isChange").val();
    }
    if ($("#appFormName").length == 0) {
        var appFormName = "";
    } else {
        var appFormName = $("#appFormName").val();
    }
    if ($("#isPrint").length == 0) {//�Ƿ��������������
        var isPrint = 0;
    } else {
        var isPrint = $("#isPrint").val();
    }

    $.post("?model=common_approvalView&action=getXqApproval", {
        pid : pid,
        relDocId : relDocId,
        itemtype : itemType,
        isChange : isChange,
        gdbtable : gdbtable,
        isPrint : isPrint
    }, function(data) {
        if (data == 0) { //û���������ʱ����ֵΪ����
            var datahtml = "<tr><td></td></tr>";
        }
        if (data != 0) {
            if(isPrint == "1"){
                var $html = $('<table width="100%"  class="form_in_table" id="approvalTable">'
                    + '<thead>'
                    + '<tr  > '
                    + '<td width="100%" colspan="6" class="form_header"><B>'
                    + appFormName
                    + '����������¼</B></td>'
                    + '</tr>'
                    + '<tr class="main_tr_header">'
                    + '<th width="12%">������</th>'
                    + '<th width="12%">������</th>'
                    + '<th width="18%" nowrap="nowrap">��������</th>'
                    + '<th width="10%">�������</th>'
                    + '<th>�������</th>'
                    + '</tr>' + '</thead>');
            }else{
                var $html = $('<table width="100%"  class="form_in_table" id="approvalTable">'
                    + '<thead>'
                    + '<tr  > '
                    + '<td width="100%" colspan="6" class="form_header"><B>'
                    + appFormName
                    + '����������¼</B></td>'
                    + '</tr>'
                    + '<tr class="main_tr_header">'
                    + '<th width="12%">���</th>'
                    + '<th width="12%">������</th>'
                    + '<th width="12%">������</th>'
                    + '<th width="18%" nowrap="nowrap">��������</th>'
                    + '<th width="10%">�������</th>'
                    + '<th>�������</th>'
                    + '</tr>' + '</thead>');
            }
            var $html2 = $('</table>');
            if (data == 0) {
                var $tr = $(datahtml);
            } else {
                var $tr = $(data);
            }
            $html.append($tr);
            $html.append($html2);
            $("#xq_approvalView").append($html);
        }
    });
}

$(document).ready(function() {

	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			applyId : $("#applyId").val()
		},
		colModel : [{
			display : '��������',
			name : 'productName',
			type : 'statictext',
			process : function(v,row){
				if(v==''){
					return row.inputProductName;
				}else{
					return v
				}
			}
		}, {
			display : '���',
			name : 'pattem'
		}, {
			display : '��������',
			name : 'applyAmount',
			tclass : 'txtshort'
		}, {
			display : 'Ԥ�ƽ��',
			name : 'amounts',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'txtshort'
		}, {
			display : 'ϣ����������',
			name : 'dateHope',
			type : 'date'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	})

    // �������������¼
    if(typeof($("#extra_approvalItems").val()) != 'undefined' &&  $("#extra_approvalItems").val() == 'xq'){
        xq_approval();
    }

	// ���ݲɹ��������ж��Ƿ���ʾ���ֵ��ֶ�
//	 alert($("#purchaseType").text());
	if ($("#purchaseType").text() != "�ƻ��� ") {
		$("#hiddenA").hide();
		// $("#hiddenB").hide();
	}

	// ���ݲɹ�����Ϊ���з��ࡱʱ����ʾ�����ֶΣ��ɹ����ࡢ�ش�ר�����ơ�ļ���ʽ���Ŀ�������з���Ŀ��
//	 alert($("#purchCategory").text());
	if ($("#purchCategory").text() == "�з���") {
		$("#hiddenC").hide();
	} else {
		$("#hiddenD").hide();
		$("#hiddenE").hide();
	}
	// �ж��Ƿ���ʾ�رհ�ť
	// alert($("#showBtn").val());
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
	}
});