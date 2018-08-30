var show_page = function(page) {
	$("#qualityereportGrid").yxgrid("reload");
};
$(function() {
	$("#qualityereportGrid").yxgrid({
		model : 'produce_quality_qualityereport',
		title : '�ҵ��ʼ챨��',
		isAddAction : false,
		isDelAction : false,
		param : {
			examineUserId : $("#userId").val()
		},
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
				name : 'checkNum',
				display : 'ʵ�ʼ�������',
				width : 80,
				sortable : true,
				hide : true
			}, {
				name : 'qualitedNum',
				display : '�ϸ���',
				width : 80,
				sortable : true,
				hide : true
			}, {
				name : 'produceNum',
				display : '���ϸ���',
				width : 80,
				sortable : true,
				hide : true
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
		menusEx : [
			{
				text: "ɾ��",
				icon: 'delete',
				showMenuFn : function(row){
					if(row.auditStatus == 'BC'|| row.auditStatus == 'BH'){
						return true;
					}
					return false;
				},
				action: function(row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=produce_quality_qualityereport&action=ajaxdeletes",
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
			},{
				text: "���泷��",//�����ϸ�
				icon: 'delete',
				showMenuFn : function(row){
					if((row.auditStatus == 'YSH' || row.auditStatus == 'WSH') && row.relDocType != "ZJSQDLBF"){
						return true;
					}
					return false;
				},
				action: function(row) {
					if (window.confirm(("ȷ��Ҫ�����˱�����?"))) {
						$.ajax({
							type : "POST",
							url : "?model=produce_quality_qualityereport&action=backReport",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == "1") {
									alert('�����ɹ���');
									show_page(1);
								}else if(row.relDocType == 'ZJSQDLBF'){
									alert("����ʧ�ܣ��ô��ϱ����ʼ챨���ѽ�����������");
								}else if(msg == "-1"){
									alert("����ʧ�ܣ��ʼ�������������");
								}else{
									alert("����ʧ��! ");
								}
							}
						});
					}
				}
			},{
				text: "�ύ����",
				icon: 'delete',
				showMenuFn : function(row){
					if(row.auditStatus == 'YSH' && row.ExaStatus == '���ύ' && row.relDocType == "ZJSQDLBF"){
						return true;
					}
					return false;
				},
				action: function(row) {
					showThickboxWin("controller/produce/quality/ewf_bfzj_index.php?actTo=ewfSelect&billId="
						+ row.id
						+ "&relDocType=" + row.relDocType
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		],
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if (row.auditStatus == "BC"|| row.auditStatus == 'BH') {
					return true;
				}
				return false;
			},
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showOpenWin("?model=produce_quality_qualityereport&action=toEdit&id=" + rowData[p.keyField] ,1, 700 , 1000, rowData.docCode );
			}
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
		}]
	});
});