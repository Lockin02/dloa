// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#sourceApplyGrid").yxsubgrid("reload");
};
$(function() {
	var purchType=$("#purchType").val();
	var sourceId=$("#sourceId").val();
	$("#sourceApplyGrid").yxsubgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_plan_basic',
		action : 'assetListPageJson',
		title : '�����б�',
		isToolBar : false,
		showcheckbox : false,
		param:{'purchType':purchType,"sourceID":sourceId},

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ɹ�����',
					name : 'purchTypeCName',
					sortable : false
				}, {
					display : '�ɹ�������',
					name : 'planNumb',
					sortable : true,
					width : 150
				},{
					display : '����Դ���ݺ�',
					name : 'sourceNumb',
					sortable : true,
					width:180
				}, {
					display : '������',
					name : 'createName',
					sortable : true
				}, {
					display : '����ʱ�� ',
					name : 'sendTime',
					sortable : true
				}, {
					display : 'ϣ�����ʱ�� ',
					name : 'dateHope',
					sortable : true
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=purchase_plan_equipment&action=pageJsonExecute',
			param : [{
						paramId : 'basicId',
						colId : 'id'
					}],
			colModel : [ {
						name : 'productNumb',
						display : '���ϱ��'
					}, {
						name : 'productName',
						display : '��������',
						width : 200
					},{
						name : 'amountAll',
						display : "��������",
						width : 60
					}, {
						name : 'amountIssued',
						display : "�´���������",
						width : 80
					},{
						name : 'inquiryNumbs',
						display : "ѯ������",
						width : 60
					},{
						name : 'orderAmount',
						display : "��������",
						width : 60
					}, {
						name : 'stokcNum',
						display : "�������",
						width : 60
					},{
						name : 'dateHope',
						display : "ϣ���������"
					}]
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					location="?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType+"&skey="+row['skey_'];
				} else {
					alert("��ѡ��һ������");
				}
			}

		},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			    showMenuFn:function(row){
			    	if((row.ExaStatus=="���"||row.ExaStatus=="���")&&(row.purchType=="assets"||row.purchType=="rdproject"||row.purchType=="produce")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_plan_basic&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}],
		// ��������
		searchitems : [{
					display : '�ɹ�������',
					name : 'seachPlanNumb'
				},{
					display : '���ϱ��',
					name : 'productNumb'
				},{
					display : '��������',
					name : 'productName'
				}
		],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		// Ĭ�������ֶ���
		sortname : "updateTime",
		// Ĭ������˳��
		sortorder : "DESC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});