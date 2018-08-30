var show_page = function(page) {
	$("#messageGrid").yxgrid("reload");
};
$(function() {
	$("#messageGrid").yxgrid({
		model : 'flights_message_message',
		title : '��Ʊ��Ϣ',
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		showcheckbox : false,
		param : {
			requireId : $("#id").val()
		},
		//����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'msgType',
			display : '��������',
			width : 50,
			sortable : true,
			process : function(v, row) {
				switch(v){
					case "0" : return "<span style='color: green;'>����</span>";break;
					case "1" : return "<span style='color: red;'>��ǩ</span>";break;
					case "2" : return "<span style='color: gray;'>��Ʊ</span>";break;
					default : return "<span style='color: green;'>����</span>";
				}
			},
			hide : true
		}, {
			name : 'businessState',
			display : 'ҵ��״̬',
			width : 50,
			sortable : true,
			process : function(v, row) {
				switch(v){
					case "0" : return "<span style='color: green;'>����</span>";break;
					case "1" : return "<span style='color: red;'>�Ѹ�ǩ</span>";break;
					case "2" : return "<span style='color: gray;'>����Ʊ</span>";break;
					case "3" : return "<span style='color: red;'>��ǩ</span>";break;
					case "4" : return "<span style='color: gray;'>��Ʊ</span>";break;
					default : return "<span style='color: green;'>����</span>";
				}
			}
		}, {
			name : 'auditState',
			display : '�˵�״̬',
			sortable : true,
			width : 50,
			process : function(v, row) {
				if (v == "0" || v == "") {
					return "δ�˶�";
				} else if(v == '1') {
					return "�Ѻ˶�";
				} else if(v == '2'){
					return "�ѽ���";
				} else if(v == '3'){
					return "<span style='color: gray;'>�������</span>";
				}
			},
			hide : true
		}, {
			name : 'auditDate',
			display : '��������',
			sortable : true,
			width : 85
		}, {
			name : 'airName',
			display : '�˻���',
			sortable : true,
			width : 85
		}, {
			name : 'airline',
			display : '���չ�˾',
			sortable : true
		}, {
			name : 'flightNumber',
			display : '����/�����',
			sortable : true
		},{
			name : 'flightTime',
			display : '�˻�ʱ��',
			sortable : true,
			width : 130
		}, {
			name : 'arrivalTime',
			display : '����ʱ��',
			sortable : true,
			width : 130
		}, {
			name : 'departPlace',
			display : '�����ص�',
			sortable : true,
			width : 75
		}, {
			name : 'arrivalPlace',
			display : '����ص�',
			sortable : true,
			width : 75
		}, {
			name: 'ticketType',
			display: '��Ʊ����',
			sortable: true,
			hide : true,
			process: function(v) {
				if (v == "10") {
					return '����';
				} else if (v == "11") {
					return '����';
				} else if (v == "12") {
					return '����';
				}
			},
			width : 80
		},
		{
			name: 'startPlace',
			display: '��������',
			sortable: true,
			width : 80,
			hide : true
		},
		{
			name: 'middlePlace',
			display: '��ת����',
			sortable: true,
			width : 80,
			hide : true
		},
		{
			name: 'endPlace',
			display: '�������',
			sortable: true,
			width : 80,
			hide : true
		},
		{
			name: 'startDate',
			display: '��������',
			sortable: true,
			width : 80,
			hide : true
		},
		{
			name: 'twoDate',
			display: '�ڶ�����ת����',
			sortable: true,
			width : 85,
			hide : true
		},
		{
			name: 'comeDate',
			display: '����ʱ��',
			sortable: true,
			width : 80,
			hide : true
		}, {
			name : 'costPay',
			display : 'ʵ����֧��',
			sortable : true,
			width : 70,
			process : function(v){
				if(v*1 >= 0){
					return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
				}else{
					return "<span class='red'>"+ moneyFormat2(v) + "</span>";
				}
			}
		}, {
			name : 'actualCost',
			display : 'ʵ�ʶ�Ʊ��',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'beforeCost',
			display : 'ԭ��Ʊ��',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'costDiff',
			display : 'Ʊ�۲���',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'fullFare',
			display : 'Ʊ��۸�',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'constructionCost',
			display : '���������',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'fuelCcharge',
			display : 'ȼ�͸��ӷ�',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'serviceCharge',
			display : '�����',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'feeChange',
			display : '��ǩ������',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'feeBack',
			display : '��Ʊ������',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'costBack',
			display : '��Ʊ���',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='red'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'requireNo',
			display : '��Ʊ�����',
			sortable : true,
			width : 120
		}, {
			name : 'requireId',
			display : '��Ʊ�����ID',
			sortable : true,
			hide : true
		} , {
			name : 'createName',
			display : '¼����',
			sortable : true,
			width : 80
		}, {
			name : 'createTime',
			display : '¼��ʱ��',
			sortable : true,
			width : 120
		}, {
			name : 'isLow',
			display : '������ͼ�',
			sortable : true,
			process : function(v, row) {
				if (v == "1") {
					return "��";
				} else {
					return "<span class='red'>"+ "��" + "</span>";
				}
			},
			width : 60
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
            text: '�鿴',
            icon: 'view',
            action: function(row, rows) {
	            showThickboxWin("?model=flights_message_message&action=toView&id="
	            	+ row.id
	            	+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
            }
        }],
		searchitems : [{
			display : "�˻���",
			name : 'airNameSearch'
		},{
			display : "�����",
			name : 'flightNumberSearch'
		}]
	});
});