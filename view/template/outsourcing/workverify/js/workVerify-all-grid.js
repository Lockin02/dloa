var show_page = function(page) {
	$("#workVerifyGrid").yxgrid("reload");
};
$(function() {
			$("#workVerifyGrid").yxgrid({
				model : 'outsourcing_workverify_workVerify',
				isEditAction:false,
				isAddAction:false,
				isDelAction:false,
				showcheckbox:false,
				bodyAlign:'center',
				param:{'statusArr':'1,2,3,4,5'},
               	title : '������ȷ�ϵ�����',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'formCode',
                  					display : '���ݱ��',
                  					width:150,
                  					sortable : true,
									process : function(v,row){
											return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_workverify_workVerify&action=toView&id=" + row.id +"\")'>" + v + "</a>";
									}
                              },{
                    					name : 'status',
                  					display : '״̬',
                  					width:70,
                  					sortable : true,
									process:function(v){
											if(v=="1"){
												return "�ύ����";
											}else if(v=="2"){
												return "����ȷ��";
											}else if(v=="3"){
												return "��ȷ��";
											}else if(v=="4"){
												return "�ر�";
											}else if(v=="5"){
												return "�������";
											}else {
												return "δ�ύ";
											}
									}
                              },{
                    					name : 'formDate',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '���ڿ�ʼ����',
                  					sortable : true
                              },{
                    					name : 'endDate',
                  					display : '���ڽ�������',
                  					sortable : true
                              } ,{
                    					name : 'createName',
                  					display : '������',
                  					width:70,
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					width:450,
                  					sortable : true
                              }],

                              //��������
			comboEx : [{
				text : '״̬',
				key : 'status',
				data : [{
						text : '�ύ����',
						value : '1'
					},{
						text : '�������',
						value : '5'
					},{
						text : '����ȷ��',
						value : '2'
					},{
						text : '��ȷ��',
						value : '3'
					},{
						text : '�ر�',
						value : '4'
					}]
				}
			],
					// ��չ�Ҽ��˵�

		menusEx : [
			{
				text : '����',
				icon : 'excel',
				action :function(row,rows,grid) {
					if(row){
						location="?model=outsourcing_workverify_workVerify&action=exportWorkVerify&id="+row.id+"&skey="+row['skey_'];
					}else{
						alert("��ѡ��һ������");
					}
				}

			}],
		toViewConfig : {
//			action : 'toView',
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=outsourcing_workverify_workVerify&action=toView&id=" + get[p.keyField]);
				}
			}
		},
		searchitems : [{
					display : "���ݱ��",
					name : 'formCode'
				},{
					display : "������",
					name : 'createName'
				}]
 		});
 });