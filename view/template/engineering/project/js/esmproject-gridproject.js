var show_page = function(page) {
	$("#esmprojectGrid").yxgrid("reload");
};

$(function() {

	var attributeVal =  $("#attribute").val();

	//��ͷ��ť����
	buttonsArr = [];

	//��ͷ��ť����
	excelOutArr = [{
			name : 'exportOut',
			text : "����",
			icon : 'excel',
			action : function(row) {
				window.open(
					"?model=engineering_project_esmproject&action=exportExcel&attribute=" + attributeVal,
					"", "width=200,height=200,top=200,left=200");
			}
		}
	];

	$.ajax({
		type : 'POST',
		url : '?model=engineering_project_esmproject&action=getLimits',
		data : {
			'limitName' : '���뵼��Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr = excelOutArr;
			}
		}
	});

    $("#esmprojectGrid").yxgrid({
        model : 'engineering_project_esmproject',
        title : '������Ŀ���ܱ�',
        param : {'attribute' : attributeVal },
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		showcheckbox : false,
		customCode : 'esmAttGrid',
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            name : 'officeId',
            display : '����ID',
            sortable : true,
            hide : true
        },{
            name : 'officeName',
            display : '����',
            sortable : true
        },{
            name : 'country',
            display : '����',
            sortable : true,
            width : 70,
            hide : true
        },{
            name : 'province',
            display : 'ʡ��',
            sortable : true,
            width : 70
        },{
            name : 'city',
            display : '����',
            sortable : true,
            width : 70,
            hide : true
        },{
            name : 'attributeName',
            display : '��Ŀ����',
            width : 80,
			process : function(v,row){
				switch(row.attribute){
					case 'GCXMSS-01' : return "<span class='red'>" + v + "</span>";break;
					case 'GCXMSS-02' : return "<span class='blue'>" + v + "</span>";break;
					case 'GCXMSS-03' : return "<span class='green'>" + v + "</span>";break;
					default : return v;
				}
			}
        },{
            name : 'projectName',
            display : '��Ŀ����',
            sortable : true,
            width : 150,
            process : function(v,row){
				if((row.contractId == "0"  || row.contractId == "")&& row.contractType != 'GCXMYD-04'){
					return "<span style='color:blue' title='δ������ͬ�ŵ���Ŀ'>" + v + "</span>";
				}else{
					return v;
				}
            }
        },{
            name : 'projectCode',
            display : '��Ŀ���',
            sortable : true,
            width : 120,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
			}
        },{
            name : 'status',
            display : '��Ŀ״̬',
            sortable : true,
			datacode : 'GCXMZT',
            width : 80
        },{
            name : 'budgetAll',
            display : '��Ԥ��',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'budgetField',
            display : '�ֳ�Ԥ��',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'budgetPerson',
            display : '����Ԥ��',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'budgetEqu',
            display : '�豸Ԥ��',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'budgetOther',
            display : '����Ԥ��',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'budgetOutsourcing',
            display : '���Ԥ��',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'feeAll',
            display : '�ܾ���(����ȷ��)',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			}
        },{
            name : 'feeAllCount',
            display : '�ܾ���(ʵʱ)',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			}
        },{
            name : 'feeFieldCount',
            display : '�ֳ�����(ʵʱ)',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			}
        },{
            name : 'feePerson',
            display : '��������',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'feeEqu',
            display : '�豸����',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'feeOutsourcing',
            display : '�������',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'feeOther',
            display : '��������',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'feeAllProcess',
            display : '���ý���(����ȷ��)',
            sortable : true,
			process : function(v,row){
				if( row.id == 'noId') return '';
				return v + ' %';
			},
            width : 120
        },{
            name : 'feeAllProcessCount',
            display : '���ý���(ʵʱ)',
            sortable : true,
			process : function(v,row){
				if( row.id == 'noId') return '';
				return v + ' %';
			},
            width : 120
        },{
            name : 'feeFieldProcessCount',
            display : '�ֳ����ý���(ʵʱ)',
            sortable : true,
			process : function(v,row){
				if( row.id == 'noId') return '';
				return v + ' %';
			},
            width : 120
        },{
//            name : 'feeFieldProcess',
//            display : '�ֳ����ý���',
//            sortable : true,
//			process : function(v,row){
//				if( row.id == 'noId') return '';
//				return v + ' %';
//			},
//            width : 80,
//            hide : true
//        },{
            name : 'projectProcess',
            display : '���̽���',
            sortable : true,
			process : function(v,row){
				if( row.id == 'noId') return '';
				return v + ' %';
			},
            width : 80
        },{
            name : 'contractTypeName',
            display : 'Դ������',
            sortable : true,
            hide : true
        },{
            name : 'contractId',
            display : '������ͬid',
            sortable : true,
            hide : true
        },{
            name : 'contractCode',
            display : '������ͬ���(Դ�����)',
            sortable : true,
            width : 160,
            hide : true
        },{
            name : 'contractTempCode',
            display : '��ʱ��ͬ���',
            sortable : true,
            width : 160,
            hide : true
        },{
            name : 'rObjCode',
            display : 'ҵ����',
            sortable : true,
            width : 120,
            hide : true
        },{
            name : 'contractMoney',
            display : '��ͬ���',
            sortable : true,
            process : function(v){
            	return moneyFormat2(v);
            }
        },{
            name : 'customerId',
            display : '�ͻ�id',
            sortable : true,
            hide : true
        },{
            name : 'customerName',
            display : '�ͻ�����',
            sortable : true,
            hide : true
        },{
//            name : 'proName',
//            display : '����ʡ��',
//            sortable : true,
//            hide : true
//        },{
            name : 'depName',
            display : '��������',
            sortable : true,
            hide : true
        },{
            name : 'planBeginDate',
            display : 'Ԥ����������',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			},
            width : 80,
            hide : true
        },{
            name : 'planEndDate',
            display : 'Ԥ�ƽ�������',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			},
            width : 80
        },{
            name : 'actBeginDate',
            display : 'ʵ�ʿ�ʼʱ��',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			},
            width : 80
        },{
            name : 'actEndDate',
            display : 'ʵ�����ʱ��',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			},
            width : 80
        },{
            name : 'managerName',
            display : '��Ŀ����',
            sortable : true
        },{
            name : 'ExaStatus',
            display : '����״̬',
            sortable : true,
            width : 80
        },{
            name : 'ExaDT',
            display : '��������',
            sortable : true,
            hide : true,
            width : 80
        },{
            name : 'peopleNumber',
            display : '������',
            sortable : true,
            width : 80
        },{
            name : 'natureName',
            display : '����1',
            sortable : true
        },{
            name : 'nature2Name',
            display : '����2',
            sortable : true
        },{
            name : 'outsourcingName',
            display : '�������',
            sortable : true,
            width : 80
        },{
            name : 'cycleName',
            display : '��/����',
            sortable : true,
            width : 80
        },{
            name : 'categoryName',
            display : '��Ŀ���',
            sortable : true,
            width : 80
        },{
            name : 'platformName',
            display : '������ƽ̨',
            sortable : true,
            width : 80
        },{
            name : 'netName',
            display : '����',
            sortable : true,
            width : 80
        },{
            name : 'createTypeName',
            display : '������ʽ',
            sortable : true,
            width : 80
        },{
            name : 'signTypeName',
            display : 'ǩ����ʽ',
            sortable : true,
            width : 80
        },{
            name : 'toolType',
            display : '��������',
            sortable : true,
            width : 80
        },{
            name : 'updateTime',
            display : '�������',
            sortable : true,
            width : 120
        }],
		subGridOptions : {
			model:'finance_invoice_invoice',
    		action:'pageJsonInfoList',
			// ��ʾ����
			colModel : [{
					display : '��Ŀ����',
					name : 'projectName'
				},{
					display : '��Ŀ���',
					name : 'projectCode'
				}
			]
		},
		lockCol:['projectName','projectCode'],//����������
		toEditConfig : {
			formWidth : 1100,
			formHeight : 550,
			showMenuFn : function(row) {
//				��ʱ�ر���Ŀ�༭����
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
				text : '�鿴��Ŀ',
				icon : 'view',
				showMenuFn : function(row) {
					if ((row.id == "noId")) {
						return false;
					}
				},
				action : function(row, rows, grid) {
					showModalWin("?model=engineering_project_esmproject&action=viewTab&id="
							+ row.id);
				}
			},{
				text : '�༭��Ŀ',
				icon : 'edit',
				showMenuFn : function(row) {
					if ((row.id == "noId")) {
						return false;
					}
					if ((row.ExaStatus == "���")) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					showThickboxWin("?model=engineering_project_esmproject&action=toEditRight&id="
							+ row.id
				          + "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1100");
				}
			},{
				name : 'aduit',
				text : '�������',
				icon : 'view',
				showMenuFn : function(row) {
					if ((row.id == "noId")) {
						return false;
					}
					if ((row.ExaStatus == "���" || row.ExaStatus == "���")) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (row) {
						showThickboxWin("controller/common/readview.php?itemtype=oa_esm_project&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
					}
				}
			},
			{
				text: "ɾ��",
				icon: 'delete',
				showMenuFn : function(row) {
					if ((row.id == "noId")) {
						return false;
					}
				},
				action: function(row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=engineering_project_esmproject&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									show_page(1);
								}else{
									alert("ɾ��ʧ��! ");
								}
							}
						});
					}
				}
			}
		],
		buttonsEx : buttonsArr,
      // �߼�����
