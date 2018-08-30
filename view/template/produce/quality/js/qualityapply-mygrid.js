var show_page = function(page) {
	$("#qualityapplyGrid").yxsubgrid("reload");
};
$(function() {
	$("#qualityapplyGrid").yxsubgrid({
		model : 'produce_quality_qualityapply',
		title : '�ҵ��ʼ����뵥',
		isAddAction : false,
		isEditAction : false,
		param : {
			applyUserCode : $("#userId").val()
		},
		isOpButton : false,
		showcheckbox : false,
		isDelAction : false,
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
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'relDocType',
				display : 'Դ������',
				sortable : true,
				width : 80,
				datacode : 'ZJSQDYD'
			}, {
				name : 'relDocCode',
				display : 'Դ�����',
				sortable : true,
				width : 110
			}, {
				name : 'supplierName',
				display : '��Ӧ��',
				sortable : true,
				width : 130
			}, {
				name : 'status',
				display : '����״̬',
				sortable : true,
				width : 70,
				process : function(v){
					switch(v){
						case '0' : return "��ִ��";
						case '1' : return "����ִ��";
						case '2' : return "ִ����";
						case '3' : return "�ѹر�";
						case "4" : return "δ����";
						default : return '<span class="red">�Ƿ�״̬</span>';
					}
				}
			}, {
				name : 'applyUserName',
				display : '������',
				sortable : true
			}, {
				name : 'applyUserCode',
				display : '������Code',
				hide : true,
				sortable : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				width : 130,
				sortable : true
			}, {
				name : 'closeUserName',
				display : '�ر���',
				sortable : true
			}, {
				name : 'closeUserId',
				display : '�ر���id',
				hide : true,
				sortable : true
			}, {
				name : 'closeTime',
				display : '�ر�ʱ��',
				width : 130,
				sortable : true
			}, {
				name : 'workDetail',
				display : '����',
				width : 200,
				hide : true,
				sortable : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=produce_quality_qualityapplyitem&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
				name : 'productCode',
				display : '���ϱ��',
				width : 80
			}, {
				name : 'productName',
				display : '��������',
				width : 120
			}, {
				name : 'pattern',
				display : '�ͺ�'
			}, {
				name : 'unitName',
				display : '��λ',
				width : 50
			}, {
				name : 'checkTypeName',
				display : '�ʼ췽ʽ',
				width : 60
			}, {
				name : 'qualityNum',
				display : '��������',
				width : 60
			}, {
				name : 'assignNum',
				display : '���´�����',
				width : 60
			}, {
				name : 'complatedNum',
				display : '�ʼ������',
				width : 65
			},{
				name : 'standardNum',
				display : '�ϸ�����',
				width : 60
			},{
				name : 'status',
				display : '������',
				width : 60,
				process : function(v){
					switch(v){
						case "0" : return "�ʼ����";
						case "1" : return "���ִ���";
						case "2" : return "������";
						case "3" : return "�ʼ����";
						default : return "";
					}
				}
			},{
				name : 'dealUserName',
				display : '������',
				width : 80
			},{
				name : 'dealTime',
				display : '����ʱ��',
				width : 130
			}]
		},
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView',
			formWidth : 900,
			formHeight : 500,
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showThickboxWin("?model=produce_quality_qualityapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		// ����״̬���ݹ���
		comboEx : [{
			text : '����״̬',
			key : 'statusArr',
			value : '0,1,2',
			data : [{
				text : '--δ���--',
				value : '0,1,2'
			},{
				text : '��ִ��',
				value : '0'
			},{
				text : '����ִ��',
				value : '1'
			}, {
				text : 'ִ����',
				value : '2'
			}, {
				text : '�ѹر�',
				value : '3'
			}]
		}],
		searchitems : [{
			display : "���ݱ��",
			name : 'docCodeSearch'
		},{
			display : "Դ�����",
			name : 'relDocCodeSearch'
		},{
			display : "������",
			name : 'createNameSearch'
		},{
			display : "��Ӧ��",
			name : 'supplierNameSearch'
		}]
	});
});