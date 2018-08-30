var show_page = function(page) {
	$("#scoreGrid").yxgrid("reload");
};
$(function() {
	$("#scoreGrid").yxgrid({
		model : 'hr_certifyapply_score',
		action : 'myPageJson',
		title : '我的任职资格评委打分',
		isAddAction : false,
		isDelAction : false,
		isViewAction : false,
		isEditAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '评价员工',
				sortable : true
			}, {
				name : 'userAccount',
				display : '评价员工帐号',
				sortable : true,
				hide : true
			}, {
				name : 'managerName',
				display : '评审人',
				sortable : true
			}, {
				name : 'managerId',
				display : '评审人员id',
				sortable : true,
				hide : true
			}, {
				name : 'scoreId',
				display : '评分表id',
				sortable : true,
				hide : true
			}, {
				name : 'assessDate',
				display : '评审日期',
				sortable : true
			}, {
				name : 'status',
				display : '评价表状态',
				sortable : true,
				process : function(v){
					switch(v){
						case '0' : return '保存';break;
						case '1' : return '认证准备中';break;
						case '2' : return '审批中';break;
						case '3' : return '完成待评分';break;
						case '4' : return '完成已评分';break;
						case '5' : return '确认审核中';break;
						case '6' : return '审核已完成';break;
						default : return v;
					}
				}
			}, {
				name : 'scoreStatus',
				display : '评分状态',
				sortable : true,
				process : function(v){
					if(v == '0'){
						return '未评分';
					}else{
						return '已评分';
					}
				}
			}, {
				name : 'score',
				display : '得分'
			}],
		menusEx : [{
			name : 'view',
			text : "查看认证材料",
			icon : 'view',
			action : function(row) {
				//判断
				showModalWin("?model=hr_certifyapply_cassess&action=toView&id=" + row.id + "&skey=" + row.skey);
			}
		},{
			name : 'add',
			text : "新建评分",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.scoreStatus == '0' && row.status == '3') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_certifyapply_score&action=toScore&cassessId=" + row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			name : 'view',
			text : "查看评分",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.scoreStatus != '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_certifyapply_score&action=toView&id=" + row.scoreId
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			name : 'edit',
			text : "修改评分",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.scoreStatus != '0' && (row.status == '3' ||row.status == '4') ) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_certifyapply_score&action=toEdit&id=" + row.scoreId
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			name : 'edit',
			text : "调整评分",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '3' || row.status == '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.managerId == row.scoreManagerId){
					alert('请选择主审评委和参与评委');
					return false;
				}
				//判断
				showModalWin("?model=hr_certifyapply_cassess&action=toInScore&id=" + row.id + "&skey=" + row.skey);
			}
		}],
		searchitems : [{
			display : "评价员工",
			name : 'suserNameSearch'
		}]
	});
});