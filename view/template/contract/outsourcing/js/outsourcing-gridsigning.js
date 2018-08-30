var show_page = function(page) {
	$("#outsourcingGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();
    $("#outsourcingGrid").yxgrid({
        model : 'contract_outsourcing_outsourcing',
        param : { "signedStatus" : '0','statuses' : '2,3,4' },
        title : 'δǩ�յ������ͬ',
		isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		customCode : 'outsourcingSigningGrid',
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
			},{
	            name : 'orderCode',
	            display : '������ͬ���',
	            sortable : true,
	            width : 130,
	            process : function(v,row){
					if(row.status == 4){
						return "<a href='#' style='color:red' title='����еĺ�ͬ' onclick='window.open(\"?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}else{
						if(row.ExaStatus == '���ύ' || row.ExaStatus == '��������'){
							return "<a href='#' onclick='window.open(\"?model=contract_outsourcing_outsourcing&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						}else{
							return "<a href='#' onclick='window.open(\"?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						}
					}
	            }
	        },{
	            name : 'orderName',
	            display : '��ͬ����',
	            sortable : true,
	            width : 130
	        },{
	            name : 'outContractCode',
	            display : '�����ͬ��',
	            sortable : true,
	            width : 130
	        },{
	            name : 'signCompanyName',
	            display : 'ǩԼ��˾',
	            sortable : true,
	            width : 130
	        },{
	            name : 'signCompanyId',
	            display : 'ǩԼ��˾id',
	            sortable : true,
	            hide : true
	        },{
	            name : 'proName',
	            display : '��˾ʡ��',
	            sortable : true,
	            hide : true
	        },{
	            name : 'proCode',
	            display : 'ʡ�ݱ���',
	            sortable : true,
	            hide : true
	        },{
	            name : 'address',
	            display : '��ϵ��ַ',
	            sortable : true,
	            hide : true
	        },{
	            name : 'phone',
	            display : '��ϵ�绰',
	            sortable : true,
	            hide : true
	        },{
	            name : 'linkman',
	            display : '��ϵ��',
	            sortable : true,
	            hide : true
	        },{
	            name : 'signDate',
	            display : 'ǩԼ����',
	            sortable : true,
	            width : 80
	        },{
	            name : 'beginDate',
	            display : '��ʼ����',
	            sortable : true,
	            width : 80
	        },{
	            name : 'endDate',
	            display : '��������',
	            sortable : true,
	            width : 80
	        },{
	            name : 'orderMoney',
	            display : '��ͬ���',
	            sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
	            width : 80
	        },{
	            name : 'initPayMoney',
	            display : '��ʼ������',
	            sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
	            width : 80,
	            hide : true
	        },{
	            name : 'initInvoiceMoney',
	            display : '��ʼ��Ʊ���',
	            sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
	            width : 80,
	            hide : true
	        },{
	            name : 'allCount',
	            display : '���շ�Ʊ',
	            sortable : true,
				process : function(v,row){
					if(v*1 != 0){
						var thisTitle = '���г�ʼ������Ʊ���Ϊ: ' + moneyFormat2(row.initInvoiceMoney) + ',������Ʊ���Ϊ��' + moneyFormat2(row.orgAllCount);
						return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
					}else{
						return 0;
					}
				},
	            width : 80
	        },{
	            name : 'applyedMoney',
	            display : '�����븶��',
	            sortable : true,
				process : function(v,row){
					if(v*1 != 0){
						var thisTitle = '���г�ʼ���븶����Ϊ: ' + moneyFormat2(row.initPayMoney) + ',�������븶����Ϊ��' + moneyFormat2(row.orgApplyedMoney);
						return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
					}else{
						return 0;
					}
				},
	            width : 80
	        },{
	            name : 'payedMoney',
	            display : '����ϼ�',
	            sortable : true,
				process : function(v,row){
					if(v*1 != 0){
						var thisTitle = '���г�ʼ���븶����Ϊ: ' + moneyFormat2(row.initPayMoney) + ',���ڸ�����Ϊ��' + moneyFormat2(row.orgPayedMoney);
						return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
					}else{
						return 0;
					}
				},
	            width : 80
	        },{
	            name : 'payTypeName',
	            display : '���ʽ',
	            sortable : true,
	            width : 100
	        },{
	            name : 'outsourcingName',
	            display : '�����ʽ',
	            sortable : true,
	            width : 80
	        },{
	            name : 'outsourceTypeName',
	            display : '�������',
	            sortable : true,
	            width : 80
	        },{
	            name : 'projectCode',
	            display : '��Ŀ���',
	            sortable : true
	        },{
	            name : 'projectName',
	            display : '��Ŀ����',
	            sortable : true,
	            width : 130,
	            hide : true
	        },{
	            name : 'deptName',
	            display : '��������',
	            sortable : true,
	            width : 100
	        },{
	            name : 'principalName',
	            display : '��ͬ������',
	            sortable : true
	        }, {
				name : 'status',
				display : '״̬',
				sortable : true,
				width : 60
				,
				process : function(v,row){
					if(row.id == "noId"){
						return '';
					}
					if(v == 0){
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
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true,
	            width : 60
			},{
	            name : 'signedStatus',
	            display : '��ͬǩ��',
	            sortable : true,
	            process : function(v,row){
					if(row.id == "noId"){
						return '';
					}
					if(v=="1"){
						return "��ǩ��";
					}else{
						return "δǩ��";
					}
				},
	            width : 70
	        },{
	            name : 'objCode',
	            display : 'ҵ����',
	            sortable : true,
	            width : 110
	        },{
	            name : 'isNeedStamp',
	            display : '���������',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(row.id == "noId"){
						return '';
					}
					if(v=="1"){
						return "��";
					}else{
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
					if(row.id == "noId"){
						return '';
					}
					if(v == 1){
						return "��";
					}else{
						return "��";
					}
	            },
	            hide : true
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
					if(row.ExaStatus == '���ύ' || row.ExaStatus == '��������'){
						showModalWin("?model=contract_outsourcing_outsourcing&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ );
					}else{
						showModalWin("?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + row.skey_ );
					}
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
					showOpenWin("?model=contract_outsourcing_outsourcing&action=toSign&id="
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
				if (row.id == "noId") {
					return false;
				}
				if(row.status == 3){
					return false;
				}
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=contract_outsourcing_outsourcing&action=toUploadFile&id="
					+ row.id
					+ "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
			}
		},{
			text : '�޸ı�ע',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=contract_outsourcing_outsourcing&action=toUpdateInfo&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		// �߼�����
		advSearchOptions : {
			modelName : 'outsourcing',
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
				name : '�������',
				value : 'c.outsourceType',
				type:'select',
	            datacode : 'HTWB'
			},{
				name : '��Ŀ���',
				value : 'c.projectCode'
			},{
				name : '�����ʽ',
				value : 'c.outsourcing',
				type:'select',
				datacode:'HTWBFS'
			},{
				name : '���ʽ',
				value : 'c.payType',
				type:'select',
				datacode:'HTFKFS'
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
		},{
            display : '�����ͬ��',
            name : 'outContractCode'
        },{
            display : '��Ŀ���',
            name : 'projectCodeSearch'
        },{
            display : '��Ŀ���� ',
            name : 'projectNameSearch'
        },{
            display : '��ͬ������',
            name : 'principalName'
        },{
            display : '������',
            name : 'createName'
        }],
		// Ĭ�������ֶ���
		sortname : "c.createTime",
		// Ĭ������˳�� ����DESC ����ASC
		sortorder : "DESC",
		// ����״̬���ݹ���
		comboEx : [{
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
			}, {
				text : '����״̬',
				key : 'ExaStatus',
				data : [{
						text : '���ύ',
						value : '���ύ'
					}, {
						text : '��������',
						value : '��������'
					}, {
						text : '���������',
						value : '���������'
					}, {
						text : '���',
						value : '���'
					}, {
						text : '���',
						value : '���'
					}
				]
			}
		]
    });
});