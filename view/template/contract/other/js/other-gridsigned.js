var show_page = function(page) {
	$("#otherGrid").yxgrid("reload");
};

$(function() {
	$("#otherGrid").yxgrid({
		model : 'contract_other_other',
		action : 'pageJsonFinanceInfo',
        param : { "signedStatus" : '1','statuses' : '2,3,4' },
		title : '��ǩ�յ�������ͬ',
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'otherGridSigned',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'createDate',
				display : '¼������',
				width : 70
			}, {
				name : 'fundTypeName',
				display : '��������',
				sortable : true,
				width : 70,
				process : function(v,row){
					if(row.fundType == 'KXXZB'){
						return '<span style="color:blue">' + v  +'</span>';
					}else if( row.fundType == 'KXXZA'){
						return '<span style="color:green">' + v  +'</span>';
					}else{
						return v;
					}
				}
			}, {
				name : 'orderCode',
				display : '������ͬ��',
				sortable : true,
				width : 130,
	            process : function(v,row){
					if(row.status == 4){
						return "<a href='#' style='color:red' title='����еĺ�ͬ' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id+ "&fundType=" + row.fundType + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}else{
						return "<a href='#' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id + "&fundType=" + row.fundType+ '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}
	            }
			}, {
				name : 'orderName',
				display : '��ͬ����',
				sortable : true,
				width : 130
			}, {
				name : 'signCompanyName',
				display : 'ǩԼ��˾',
				sortable : true,
				width : 150
			}, {
				name : 'proName',
				display : '��˾ʡ��',
				sortable : true,
				width : 70,
				hide : true
			}, {
				name : 'address',
				display : '��ϵ��ַ',
				sortable : true,
				hide : true
			}, {
				name : 'phone',
				display : '��ϵ�绰',
				sortable : true,
				hide : true
			}, {
				name : 'linkman',
				display : '��ϵ��',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'signDate',
				display : 'ǩԼ����',
				sortable : true,
				width : 80
			}, {
				name : 'orderMoney',
				display : '��ͬ�ܽ��',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'payApplyMoney',
				display : '���븶��',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB'){
						if(row.id == 'noId'){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							var thisTitle = '���г�ʼ���븶����Ϊ: ' + moneyFormat2(row.initPayMoney) + ',���ڸ���������Ϊ��' + moneyFormat2(row.countPayApplyMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80
			}, {
				name : 'payedMoney',
				display : '�Ѹ����',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB' ){
						if(row.id == 'noId'){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							var thisTitle = '���г�ʼ���븶����Ϊ: ' + moneyFormat2(row.initPayMoney) + ',���ڸ�����Ϊ��' + moneyFormat2(row.countPayMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80
			}, {
				name : 'returnMoney',
				display : '������',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB'){
						if(row.id == 'noId'){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'invotherMoney',
				display : '���շ�Ʊ',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB'){
						if(row.id == 'noId'){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 != 0){
							var thisTitle = '���г�ʼ������Ʊ���Ϊ: ' + moneyFormat2(row.initInvotherMoney) + ',������Ʊ���Ϊ��' + moneyFormat2(row.countInvotherMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'applyInvoice',
				display : '���뿪Ʊ',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA' ){
						if(row.id == 'noId'){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80,
				hide : true
			}, {
				name : 'invoiceMoney',
				display : '�ѿ���Ʊ',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA'){
						if(row.id == 'noId'){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80,
				hide : true
			}, {
				name : 'incomeMoney',
				display : '�տ���',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA'){
						if(row.id == 'noId'){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80,
				hide : true
			}, {
				name : 'principalName',
				display : '��ͬ������',
				sortable : true,
				hide : true
			}, {
				name : 'deptName',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				width : 60
				,
				process : function(v,row){
					if(v == '0'){
						return "δ�ύ";
					}else if(v == 1){
						return "������";
					}else if(v == 2){
						return "ִ����";
					}else if(v == 3){
						return "�ѹر�";
					}else if(v == 4){
						return "�����";
					}
				}
			},{
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true,
				width : 60
			},{
				name : 'objCode',
				display : 'ҵ����',
				sortable : true,
				width : 120,
				hide : true
			},{
	            name : 'isNeedStamp',
	            display : '���������',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(v=="0"){
						return "��";
					}else if( v== "1"){
						return "��";
					}
				},
				hide : true
	        },{
	            name : 'isStamp',
	            display : '�Ƿ��Ѹ���',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(v=="0"){
						return "��";
					}else if( v== "1"){
						return "��";
					}
	            }
	        },{
	            name : 'stampType',
	            display : '��������',
	            sortable : true,
	            width : 80,
				hide : true
	        },{
	            name : 'createName',
	            display : '������',
	            sortable : true,
				hide : true
	        },{
	            name : 'updateTime',
	            display : '����ʱ��',
	            sortable : true,
	            width : 130,
				hide : true
	        }],
		toAddConfig : {
			formWidth : 1000,
			formHeight : 500
		},
		toEditConfig : {
			formWidth : 1000,
			formHeight : 500,
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴��ͬ',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=contract_other_other&action=viewTab&id="
							+ row.id
							+ "&fundType="
							+ row.fundType
							+ "&skey=" + row.skey_
							);
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			text : 'ǩ�պ�ͬ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.status == '4') {
						alert('��ͬ���ڱ��״̬�����ܽ���ǩ�ղ���');
						return false;
					}
					showOpenWin("?model=contract_other_other&action=toSign&id="
						+ row.id
						+ "&skey=" + row.skey_
					);
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			name : 'file',
			text : '�ϴ�����',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=contract_other_other&action=toUploadFile&id="
					+ row.id
					+ "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
			}
		}],
		// �߼�����
		advSearchOptions : {
			modelName : 'otherGrid',
			// ѡ���ֶκ��������ֵ����
			selectFn : function($valInput) {
				$valInput.yxselect_user("remove");
				$valInput.yxcombogrid_signcompany("remove");
			},
			searchConfig : [{
				name : '������ͬ��',
				value : 'c.orderCode'
			},{
				name : 'ǩԼ����',
				value : 'c.signDate',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
							dateFmt : 'yyyy-MM-dd'
						});
					});
				}
			},{
				name : 'ǩԼ��˾',
				value : 'c.signCompanyName',
				changeFn : function($t, $valInput, rowNum) {
					if (!$("#signCompanyId" + rowNum)[0]) {
						$hiddenCmp = $("<input type='hidden' id='signCompanyId" + rowNum + "'/>");
						$valInput.after($hiddenCmp);
					}
					$valInput.yxcombogrid_signcompany({
						hiddenId : 'signCompanyId' + rowNum,
						height : 250,
						width : 550,
						isShowButton : false
					});
				}
			},{
				name : '��˾ʡ��',
				value : 'c.proName'
			},{
				name : '��ͬ���',
				value : 'c.orderMoney'
			},{
				name : '��������',
				value : 'c.fundType',
				type:'select',
	            datacode : 'KXXZ'
			}]
		},
		searchitems : [{
			display : '������',
			name : 'principalName'
		}, {
			display : 'ǩԼ��˾',
			name : 'signCompanyName'
		}, {
			display : '��ͬ����',
			name : 'orderName'
		}, {
			display : '��ͬ���',
			name : 'orderCodeSearch'
		},{
			display : 'ҵ����',
			name : 'objCodeSearch'
		}],
		// Ĭ�������ֶ���
		sortname : "c.createTime",
		// Ĭ������˳�� ����DESC ����ASC
		sortorder : "DESC",
		// ����״̬���ݹ���
		comboEx : [{
			text : "��������",
			key : 'fundType',
			datacode : 'KXXZ'
		},{
			text : '��ͬ״̬',
			key : 'status',
			data : [{
				text : 'δ�ύ',
				value : '0'
			},{
				text : '������',
				value : '1'
			}, {
				text : 'ִ����',
				value : '2'
			}, {
				text : '�ѹر�',
				value : '3'
			}, {
				text : '�����',
				value : '4'
			}]
		}]
	});
});