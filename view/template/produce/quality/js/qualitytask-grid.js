var show_page = function(page) {
	$("#qualitytaskGrid").yxgrid("reload");
};
$(function() {
 var comBxArr=[/*{
		text : '����״̬',
		key : 'acceptStatusArr',
		//value : 'WJS,YJS',
		data : [{
	  		text : '--δ���--',
	  		value : 'WJS,YJS'
	  	},{
	  		text : 'δ����',
	  		value : 'WJS'
	  	},{
	  		text : 'δ���',
	  		value : 'YJS'
	  	}, {
	  		text : '�����',
	  		value : 'YWC'
	  	}
	  	]
	  }*/];
var acceptStatusArr= $("#acceptStatusArr").val();
/*if(acceptStatusArr!='')
	 comBxArr=[];*/
	$("#qualitytaskGrid").yxgrid({
		model : 'produce_quality_qualitytask',
		action : 'pageJsonDetail',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		title : '�ʼ�����',
		isOpButton : false,
		showcheckbox : false,
		param : {
			acceptStatusArr : acceptStatusArr
		},
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
//				return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + row.applyId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		},{
			name : 'productCode',
			display : '���ϱ��',
			width : 90
		}, {
			name : 'productName',
			display : '��������',
			width : 150
		}, {
			name : 'pattern',
			display : '�ͺ�',
			width : 100
		}/*, {
			name : 'unitName',
			display : '��λ',
			width : 50
		}*/, {
			name : 'checkTypeName',
			display : '�ʼ췽ʽ',
			width : 80
		},{
			name : 'checkStatus',
			display : '����״̬',
			process : function(v) {
				switch(v){
					case "YJY" : return "�Ѽ���"; break;
					case "" : return "δ����"; break;
					case "BFJY" : return "���ּ���"; break;
				}
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
					//case "WJS" : return "δ����"; break;
					case "YJS" : return "δ���"; break;
					case "YWC" : return "�����"; break;
					default : return "�Ƿ�״̬";
				}
			},
			width : 80
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
		
//		menusEx : [{
//			name : 'edit',
//			text : "�༭",
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if(row.status != '3'){
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				showModalWin("?model=produce_quality_qualitytask&action=toEdit&id="
//						+ row.id + "&skey=" + row['skey_']);
//			}
//		}],
		toViewConfig : {
			action : 'toView',
			formWidth : 850
		},
		// ����״̬���ݹ���
		comboEx : comBxArr,
		searchitems : [{
			display : "���ݱ��",
			name : 'docCodeSearch'
		},{
			display : "�ʼ����뵥��",
			name : 'applyCodeSearch'
		},{
			display : "���ϱ���",
			name : 'productCodeSearch'
		},{
			display : "��������",
			name : 'productNameSearch'
		}]
	});

});