var show_page = function(page) {
	$("#MyBorrowGrid").yxgrid("reload");
};
$(function() {
			$("#MyBorrowGrid").yxgrid({
			    model : 'projectmanagent_borrow_borrow',
//			    action : 'listForChance' ,
                param : {"chanceId" : $("#chanceId").val()},
               	title : '�ҵĽ�����',
               	//��ť
				isViewAction : false,
				isAddAction : false,
				isEditAction : false,
				isDelAction : false,

						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'chanceId',
                  					display : '�̻�Id',
                  					sortable : true,
                  					hide : true
                              },{
                    					name : 'Code',
                  					display : '���',
                  					sortable : true
                              },{
                    					name : 'Type',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'customerName',
                  					display : '�ͻ�����',
                  					sortable : true
                              },{
                    					name : 'salesName',
                  					display : '���۸�����',
                  					sortable : true
                              },{
                    					name : 'scienceName',
                  					display : '����������',
                  					sortable : true
                              },{
									name : 'ExaStatus',
				  					display : '����״̬',
				  					sortable : true,
				  					width : 90
				              },{
									name : 'ExaDT',
				  					display : '����ʱ��',
				  					sortable : true
				              },{
				                    name : 'createName',
				                    display : '������',
				                    sortable : true
				              }],
                               		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=init&perm=view&id=" + row.id + "&skey="+row['skey_']);
//					showThickboxWin("?model=projectmanagent_borrow_borrow&action=init&id=" + row.id
//									+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
//					);
				}
			}

		},{
			text : '�༭',
			icon : 'edit',
            showMenuFn : function(row){
				if(row.ExaStatus == 'δ����' || row.ExaStatus == '���'){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {

					showOpenWin("?model=projectmanagent_borrow_borrow&action=init&id="
							+ row.id + "&skey="+row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			text : '�ύ���',
			icon : 'add',
			showMenuFn : function(row){
				if(row.ExaStatus == 'δ����' || row.ExaStatus == '���'){
					return true;
				}
				return false;
			},
			action : function(row,rows,grid){
				if (row) {
					showThickboxWin('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId='
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		}]
 		});



 });