//		advSearchOptions : {
//			modelName : 'esmprojectSearch',
//			// ѡ���ֶκ��������ֵ����
//			selectFn : function($valInput) {
//				$valInput.yxselect_user("remove");
//				$valInput.yxcombogrid_office("remove");
//			},
//			searchConfig : [{
//					name : '��Ŀ����',
//					value : 'c.projectName'
//				},{
//					name : '��Ŀ���',
//					value : 'c.projectCode'
//				},{
//					name : '���´�',
//					value : 'c.officeName',
//					changeFn : function($t, $valInput, rowNum) {
//						if (!$("#officeId" + rowNum)[0]) {
//							$hiddenCmp = $("<input type='hidden' id='officeId" + rowNum + "'/>");
//							$valInput.after($hiddenCmp);
//						}
//						$valInput.yxcombogrid_office({
//							hiddenId : 'officeId' + rowNum,
//							height : 200,
//							width : 550,
//							gridOptions : {
//								showcheckbox : false
//							}
//						});
//					}
//				},{
//					name : '��Ŀ����',
//					value : 'c.managerName',
//					changeFn : function($t, $valInput, rowNum) {
//						if (!$("#managerId" + rowNum)[0]) {
//							$hiddenCmp = $("<input type='hidden' id='managerId"+ rowNum + "'/>");
//							$valInput.after($hiddenCmp);
//						}
//						$valInput.yxselect_user({
//							hiddenId : 'managerId' + rowNum,
//							height : 200,
//							width : 550,
//							gridOptions : {
//								showcheckbox : false
//							}
//						});
//					}
//				},{
//					name : '��Ŀ״̬',
//					value : 'c.status',
//					type:'select',
//					datacode : 'GCXMZT'
//				},{
//		            name : '��������',
//      				value : 'nature',
//					type:'select',
//		            datacode : 'GCXMXZ'
//		        },{
//		            name : '�������',
//		            value : 'outsourcing',
//					type:'select',
//					datacode : 'WBLX'
//		        },{
//		            name : '��/����',
//		            value : 'cycle',
//					type:'select',
//		            datacode : 'GCCDQ'
//		        },{
//		            name : '��Ŀ���',
//		            value : 'category',
//					type:'select',
//		            datacode : 'XMLB'
//		        }
//			]
//		},
		searchitems : [{
			display : '���´�',
			name : 'officeName'
		}, {
			display : '��Ŀ���',
			name : 'projectCodeSearch'
		}, {
			display : '��Ŀ����',
			name : 'projectName'
		}, {
			display : '��Ŀ����',
			name : 'managerName'
		},	{
			display : 'ҵ����',
			name : 'rObjCodeSearch'
		}, {
			display : '������ͬ��',
			name : 'contractCodeSearch'
		},	{
			display : '��ʱ��ͬ��',
			name : 'contractTempCodeSearch'
		}],
		// ����״̬���ݹ���
		comboEx : [{
			text: "����״̬",
			key: 'ExaStatus',
			type : 'workFlow'
		},{
			text: "��Ŀ״̬",
			key: 'status',
			datacode : 'GCXMZT',
			value : 'GCXMZT02'
		}],
		// Ĭ�������ֶ���
		sortname : "c.updateTime",
		// Ĭ������˳�� ����
		sortorder : "DESC"

    });
});