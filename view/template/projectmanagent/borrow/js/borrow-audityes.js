/**��Ʊ�����б�**/

var show_page=function(page){
   $("#borrowGrid").yxgrid("reload");
};

$(function(){
        $("#borrowGrid").yxgrid({

        	model:'projectmanagent_borrow_borrow',
        	action:'pageJsonAuditYes',
        	title:'���������۶���',
        	isToolBar:false,
        	showcheckbox : false,
        	isViewAction:false,
        	isAddAction:false,
        	isEditAction:false,
        	isDelAction:false,

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
                    					name : 'limits',
                  					display : '��Χ',
                  					sortable : true
                              },{
                    					name : 'beginTime',
                  					display : '��ʼ����',
                  					sortable : true
                              },{
                    					name : 'closeTime',
                  					display : '��ֹ����',
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
										display : '����ʱ��',
										name : 'ExaDT',
										width:80
									}],


					//��չ�Ҽ��˵�
					menusEx : [
					{
						text : '�鿴',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin("?model=projectmanagent_borrow_borrow&action=init&perm=view&id="+ row.borrowId + "&skey="+row['skey_']
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800"
							);
						}
					}
					],
			searchitems:[
			        {
			            display:'���',
			            name:'Code'
			        }
			        ],
			sortname:'id',
			sortorder:'DESC'
        });
});