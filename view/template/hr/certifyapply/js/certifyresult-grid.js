var show_page = function(page) {
	$("#certifyresultGrid").yxgrid("reload");
};
$(function() {
	$("#certifyresultGrid").yxgrid({
		model : 'hr_certifyapply_certifyresult',
		title : '任职资格审核表',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
					display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'periodName',
				display : '周期名称',
				sortable : true,
				hide : true
			}, {
				name : 'careerDirection',
				display : '职业发展通道',
				sortable : true,
				hide : true
			}, {
				name : 'careerDirectionName',
				display : '通道名称',
				sortable : true
			}, {
				name : 'formDate',
				display : '填报日期',
				sortable : true
			}, {
				name : 'formUserId',
				display : '填表人',
				sortable : true,
				hide : true
			}, {
				name : 'formUserName',
				display : '填表人',
				sortable : true
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true
			}, {
				name : 'ExaDT',
				display : '审批日期',
				sortable : true
			}, {
				name : 'status',
				display : '单据状态',
				sortable : true,
				process : function(v){
					switch(v){
						case '0' : return '保存';break;
						case '1' : return '审批中';break;
						case '2' : return '完成';break;
						default : return v;
					}
				}
			}, {
				name : 'createId',
				display : '创建人Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				width : 130
			}, {
				name : 'updateId',
				display : '修改人Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}],
		toViewConfig : {
			action : 'toView',
			showMenuFn : function(row) {
				return true;
			},
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//判断
					showModalWin("?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
        //过滤数据
		comboEx:[{
		     text:'审批状态',
		     key:'ExaStatus',
		     type : 'workFlow'
	   }],
		searchitems : [{
			display : "职业发展通道",
			name : 'careerDirectionNameSearch'
		}]
	});
});