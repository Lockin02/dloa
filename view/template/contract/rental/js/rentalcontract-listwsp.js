// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".rentalcontractGrid").yxgrid("reload");
};
$(function() {
	$(".rentalcontractGrid").yxgrid({
		//�������url�����ô����url������ʹ��model��action�Զ���װ
		//						 url :
		model : 'contract_rental_rentalcontract',
        action : 'myPageJson',
		param : {
			'ExaStatusArr' : '��������,���' , "hiresId" : $("#userId").val()
		},
		title : '�����������޺�ͬ',
		showcheckbox : false,
		isToolBar : false,
		customCode : 'renlistwsp',
		//����Ϣ
		colModel :
			[
			  {
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
			  },{
					name : 'orderCode',
  					display : '������ͬ��',
  					sortable : true,
  					width : 210
              },{
					name : 'orderTempCode',
  					display : '��ʱ��ͬ��',
  					sortable : true,
  					width : 210
              },{
					name : 'orderName',
  					display : '��ͬ����',
  					sortable : true,
  					width : 210
              },{
                    name : 'tenant',
                    display : '�ͻ�����',
                    sortable : true,
                    width : 150
              },{
                    name : 'orderTempMoney',
                    display : 'Ԥ�ƺ�ͬ���',
                    sortable : true,
                    width : 100,
                    process : function(v){
  						return moneyFormat2(v);
  					}
              },{
                    name : 'orderMoney',
                    display : 'ǩԼ��ͬ���',
                    sortable : true,
                    width : 100,
                    process : function(v){
  						return moneyFormat2(v);
  					}
              },{
                    name : 'areaName',
                    display : '��������',
                    sortable : true,
                    width : 100
              },{
					name : 'state',
  					display : '��ͬ״̬',
  					sortable : true,
  					process : function(v){
  						if( v == '0'){
  							return "δ�ύ";
  						}else if(v == '1'){
  							return "������";
  						}else if(v == '2'){
  							return "ִ����";
  						}else if(v == '3'){
  							return "�ѹر�";
  						}else if(v == '4'){
  						    return "�����";
  						}
  					},
  					width : 90
              },{
					name : 'ExaStatus',
  					display : '����״̬',
  					sortable : true,
  					width : 100
              },{
    				name : 'sign',
  					display : '�Ƿ�ǩԼ',
  					sortable : true,
  					width : 70
              },{
    				name : 'orderstate',
  					display : 'ֽ�ʺ�ͬ״̬',
  					sortable : true,
  					width : 100
              },{
    				name : 'parentOrder',
  					display : '����ͬ����',
  					sortable : true,
  					hide : true
              }, {
					name : 'objCode',
					display : 'ҵ����',
					width : 150
				}
          ],
		//��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id="
							+ row.id + "&skey="+row['skey_'])
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : '�༭',
			icon : 'edit',
            showMenuFn : function(row) {
				if (row.ExaStatus == 'δ����' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=contract_rental_rentalcontract&action=init&id="
							+ row.id + "&skey="+row['skey_'])
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '�ύ���',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == 'δ����' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/contract/rental/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		},{
				text : '�������',
				icon : 'view',
	            showMenuFn : function (row) {
	               if (row.ExaStatus=='����'){
	                   return true;
	               }
	                   return true;
	            },
				action : function(row) {

	                     showThickboxWin('controller/contract/rental/readview.php?itemtype=oa_sale_lease&pid='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');

				}
			},{
			text : '����',
			icon : 'add',
//			showMenuFn : function (row){
//				   if(row.exportOrder == 1){
//				       return true;
//				   }
//				       return false;
//				},
			action: function(row){
				     window.open ('?model=contract_common_allcontract&action=importCont&id='
				                      + row.id
				                      +'&type=oa_sale_lease'
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		   },{
			text : '�����ϴ�',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.id
				                      +'&type=oa_sale_lease'
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		   }

	     ],
		//��������
		searchitems : [{
			display : '��ͬ����',
			name : 'orderName'
		},{
			display : '��ͬ���',
			name : 'orderCodeOrTempSearch'
		}, {
					name : 'objCode',
					display : 'ҵ����',
					width : 150
				}],
		// title : '�ͻ���Ϣ',

		sortname : "createTime",
		//��ʾ�鿴��ť
		isViewAction : false,
		//						isAddAction : true,
		isEditAction : false,
		isDelAction : false

	});

});