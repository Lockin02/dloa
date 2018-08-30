var show_page = function(page) {
	$("#stampGrid").yxgrid("reload");
};
$(function() {
	$("#stampGrid").yxgrid({
		model : 'contract_stamp_stamp',
		title : '���¼�¼',
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		isViewAction :false,
		customCode : 'stampRecordsGrid',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'contractId',
				display : '��ͬid',
				sortable : true,
				hide : true
			}, {
				name : 'contractCode',
				display : '��ͬ���',
            	width : 130,
				sortable : true
			}, {
				name : 'contractName',
				display : '��ͬ����',
				sortable : true,
            	width : 130,
				hide : true
			}, {
				name : 'contractType',
				display : '��ͬ����',
				sortable : true,
            	datacode : 'HTGZYD'
			}, {
				name : 'signCompanyName',
				display : 'ǩԼ��λ',
				sortable : true,
            	width : 130,
				hide : true
			}, {
				name : 'contractMoney',
				display : '��ͬ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'applyUserId',
				display : '������id',
				sortable : true,
				hide : true
			}, {
				name : 'applyUserName',
				display : '������',
				width : 80,
				sortable : true
			}, {
				name : 'applyDate',
				display : '��������',
				width : 80,
				sortable : true
			}, {
				name : 'stampType',
				display : '��������',
				sortable : true
			}, {
				name : 'stampUserId',
				display : '������id',
				sortable : true,
				hide : true
			}, {
				name : 'stampUserName',
				display : '������',
				sortable : true
			}, {
				name : 'stampDate',
				display : '��������',
				width : 80,
				sortable : true
			}, {
				name : 'stampCompany',
				display : '��˾',
				sortable : true,
				width : 80,
				process : function(v){
					if(v == '' || v == 'NULL'){
						return '';
					}else{
						return v;
					}
				}
			},  {
				name : 'stampCompanyId',
				display : '��˾ID',
				sortable : true,
				hide : true,
				process : function(v){
					if(v == '' || v == 'NULL'){
						return '';
					}else{
						return v;
					}
				}
			},{
				name : 'status',
				display : '״̬',
				sortable : true,
				width : 80,
				process : function(v,row){
					if(v=="1"){
						return "�Ѹ���";
					}else if(v=='2'){
						return "�ѹر�";
					}else{
						return "δ����";
					}
				}
			}, {
				name : 'objCode',
				display : 'ҵ����',
				width : 120,
				sortable : true
			}, {
				name : 'batchNo',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'remark',
				display : '��ע',
				width : 200,
				sortable : true
			}
		],
        // ��չ��ť
        buttonsEx : [{
            text: "����",
            icon: 'excel',
            action: function() {
                var i = 1;
                var colId = "";
                var colName = "";
                $("#stampGrid_hTable").children("thead").children("tr")
                    .children("th").each(function() {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined) {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    });
                var url = "?model=contract_stamp_stamp&action=toExportExcel"
                    + '&exportType=getAll'
                    + "&colName="
                    + colName
                    + "&colId="
                    + colId;
                window.open(url);
            }
        }],
		// ��չ�Ҽ��˵�
		menusEx : [{
				name : 'view',
				text : '�鿴',
				icon : 'view',
				action : function(row, rows, grid) {
					showModalWin('?model=contract_stamp_stamp&action=toView&id=' + row.id);
				}
			}
		],
		searchitems : [{
			display : "��ͬ���",
			name : 'contractCodeSer'
		},{
			display : "������",
			name : 'applyUserNameSer'
		},{
			display : "��˾",
			name : "stampCompanySearcha"
		}],
		// ����״̬���ݹ���
		comboEx : [{
			text: "����״̬",
			key: 'status',
			value : 1,
			data : [{
				text : 'δ����',
				value : '0'
			}, {
				text : '�Ѹ���',
				value : '1'
			}, {
				text : '�ѹر�',
				value : '2'
			}]
		}]
	});
});