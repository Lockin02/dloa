var show_page = function(page) {
	$("#trackGrid").yxgrid("reload");
};
$(function() {
			$("#trackGrid").yxgrid({
				      model : 'projectmanagent_track_track',
               	title : '���ټ�¼',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'cluesId',
                  					display : '����ID',
                  					sortable : true
                              },{
                    					name : 'cluesCode',
                  					display : '�������',
                  					sortable : true
                              },{
                    					name : 'cluesName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'chanceId',
                  					display : '�̻�ID',
                  					sortable : true
                              },{
                    					name : 'chanceName',
                  					display : '�̻�����',
                  					sortable : true
                              },{
                    					name : 'chanceCode',
                  					display : '�̻����',
                  					sortable : true
                              },{
                    					name : 'trackId',
                  					display : '������ID',
                  					sortable : true
                              },{
                    					name : 'trackName',
                  					display : '����������',
                  					sortable : true
                              },{
                    					name : 'trackDate',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'trackType',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'linkmanName',
                  					display : '��ϵ������',
                  					sortable : true
                              },{
                    					name : 'trackPurpose',
                  					display : '����Ŀ��',
                  					sortable : true
                              },{
                    					name : 'customerFocus',
                  					display : '�ͻ���ע��',
                  					sortable : true
                              },{
                    					name : 'result',
                  					display : '�Ӵ����',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '�޸�ʱ��',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '�޸�������',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '�޸���Id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '����������',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '������ID',
                  					sortable : true
                              }]
 		});
 });