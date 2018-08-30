/**
 * 简历信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_resume', {
		isDown : false,
		setValue : function(rowData) {
			if (rowData) {
				var t = this, p = t.options, el = t.el;
				p.rowData = rowData;
				if (p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData.idArr;
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData.text;
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				} else if (!p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData[p.valueCol];
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData[p.nameCol];
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				}
			}
		},
		options : {
			hiddenId : 'id',
			nameCol : 'applicantName',
			closeCheck : false,// 关闭状态,不可选择
			gridOptions : {

				showcheckbox : false,
				model : 'hr_recruitment_resume',
				action : 'pageJson',
				bodyAlign:'center',
				param : {
	 				"resumeTypeArr" : "0,3,4,5,6"
				},
				pageSize : 10,
				// 列信息
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'resumeCode',
				display : '简历编号',
				sortable : true,
				process : function(v, row) {
						return '<a href="javascript:void(0)" title="点击查看简历" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toView&id='
									+ row.id
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
									+ "<font color = '#4169E1'>"
									+ v
									+ "</font>"
									+ '</a>';
					}
			}, {
				name : 'applicantName',
				display : '应聘者姓名',
				width:70,
				sortable : true
			}, {
				name : 'isInform',
				display : '面试通知',
				sortable : true,
				process : function(v,row){
				    if(v=="0"){
				       return "未通知面试";
				    }else if(v=="1"){
				       return "已通知面试";
				    }
				}
			}, {
				name : 'post',
				display : '应聘职位',
				sortable : true,
				datacode : 'YPZW'
			}, {
				name : 'phone',
				display : '联系电话',
				sortable : true
			}, {
				name : 'email',
				display : '电子邮箱',
				sortable : true,
				width : 200
			}, {
				name : 'resumeType',
				display : '简历类型',
				sortable : true,
				process : function(v,row){
				    if(v=="0"){
				       return "公司简历";
				    }else if(v=="1"){
				       return "在职简历";
				    }else if(v=="2"){
				       return "黑名单";
				    }else if(v=="3"){
				       return "储备简历";
				    }else if(v=="4"){
				       return "淘汰简历";
				    }else if(v=="5"){
				       return "在职简历";
				    }else if(v=="6"){
				       return "离职简历";
				    }
				}
			}],
						// 快速搜索
						searchitems : [{
									display : '应聘者姓名',
									name : 'applicantName'
								}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);