var show_page = function(page) {
	$("#qualityereportGrid").yxgrid("reload");
};
$(function() {
	$("#qualityereportGrid").yxgrid({
		model : 'produce_quality_qualityereport',
		title : '�ʼ챨��',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'docCode',
				display : '���ݱ��',
				sortable : true,
				width : 110,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=produce_quality_qualityereport&action=toView&id=" + row.id + '&skey=' + row.skey_ +",1,700,1000, " + row.docCode +"\")'>" + v + "</a>";
				}
			}, {
				name : 'mainCode',
				display : 'Դ�����',
				sortable : true,
				width : 110,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualitytask&action=toView&id=" + row.mainId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'examineUserName',
				display : '������',
				sortable : true
			}, {
				name : 'docDate',
				display : '��������',
				width : 80,
				sortable : true
			}, {
				name : 'auditStatus',
				display : '��˽��',
				sortable : true,
				width : 80,
				process : function(v) {
					switch(v){
						case "BC" : return "����"; break;
						case "WSH" : return "�����"; break;
						case "YSH" : return "�ϸ�"; break;
						case "RBJS" : return "�ò�����"; break;
						case "BHG" : return "���ϸ�"; break;
						default : return "�Ƿ�״̬";
					}
				}
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				width : 80,
				sortable : true
			}, {
				name : 'ExaDT',
				display : '��������',
				width : 80,
				sortable : true
			}, {
				name : 'remark',
				display : '��������',
				sortable : true,
				hide : true
			}],
		menusEx : [{
			text : '���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=produce_quality_qualityereport&action=toConfirm&id=" + row.id + "&skey=" + row.skey_ ,1, 700 , 1000, row.docCode )
			}
		},{
			text : '�������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus =="���") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=produce_quality_qualityereport&action=toConfirm&id=" + row.id + "&skey=" + row.skey_ ,1, 700 , 1000, row.docCode )
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView',
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showOpenWin("?model=produce_quality_qualityereport&action=toView&id=" + rowData[p.keyField] ,1, 700 , 1000, rowData.docCode );
			}
		},
		// ����״̬���ݹ���
		comboEx : [{
			text : '��˽��',
			key : 'auditStatus',
			data : [{
				text : '����',
				value : 'BC'
			},{
				text : '�����',
				value : 'WSH'
			},{
				text : '�ϸ�',
				value : 'YSH'
			}, {
				text : '�ò�����',
				value : 'RBJS'
			}, {
				text : '���ϸ�',
				value : 'BHG'
			}]
		}],
		searchitems : [{
			display : "���ݱ��",
			name : 'docCodeSearch'
		},{
			display : "Դ�����",
			name : 'mainCodeSearch'
		},{
			display : "������",
			name : 'examineUserNameSearch'
		}]
	});
});