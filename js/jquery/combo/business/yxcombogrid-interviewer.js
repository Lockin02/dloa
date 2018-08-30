/**
 * ������������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_interviewer', {
				options : {
					hiddenId : 'id',
					nameCol : 'interviewerName',
					event : {
						clear : function(){
							$("#useWriteEva").val('');
							$("#interviewEva").val('');
						}
					},
					gridOptions : {
						showcheckbox : true,
						model : 'hr_recruitment_interviewer',
						//����Ϣ
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'interviewerName',
			          					display : '���Թ�',
			          					sortable : true
		                          },{
		            					name : 'interviewerId',
		              					display : 'interviewerId',
		              					hide : true
		                          },{
		            					name : 'interviewerType',
		              					display : '���Թ�����',
		              					sortable : true,
		              					hide:true
		                          }],
						// ��������
						searchitems : [{
									display : '���Թ�',
									name : 'interviewerName'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);