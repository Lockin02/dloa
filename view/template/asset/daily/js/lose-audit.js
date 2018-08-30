$(document).ready(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName : 'lose[item]',
		url : '?model=asset_daily_loseitem&action=listJson',
		type : 'view',
		param : {
			loseId : $("#loseId").val(),
			assetId : $("#assetId").val()
		},
		colModel : [{
			display : '��Ƭ���',
			name : 'assetCode'
		}, {
			display : '�ʲ�����',
			name : 'assetName'
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
			display : '��������',// orgName
			name : 'orgName',
			tclass : 'txt',
			readonly : true
		}, {
			display : 'ʹ�ò���',// useOrgName
			name : 'useOrgName',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '�����豸',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&showBtn=0&assetCode='
						+ data.assetCode + '\')">��ϸ</a>'
			}
		}, {
			display : '����ԭֵ',
			name : 'origina',
			tclass : 'txt',
			readonly : true
		}, {
			display : '�Ѿ�ʹ���ڼ���',
			name : 'alreadyDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '�ۼ��۾ɽ��',
			name : 'depreciation',
			tclass : 'txtmiddle',
			readonly : true,
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '��ֵ',
			name : 'salvage',
			tclass : 'txtmiddle',
			readonly : true,
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	})

	// �ύ������鿴����ʱ���عرհ�ť
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
	}

	var appendHtml=' <input type="hidden" id="id" name="lose[id]" value="'+$('#loseId').val()+'"/>'
				+ ' <input type="hidden" id="realAmount" name="lose[realAmount]" value="'+$("#realAmount").val()+'"/>';
	if($(window.parent.document.getElementById("appendHtml")).html()!=""){   //����ѡ����Ȱ�ǰһ��׷�ӵ��������
		$(window.parent.document.getElementById("appendHtml")).html("");
	}
	$(window.parent.document.getElementById("appendHtml")).append(appendHtml);

	$(window.parent.document.getElementById("sub")).bind("click", function() { // �����ύʱ���ж��Ƿ�ָ���˹�Ӧ��
				var pattern=/^[0-9]*(\.[0-9]{1,2})?$/;
				var realAmount = $('#realAmount').val();
				if (realAmount == "" || !pattern.test(realAmount)){
					alert('��������ȷ�Ľ��');
					return false;
				}
			});
});
	/*��ʧ����ʱ����ӱ�ע��������*/
	function addRemark(){
		var realAmount=$("#realAmount").val();
		if($(window.parent.document.getElementById("realAmount")).length>0){
			$(window.parent.document.getElementById("realAmount")).val(realAmount);
		}else{
			var realAmountHtml='<input type="hidden" id="realAmount" name="lose[realAmount]" value="'+realAmount+'"/>';
			$(window.parent.document.getElementById("realAmount")).append(realAmountHtml);
		}
	}
