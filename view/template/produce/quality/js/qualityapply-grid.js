var show_page = function(page) {
	$("#qualityapplyGrid").yxsubgrid("reload");
};
$(function() {
	var relDocType = $("#relDocType").val();
	$("#qualityapplyGrid").yxsubgrid({
		model : 'produce_quality_qualityapply',
		title : '�ʼ����뵥',
		param : {
			relDocTypeArr:relDocType
		},
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
					if(row.status == "3" || row.status == "2" || row.relDocType == "ZJSQDLBF"){
						return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toQualityView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
					}
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
						case "4" : return "δ����";
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
//		menusEx : [{
//			text : '�´�����',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if(row.status != '3'){
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(row.status == '2'){
//					alert('�������ʼ������Ѿ���ȫ�´�,���ܼ������д˲���');
//					return false;
//				}else{
//					showThickboxWin("?model=produce_quality_qualitytask&action=toIssued&applyId="+ row.id
//						+ "&docType="
//						+ row.docType
//						+ "&skey="
//						+ row['skey_']
//						+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
//				}
//			}
//		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toQualityView',
			formWidth : 900,
			formHeight : 500,
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				if(row.status == "3" || row.status == "2" || row.relDocType == "ZJSQDLBF"){
					showThickboxWin("?model=produce_quality_qualityapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}else{
					return showThickboxWin("?model=produce_quality_qualityapply&action=toQualityView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
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