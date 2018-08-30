var show_page = function() {
	$("#incomeGrid").yxgrid("reload");
}

$(function() {
	$("#incomeGrid").yxgrid({
		model : 'finance_income_income',
		action : 'pageJsonList',
		param : {"formType" : "YFLX-TKD"},
		title : 'Ӧ���˿����',
		isToolBar : true,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
        isOpButton : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���˵���',
			name : 'inFormNum',
			sortable : true,
			width : 110,
            hide : true
		}, {
			display : 'ϵͳ���ݺ�',
			name : 'incomeNo',
			sortable : true,
			width : 120,
            process : function(v,row){
                if(row.id == 'noId') return v;
                return "<a href='#' onclick='showOpenWin(\"?model=finance_income_income&action=toAllot&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
            }
		}, {
			display : '�ͻ�����',
			name : 'incomeUnitName',
			sortable : true,
			width : 130
		}, {
			display : 'ʡ��',
			name : 'province',
			sortable : true,
			width : 80
		}, {
			display : '��������',
			name : 'incomeDate',
			sortable : true,
            width : 80
		}, {
			display : '��������',
			name : 'incomeType',
			datacode : 'DKFS',
			sortable : true,
			width : 80
		}, {
			display : '���ݽ��',
			name : 'incomeMoney',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 90
		}, {
			name : 'businessBelongName',
			display : '������˾',
			sortable : true,
			width : 80
		}, {
			display : '¼����',
			name : 'createName',
			sortable : true
		}, {
			display : '״̬',
			name : 'status',
			datacode : 'DKZT',
			sortable : true,
			width : 80
		}, {
			display : '¼��ʱ��',
			name : 'createTime',
			sortable : true,
			width : 120
		}],
        buttonsEx : [{
			name : 'Add',
			text : "����",
			icon : 'edit',
			action : function(row) {
				showThickboxWin("?model=finance_income_income&action=toExcel"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600")
			}
		}],
		toAddConfig:{
			toAddFn : function(p) {
				showOpenWin("?model=finance_income_income&action=toAdd&formType=YFLX-TKD");
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [
			{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row)
					showOpenWin("?model=finance_income_income"
                        + "&action=toAllot"
                        + "&id="
                        + row.id
                        + "&perm=view"
                        + '&skey=' + row['skey_'] );
			}
		}, {
			text : '�༭',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row)
					showOpenWin("?model=finance_income_income"
                        + "&action=toAllot"
                        + "&id="
                        + row.id
                        + '&skey=' + row['skey_'] );
			}
		}, {
			name : 'delete',
			text : 'ɾ��',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_income_income&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									$("#incomeGrid").yxgrid("reload");
								}
							}
						});
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
        // ��������
        comboEx : [{
            text : '״̬',
            key : 'status',
            datacode : 'DKZT'
        }],
		searchitems : [{
			display : '�ͻ�����',
			name : 'incomeUnitName'
		},{
			display : '�ͻ�ʡ��',
			name : 'province'
		},{
			display : '���˵���',
			name : 'inFormNum'
		}]
	});
});