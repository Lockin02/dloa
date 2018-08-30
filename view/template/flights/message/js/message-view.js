//��ʼ��һЩ�ֶ�
var objName = 'message';
var initId = 'feeTbl_c';
var actionType = 'view';
var myUrl = '?model=flights_message_message&action=ajaxGet';

$(document).ready(function() {
	//������ʾ
	changeType($("#ticketTypeHidden").val());
	
	var itemTableObj = $("#itemTable");
	itemTableObj.yxeditgrid( {
		objName : 'message[items]',
		url : '?model=flights_message_messageitem&action=listJson',
		type : 'view',
		title : '��ǩ/��Ʊ��Ϣ',
        event: {
        	'reloadData': function(e) {
				var professionArr   = itemTableObj.yxeditgrid("getCmpByCol", "profession");
				if (professionArr.length == 0) {
					itemTableObj.hide();
				}
            }
        },
		param : {
			mainId : $("#id").val()
		},
		colModel : [ {
			name : 'profession',
			display : '����',
			process : function(v, row) {
				if (v == "1") {
					return "<span>��ǩ</span>";
				} else {
					return "<span style='color: red;' >��Ʊ</span>";
				}
			},
			width : 80
		}, {
			name : 'changeNum',
			display : '��ǩ�����',
			width : 120
		}, {
			name : 'startDate',
			display : '�˻�/��Ʊ����',
			width : 110
		}, {
			name : 'arriveDate',
			display : '��������',
			width : 100
		}, {
			name : 'changeCost',
			display : '��ǩ/��Ʊ������',
			width : 110,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'ticketSum',
			display : '��Ʊ���',
			width : 90,
			process : function(v){
				return moneyFormat2(v);
			}
		},{
			name : 'changeReason',
			display : '��ǩ/��Ʊԭ��',
			width : 220
		}]
	});
});
