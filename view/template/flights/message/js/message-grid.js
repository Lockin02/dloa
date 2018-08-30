var show_page = function(page) {
	$("#messageGrid").yxgrid("reload");
};
$(function() {
	var auditState = $("#auditState").val();

	//��ͷ��������
	var buttonsArr = [];
	if(auditState == "0"){
		buttonsArr.push({
			icon : 'edit',
			text : '�˵�',
			action : function(row, rows, rowIds, g) {
				if (row) {
					// �ж������Ƿ��й�ѡ����
					var idArr = [];
					for(var i=0;i<rows.length;i++){
						if (rows[i].auditState != '0'){
							alert( "����/����š�" + rows[i].flightNumber + "�����ܽ��к˵�����");
							return false;
						}
						//���������ϸid
						idArr.push(rows[i].id);
					}

					if (confirm("ȷ�Ͻ��к˵�������")){
						$.post(
							"?model=flights_message_message&action=confirm",
							{ id: idArr.toString() },
							function (data){
								if (data=='1'){
									alert("�˵��ɹ�");
									show_page();
								}
							}
						);
					}
				}else{
					alert("��ѡ��һ������");
				}
			}
		});
	}else if(auditState == "1"){
		buttonsArr.push({
			icon : 'edit',
			text : '���˵�',
			action : function(row, rows, rowIds, g) {
				if (row) {
					// �ж������Ƿ��й�ѡ����
					var idArr = [];
					for(var i=0;i<rows.length;i++){
						if (rows[i].auditState != '1'){
							alert( "����/����š�" + rows[i].flightNumber + "�����ܽ��з��˵�����");
							return false;
						}
						//���������ϸid
						idArr.push(rows[i].id);
					}

					if (confirm("ȷ�Ͻ��з��˵�������")){
						$.post(
							"?model=flights_message_message&action=unconfirm",
							{ id: idArr.toString() },
							function (data){
								if (data=='1'){
									alert("���˵��ɹ�");
									show_page();
								}
							}
						);
					}
				}else{
					alert("��ѡ��һ������");
				}
			}
		});
		buttonsArr.push({
			icon: 'view',
			text: '���ɽ��㵥',
			action: function(row, rows, rowIds, g) {
				if (row) {
					var idArr = [];
					for(var i=0;i<rows.length;i++){
						if (rows[i].auditState!='1'){
							alert( "����/����š�" + rows[i].flightNumber + "�����ܽ������ɽ��㵥����");
							return false;
						}
						//���������ϸid
						idArr.push(rows[i].id);
					}
					showModalWin('?model=flights_balance_balance&action=toAddBatch&msgId=' + idArr.toString(),1,'batch');
				}else{
					alert("��ѡ��һ������");
				}
			}
		});
	}

	$("#messageGrid").yxgrid( {
		model : 'flights_message_message',
		title : '��Ʊ��Ϣ',
		param : {'auditState' : auditState},
		// ����Ϣ
		isOpButton : false,
		isDelAction : false,
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
		toAddConfig : {
			toAddFn : function(p) {
				showModalWin("index1.php?model=flights_message_message&action=toAdd");
			}
		},
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if(row.auditState == '0' && row.businessState == "0" ){
					return true;
				}
				return false;
			},
			formWidth : 900,
			formHeight : 500
		},
		toViewConfig : {
			action : 'toView',
			formWidth : 900,
			formHeight : 500
		},
		buttonsEx : buttonsArr,
		menusEx:[{// ���Ҽ���Ŀ���һ������ͼ��
			icon:'edit',
			text:'��ǩ��Ʊ',
			showMenuFn : function(row) {
				if(row.businessState == "0" || row.businessState == "3"){
					return true;
				}
				return false;
			},
			action: function(rowData) {
				showThickboxWin('?model=flights_message_message&action=toChange&id='+rowData.id
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850');
			}
		},
		{// ���Ҽ���Ŀ���һ������ͼ��
			icon:'edit',
			text:'�޸ĸ�ǩ',
			showMenuFn : function(row) {
				if(row.businessState == "3" && row.auditState == "0"){
					return true;
				}
				return false;
			},
			action: function(rowData) {
				showThickboxWin('?model=flights_message_message&action=toEditChange&id='+ rowData.id
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850');
			}
		},
		{// ���Ҽ���Ŀ���һ������ͼ��
			icon:'delete',
			text:'�˻���Ʊ',
			showMenuFn : function(row) {
				if(row.businessState == "0" || row.businessState == "3"){
					return true;
				}
				return false;
			},
			action: function(rowData) {
				showThickboxWin('?model=flights_message_message&action=toBack&id='+ rowData.id
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=850');
			}
		},
		{// ���Ҽ���Ŀ���һ������ͼ��
			icon:'edit',
			text:'�޸���Ʊ',
			showMenuFn : function(row) {
				if(row.businessState == "4" && row.auditState == "0"){
					return true;
				}
				return false;
			},
			action: function(rowData) {
				showThickboxWin('?model=flights_message_message&action=toEditBack&id='+ rowData.id
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=850');
			}
		},
		{// ���Ҽ���Ŀ���һ������ͼ��
			icon:'delete',
			text:'ɾ��',
			showMenuFn : function(row) {
				if(row.auditState == '0' && row.businessState == "0" ){
					return true;
				}
				return false;
			},
			action: function(rowData) {
				if(confirm('ȷ��ɾ����')){
					$.post('?model=flights_message_message&action=ajaxdeletes',{id:rowData.id},function (data){
						if (data=='1'){
							alert('ɾ���ɹ�');
							show_page();
						}
					});
				}
			}
		}],
		comboEx : [ {
//			text : '�˵�״̬',
//			key : 'auditState',
//			data : [ {
//				text : 'δ�˶�',
//				value : '0'
//			}, {
//				text : '�Ѻ˶�',
//				value : '1'
//			}, {
//				text : '�ѽ���',
//				value : '2'
//			} ]
//		},{
			text : 'ҵ��״̬',
			key : 'businessState',
			data : [ {
				text : '����',
				value : '0'
			}, {
				text : '��ǩ',
				value : '1'
			}, {
				text : '��Ʊ',
				value : '2'
			} ]
		}],
		searchitems : [{
			display : "�˻���",
			name : 'airNameSearch'
		},{
			display : "�����",
			name : 'flightNumberSearch'
		},{
			display : "¼����",
			name : 'createNameSearch'
		}],
		sortname : "c.createTime"
	});
});