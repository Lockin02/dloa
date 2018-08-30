/**
 * 下拉评估方案表格组件
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
						//列信息
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'interviewerName',
			          					display : '面试官',
			          					sortable : true
		                          },{
		            					name : 'interviewerId',
		              					display : 'interviewerId',
		              					hide : true
		                          },{
		            					name : 'interviewerType',
		              					display : '面试官类型',
		              					sortable : true,
		              					hide:true
		                          }],
						// 快速搜索
						searchitems : [{
									display : '面试官',
									name : 'interviewerName'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);