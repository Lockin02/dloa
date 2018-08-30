var show_page = function(page) {
	$("#qualityereportGrid").yxgrid("reload");
};
$(function() {
	var statu_=$("#statu_").val();
	var paramData={};
	if(statu_==1){
		paramData={
				noComplete : "n"
		};
	}else if(statu_==2){
		paramData={
				completeStatus : "y"

		};
	}

	$("#qualityereportGrid").yxgrid({
		model : 'produce_quality_qualityereport',
		action : 'pageDetail',
		title : '�ʼ챨��',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		showcheckbox : false,
		param : paramData,
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
						case "BH" : return "����"; break;
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
				name : 'productCode',
				display : '���ϱ���',
				sortable : true,
				width : 110
			}, {
				name : 'productName',
				display : '��������',
				sortable : true,
				width : 110
			}, {
				name : 'supplierName',
				display : '��Ӧ��',
				sortable : true,
				width : 110
			}, {
				name : 'priority',
				display : '�����̶�',
				datacode : 'ZJJJCD',
				width : 70
			}, {
				name : 'qualitedNum',
				display : '�ϸ���',
				width : 70
			}, {
				name : 'produceNum',
				display : '���ϸ���',
				width : 70
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
<<<<<<< .mine
=======
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
						case "BH" : return "����"; break;
						case "BHG" : return "���ϸ�"; break;
						case "BH" : return "����"; break;
						default : return "�Ƿ�״̬";
					}
				}
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				width : 80,
				sortable : true
			}, {
>>>>>>> .r31499
				name : 'ExaDT',
				display : '��������',
				width : 80,
				sortable : true
			}, {
				name : 'remark',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'productRemark',
				display : '��ע'
			}],
		menusEx : [{
			text : '���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' && row.auditStatus != 'BH') {
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
			data : [/*{
				text : '����',
				value : 'BC'
			},*/{
				text : '�����',
				value : 'WSH'
			}, {
				text : '����',
				value : 'BH'
			},{
				text : '�ϸ�',
				value : 'YSH'
			}, {
				text : '�ò�����',
				value : 'RBJS'
			}, {
				text : '����',
				value : 'BC'
			}, {
				text : '���ϸ�',
				value : 'BHG'
			}]
		},{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : '���ύ',
				value : '���ύ'
			},{
				text : '��������',
				value : '��������'
			},{
				text : '���',
				value : '���'
			}, {
				text : '���',
				value : '���'
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
		},{
			display : "���ϱ���",
			name : 'productCodeSearch'
		},{
			display : "��������",
			name : 'productNameSearch'
		},{
			display : "��Ӧ��",
			name : 'supplierNameSearch'
		}]
	});
});