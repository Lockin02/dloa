// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#myEquGrid").yxgrid("reload");
};
	function viewPlan(basicId,purchType){
	    var skey = "";
	    $.ajax({
		    type: "POST",
		    url: "?model=purchase_plan_basic&action=md5RowAjax",
		    data: {"id" : basicId},
		    async: false,
		    success: function(data){
		   	   skey = data;
			}
		});

		location="index1.php?model=purchase_plan_basic&action=read&id="+basicId+"&purchType="+purchType+"&skey=" + skey;
	}

$(function() {
	$("#myEquGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_plan_equipment',
		action : 'pageJsonAllList',
		title : '�ɹ��������ϻ���',
		isToolBar : false,
		showcheckbox : false,

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					display : 'basicId',
					name : 'basicId',
					sortable : true,
					hide : true
				}, {
						name : 'productNumb',
						display : '���ϱ��'
					}, {
						name : 'productName',
						width : 200,
						display : '��������',
						process : function(v, data) {
							if (v == "") {
								return data.inputProductName;
							}
							return v;
						}
					},{
					display : '�ɹ�������',
					name : 'basicNumb',
					sortable : true,
					width : 110,
					process : function(v, row) {
						return "<a href='#' onclick='viewPlan(\""
								+ row.basicId
								+"\",\""
								+row.purchType
								+ "\")' >"
								+ v
								+ "</a>";
					}
				},{
					display : '����״̬',
					name : 'ExaStatus',
					sortable : true,
					width:60
				}, {
					display : '����Դ���ݺ�',
					name : 'sourceNumb',
					sortable : true,
					width : 110
				}, {
					display : '���κ�',
					name : 'batchNumb',
					sortable : true
				}, {
						name : 'amountAll',
						display : "��������",
						width : 70
					},{
						name : 'amountIssued',
						display : "���´���������"
					},

						{
					display : '����ʱ�� ',
					name : 'dateIssued',
					sortable : true,
					width : 80
				}, {
					display : 'ϣ�����ʱ�� ',
					name : 'dateHope',
					sortable : true,
					width : 80
				}],
		// ��չ�Ҽ��˵�
//		menusEx : [{
//			text : '�鿴',
//			icon : 'view',
//			action : function(row, rows, grid) {
//				if (row) {
//					location="?model=purchase_plan_basic&action=read&id="
//							+ row.id + "&purchType=" + row.purchType+"&skey="+row['skey_'];
//				} else {
//					alert("��ѡ��һ������");
//				}
//			}
//
//		}],
		// ��������
		searchitems : [{
					display : '��������',
					name : 'productName'
				},{
					display : '���ϱ��',
					name : 'seachProductNumb'
				},{
					display : '�ɹ�������',
					name : 'basicNumb'
				},{
					display : '���κ�',
					name : 'searchBatchNumb'
				}
		],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳��
		sortorder : "DESC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});