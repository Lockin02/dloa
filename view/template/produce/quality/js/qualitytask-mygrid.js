var show_page = function() {
	$("#qualitytaskGrid").yxsubgrid("reload");
};
$(function() {
	if($("#relDocType").val() != ''){
		param = {
			chargeUserCode : $("#userId").val(),
			relDocType : $("#relDocType").val()
		};
	}else{
		param = {
			chargeUserCode : $("#userId").val()
		};
	}
	$("#qualitytaskGrid").yxsubgrid({
		model : 'produce_quality_qualitytask',
		title : '�ҵ��ʼ�����',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		param : param,
		isOpButton : false,
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
			width : 120,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualitytask&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		}, {
			name : 'applyCode',
			display : '�ʼ����뵥���',
			sortable : true,
			width : 120,
			process : function(v,row){
				var applyCodeArr = v.split(',');
				var applyIdArr = row.applyId.split(',');
				var rtStr = '';
				for(var i = 0; i < applyCodeArr.length ; i++){
					if(rtStr == ""){
						rtStr = "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + applyIdArr[i] + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + applyCodeArr[i] + "</a>";
					}else{
						rtStr += ",<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + applyIdArr[i] + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + applyCodeArr[i] + "</a>";
					}
				}
				return rtStr;
			}
		}, {
			name : 'createName',
			display : '�� �� ��',
			sortable : true
		}, {
			name : 'createId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '�����´�����',
			sortable : true,
			width : 140
		}, {
			name : 'chargeUserName',
			display : '�� �� ��',
			sortable : true
		}, {
			name : 'acceptStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				switch(v){
					case "YJS" : return "δ���"; break;
					case "YWC" : return "�����"; break;
					default : return "�Ƿ�״̬";
				}
			},
			width : 70
		}, {
			name : 'acceptTime',
			display : '��������',
			sortable : true,
			width : 140
		}, {
			name : 'complatedTime',
			display : '�������',
			sortable : true,
			width : 140
		}, {
			name : 'updateName',
			display : '�޸���',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '�޸���id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '�޸�����',
			sortable : true,
			hide : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=produce_quality_qualitytaskitem&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productCode',
				display : '���ϱ��'
			}, {
				name : 'productName',
				display : '��������',
				width : 150
			}, {
				name : 'pattern',
				display : '�ͺ�',
				width : 120
			}, {
				name : 'unitName',
				display : '��λ',
				width : 70
			}, {
				name : 'checkTypeName',
				display : '�ʼ췽ʽ',
				width : 80
			}, {
				name : 'assignNum',
				width : 80,
				display : '�´�����'
			}, {
				name : 'checkedNum',
				width : 80,
				display : '���ʼ�����'
			}, {
				name : 'standardNum',
				width : 70,
				display : '�ϸ�����'
			}, {
                name : 'unStandardNum',
                display : '���ϸ�����',
                width : 70,
				process : function(v,row){
					var produceNum = 0;
					if(row.realCheckNum >= 0 && row.realCheckNum != ''){
						produceNum = row.realCheckNum-row.standardNum;
					}else{
						produceNum = row.checkedNum-row.standardNum;
					}
					return (row.checkStatus == "")? "-" : produceNum;
				}
            },{
                name : 'qualitedRate',
                display : '�ϸ���',
                process : function(v,row){
                    if(v!=""){
                        return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=produce_quality_qualityereport&action=toItempage&type=task&sourceId=" + row.id  +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
                    }else{
                        return v;
                    }
                }
            }, {
				name : 'checkStatus',
				width : 80,
				display : '����״̬',
				process : function(v) {
					if (v == "YJY") {
						return "�Ѽ���";
					} else if( v == ""){
						return "δ����";
					}else if(v == "BFJY"){
						return "���ּ���";
					}
				}
			}]
		},
		menusEx : [{
			text : "����",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.acceptStatus == 'WJS') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (confirm('ȷ�Ͻ�������')) {
					$.ajax({
						type : 'POST',
						url : '?model=produce_quality_qualitytask&action=acceptTask&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						success : function(data) {
							if(data == "1"){
								alert('�����ѽ���');
							}else{
								alert("�������ʧ��");
							}
							show_page();
						}
					});
				}
			}
		},{
			text : "¼���ʼ챨��",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.acceptStatus == 'YJS') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				//���б��洦�� -- δʵ��
				return showOpenWin("?model=produce_quality_qualityereport&action=toAdd&mainId=" + row.id
                    +'&mainCode=' +row.docCode
                    +'&relDocType=' +row.relDocType
                    +'&applyId=' +row.applyId
                    +'&skey=' + row.skey_ ,1,700,1000, row.docCode);
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		// ����״̬���ݹ���
		comboEx : [{
		text : '����״̬',
		key : 'acceptStatusArr',
		value : 'YJS',
		data : [{
				text : 'δ���',
				value : 'YJS'
			}, {
				text : '�����',
				value : 'YWC'
			}]
		}],
		searchitems : [{
			display : "���ݱ��",
			name : 'docCodeSearch'
		},{
			display : "�ʼ����뵥��",
			name : 'applyCodeSearch'
		}]
	});
});