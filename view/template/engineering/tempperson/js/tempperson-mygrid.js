var show_page = function(page) {
	$("#temppersonGrid").yxgrid("reload");
};
$(function() {
	$("#temppersonGrid").yxgrid({
		model : 'engineering_tempperson_tempperson',
		action : 'myPageJson',
		title : '临聘人员库',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'personName',
				display : '姓名',
				sortable : true,
				width : 80,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_tempperson_tempperson&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'idCardNo',
				display : '身份证',
				sortable : true,
				width : 140
			}, {
				name : 'country',
				display : '国家',
				sortable : true,
				hide : true,
				width : 80
			}, {
				name : 'province',
				display : '籍贯(省)',
				sortable : true,
				width : 60
			}, {
				name : 'city',
				display : '籍贯(市)',
				sortable : true,
				width : 60
			}, {
				name : 'address',
				display : '家庭住址',
				sortable : true,
				width : 130
			}, {
				name : 'phone',
				display : '手机',
				sortable : true
			}, {
				name : 'specialty',
				display : '专长',
				sortable : true,
				width : 120
			}, {
				name : 'ability',
				display : '能力',
				sortable : true,
				width : 120
			}, {
				name : 'allDays',
				display : '累计工作天数',
				sortable : true,
				width : 80
			}, {
				name : 'allMoney',
				display : '累计支付费用',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				},
				hide : true
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				hide : true
			}],
		toAddConfig : {
			formHeight : 450
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 450
		},
		toViewConfig : {
			action : 'toView'
		},
		toDelConfig : {
			text : '删除',
			/**
			 * 默认点击删除按钮触发事件
			 */
			toDelFn : function(p, g) {
				var rows = g.getCheckedRows();
				var key = "";
				for (var i = 0; i < rows.length; i++) {
					if(rows[i].allDays*1 != 0){
						alert('临聘人员 ['+ rows[i].personName +'] 已存在相关聘用记录，不能进行删除操作');
						return false;
					}
				}
				if (rows) {
					if (window.confirm("确认要删除?")) {
						$.ajax({
							type : "POST",
							url : "?model=" + p.model + "&action="
									+ p.toDelConfig.action
									+ p.toDelConfig.plusUrl,
							data : {
								id : g.getCheckedRowIds().toString(),
								skey : key
							},
							success : function(msg) {
								if(msg == 1){
									alert('删除成功');
									show_page();
								}else if(msg == 0){
									alert('删除失败');
								}
							}
						});
					}
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		searchitems : [{
			display : "姓名",
			name : 'personNameSearch'
		},{
			display : "身份证",
			name : 'idCardNoSearch'
		},{
			display : "手机号",
			name : 'phoneSearch'
		}]
	});
});