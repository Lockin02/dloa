

var show_page=function(page){
   $("#rdprojectGrid").yxgrid("reload");
};

$(function(){
        $("#rdprojectGrid").yxgrid({

        	model:'rdproject_yxrdproject_rdproject',
        	action:'jsonCloseAuditNo',
        	title:'δ�����쳣�ر�����',
        	isToolBar:false,
        	showcheckbox : false,
        	isViewAction:false,
        	isAddAction:false,
        	isEditAction:false,
        	isDelAction:false,

			// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'sign',
			display : '�Ƿ�ǩԼ',
			sortable : true
		}, {
			name : 'orderstate',
			display : 'ֽ�ʺ�ͬ״̬',
			sortable : true
		}, {
			name : 'parentOrder',
			display : '����ͬ����',
			sortable : true,
			hide : true
		}, {
			name : 'orderCode',
			display : '������ͬ��',
			sortable : true
		}, {
			name : 'orderTempCode',
			display : '��ʱ��ͬ��',
			sortable : true
		}, {
			name : 'orderName',
			display : '��ͬ����',
			sortable : true
		}, {
			name : 'state',
			display : '��ͬ״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "������";
				} else if (v == '1') {
					return "������";
				} else if (v == '2') {
					return "ִ����";
				} else if (v == '3') {
					return "�ѹر�";
				} else if (v == '4') {
					return "�����";
				} else if (v == '5') {
					return "�Ѻϲ�";
				} else if (v == '6') {
					return "�Ѳ��";
				}
			},
			width : 90
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 90
		}],



					//��չ�Ҽ��˵�
					menusEx : [
					{
						text : '�鿴',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin("?model=rdproject_yxrdproject_rdproject&action=init&perm=view&id="+ row.contractId + "&skey="+row['skey_']
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800"
							);
						}
					},
						{
							text : '����',
							icon : 'edit',
							action : function(row,rows,grid){
								if(row){
									location = "controller/rdproject/yxrdproject/ewf_close.php?actTo=ewfExam&orderId="+row.contractId+"&spid="+row.id+"&billId="+row.contractId+"&examCode=oa_sale_service" + "&skey="+row['skey_'];
								}
							}
						}
					],
			searchitems:[
			        {
			            display:'��ͬ����',
			            name:'orderName'
			        }
			        ],
			sortname:'id',
			sortorder:'DESC'
        });
});