var show_page = function(page) {
	$("#assessrecordsGrid").yxgrid("reload");
};
$(function() {
		var userAccount = $("#userAccount").val();
		var userNo = $("#userNo").val();
		$("#assessrecordsGrid").yxgrid({
				model : 'hr_assess_assessrecords',
               	title : '���ȿ�����Ϣ',
               	showcheckbox:false,
               	isAddAction:false,
               	isEditAction:false,
               	isDelAction:false,
               	isViewAction:false,
				isOpButton:false,
				bodyAlign:'center',
               	param:{"userNo":userNo},
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'userNo',
                  					display : 'Ա�����',
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'userName',
                  					display : '��������',
//									process : function(v,row){
//										return "<a href='#' onclick='showThickboxWin(\"?model=administration_appraisal_performance_list&action=perExIn&keyId=" + row.id+ '&tplId=' + row.tpl_id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=550&width=900\")'>" + v + "</a>";
//									},
                  					width:60,
                  					sortable : true
                              },{
                    					name : 'deptName',
                  					display : 'ֱ������',
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'deptNameS',
                  					display : '��������',
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'deptNameT',
                  					display : '��������',
                  					width:80,
                  					sortable : true
                              },{
                                name : 'deptNameF',
                                display : '�ļ�����',
                                width:80,
                                sortable : true
                            },{
                    					name : 'jobName',
                  					display : 'ְ��',
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'years',
                  					display : '�������',
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'quarter',
                  					display : '��������',
									process : function(v,row){
										switch (v)
											{
												case '1':
												    return 'һ����';
												  break;
												case '2':
												   return  '������';
												  break;
												case '3':
												   return  '������';
												  break;
												case '4':
												   return  '�ļ���';
												  break;
												case '5':
												   return  '�ϰ���';
												  break;
												case '6':
												   return  '�°���';
												  break;
												case '7':
												   return  'ȫ��';
												  break;
									   	   }
									},
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'count_my_fraction',
                  					display : '�����ܷ�',
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'assessName',
                  					display : '������',
                  					width:60,
                  					sortable : true
                              },{
                    					name : 'count_assess_fraction',
                  					display : '�����ܷ�',
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'auditName',
                  					display : '�����',
                  					width:60,
                  					sortable : true
                              },{
                    					name : 'count_audit_fraction',
                  					display : '����ܷ�',
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'countFraction',
                  					display : '�ܷ�',
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'deptRank',
                  					display : '��������������',
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'deptRankPer',
                  					display : '������������������(ǰ)',
                  					width:150,
                  					sortable : true
                              }],
							  lockCol:['userNo','userName'],//����������
                              menusEx : [{
											text : '�鿴',
											icon : 'view',
											action : function(row) {
												showThickboxWin('?model=administration_appraisal_performance_list&action=perExIn&keyId='+row.id+'&tplId='
														+ row.tpl_id
														+ "&placeValuesBefore&TB_iframe=true&modal=false&height=550&width=800");
											}
										}]
 		});
 });