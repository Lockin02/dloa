var show_page = function() {
	$("#borrowGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [
        {
            text : "����",
            icon : 'delete',
			action : function(row) {
				var listGrid= $("#borrowGrid").data('yxsubgrid');
				listGrid.options.extParam = {};
				$("#borrowGrid tr").attr('style',"background-color: rgb(255, 255, 255)");
				listGrid.reload();
			}
		},{
			name : 'advancedsearch',
			text : "�߼�����",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=projectmanagent_borrow_borrow&action=search&gridName=borrowGrid"
				        + "&gridType=yxsubgrid"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
			}
		}
    ];

	// �����б�����
	var exportExcel = {
		text: "����",
		icon: 'excel',
		action: function (row) {
			var i = 1;
			var colId = "";
			var colName = "";
			$("#borrowGrid_hTable").children("thead").children("tr")
				.children("th").each(function () {
				if ($(this).css("display") != "none"
					&& $(this).attr("colId") != undefined && $(this).attr("colId") != 'test') {
					colName += $(this).children("div").html() + ",";
					colId += $(this).attr("colId") + ",";
					i++;
				}
			});
			window.open("?model=projectmanagent_borrow_borrow&action=exportExcel&colId="+colId+"&colName="+colName);
		}
	};

	// �����Ƿ��е���Ȩ��
	var exportLimit = $.ajax({
		url : "?model=projectmanagent_borrow_borrow&action=ajaxChkExportLimit",
		type : 'post',
		async : false
	}).responseText;
	if(exportLimit === '1'){
		buttonsArr.push(exportExcel);
	}

	$("#borrowGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'borrowGridJson',
		param : {
			"ExaStatus" : "���",
			"limits" : "�ͻ�"
		},
		title : '������',
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isToolBar : false, // �Ƿ���ʾ������
		showcheckbox : false,
        isOpButton : false,
		// ����Ϣ
		colModel : [{
			display : 'initTip',
			name : 'initTip',
			sortable : true,
			hide : true
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'chanceId',
			display : '�̻�Id',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '���',
			sortable : true
		}, {
			name : 'Type',
			display : '����',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'salesName',
			display : '���۸�����',
			sortable : true
		}, {
			name : 'beginTime',
			display : '��ʼ����',
			sortable : true
		}, {
			name : 'closeTime',
			display : '��ֹ����',
			sortable : true
		}, {
			name : 'scienceName',
			display : '����������',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 90,
			process: function (v,row) {
				if(row.lExaStatus != '���������'){
					return v;
				}else{
					return '���������';
				}
			}
		}, {
			name : 'checkFile',
			display : '�����ļ�',
			sortable : true,
			width : 90,
			process: function (v,row) {
				if(v == '��'){
					return v;
				}else{
					return '��';
				}
			}
		}, {
			name : 'DeliveryStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if (v == 'WFH') {
					return "δ����";
				} else if (v == 'YFH') {
					return "�ѷ���";
				} else if (v == 'BFFH') {
					return "���ַ���";
				} else if (v == 'TZFH') {
					return "ֹͣ����";
				}
			}
		}, {
			name : 'backStatus',
			display : '�黹״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ�黹";
				} else if (v == '1') {
					return "�ѹ黹";
				} else if (v == '2') {
					return "���ֹ黹";
				}
			}
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			sortable : true,
			hide : true,
			process: function (v,row){
				if(row['ExaStatus'] == "��������"){
					return '';
				}else{
					return v;
				}
			}
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true
		}, {
			name : 'objCode',
			display : 'ҵ����',
			width : 120
		}],
		comboEx : [{
			text: '�黹״̬',
			key: 'backStatu',
			data: [
				{
					text: 'δ�黹',
					value: '0'
				},{
					text: '�ѹ黹',
					value: '1'
				},{
					text: '���ֹ黹',
					value: '2'
				}
			]
		},{
			text : '����״̬',
			key : 'ExaStatus',
			value : '���',
			data : [{
				text : 'δ����',
				value : 'δ����'
			}, {
				text : '���������',
				value : '���������'
			}, {
				text : '��������',
				value : '��������'
			}, {
				text : '����ȷ��',
				value : '����ȷ��'
			}, {
				text : '���',
				value : '���'
			}]
		}, {
			text : '����״̬',
			key : 'DeliveryStatus',
			data : [{
				text : 'δ����',
				value : 'WFH'
			}, {
				text : '�ѷ���',
				value : 'YFH'
			}, {
				text : '���ַ���',
				value : 'BFFH'
			}]
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrowequ&action=listPageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'borrowId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
				name : 'productNo',
				width : 100,
				display : '��Ʒ���',
				process : function(v, row) {
					return v + "&nbsp;&nbsp;K3:" + row['productNoKS'];
				}
			}, {
				name : 'productName',
				width : 200,
				display : '��Ʒ����',
				process : function(v, row) {
					return v + "&nbsp;&nbsp;K3:" + row['productNameKS'];
				}
			}, {
				name : 'productModel',
				width : 200,
				display : '���ϰ汾/�ͺ�'
			}, {
				name : 'number',
				display : '��������',
				width : 80
			}, {
				name : 'executedNum',
				display : '��ִ������',
				width : 80
			}, {
                name : 'applyBackNum',
                display : '������黹����'
            }, {
				name : 'backNum',
				display : '�ѹ黹����',
				width : 80
			}]
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���',
			name : 'Code'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : 'ҵ����',
			name : 'objCode'
		}, {
			display : '���۸�����',
			name : 'salesName'
		}, {
			display : '������',
			name : 'createNmae'
		}, {
			display : '��������',
			name : 'createTime'
		}, {
			display : 'K3��������',
			name : 'productNameKS'
		}, {
			display : 'ϵͳ��������',
			name : 'productName'
		}, {
			display : 'K3���ϱ���',
			name : 'productNoKS'
		}, {
			display : 'ϵͳ���ϱ���',
			name : 'productNo'
        }, {
            display: '���к�',
            name: 'serialName2'
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=toViewTab&id="
							+ row.id + "&skey=" + row['skey_']);

				}
			}
		}
		, {
			text : '�ύ���',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == 'δ����' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				// if (row) {
				// 	showThickboxWin('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId='
				// 			+ row.id
				// 			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				// }
				if (window.confirm(("ȷ���ύ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrow_borrow&action=ajaxSubForm",
						data : {
							id : row.id
						},
						success : function(msg) {
							if(msg != ""){
								alert(msg);
							}else{
								alert("�ύʧ�ܡ�������!");
							}
							$("#MyBorrowGrid").yxsubgrid("reload");
						}
					});
				}
			}
		},{
            text : '�黹����',
            icon : 'add',
            showMenuFn : function(row) {
                return row.ExaStatus == '���' && row.backStatus != '1' && $("#returnLimit").val() == "1";
            },
            action : function(row) {
                showOpenWin('?model=projectmanagent_borrowreturn_borrowreturn&action=toAdd&id=' + row.id);
            }
        }],
		buttonsEx : buttonsArr
	});
});