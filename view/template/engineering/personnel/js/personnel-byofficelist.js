// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".byOfficeGrid").yxgrid("reload");
};
$(function() {
	$(".byOfficeGrid").yxgrid({
		model: 'engineering_personnel_personnel',
		action : 'pageJsonOff',
		param : { 'rprocode' : $('#procode').val() },
		title: "员工信息  " + $('#provinceName').val(),
		showcheckbox: false,
		isEditAction: false,
		isAddAction: false,
		isDelAction: false,
		isViewAction: false,
		comboEx: [{
			text: "是否在项目",
			key: 'isProj',
			data : [{
				text : '无项目',
				value : '2'
				}, {
				text : '项目中',
				value : '1'
				}
			]
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_personnel_personnel&action=viewTab&id=" + row.id + "&perm=view");
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "初始化",
			icon : 'edit',
			/**
			 * row 最后一条选中的行 rows 选中的行（多选） rowIds
			 * 选中的行id数组 grid 当前表格实例
			 */
			action : function(row) {
				location='?model=engineering_personnel_personnel&action=byOfficeList'
			}
		}],

		// 列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			display: '姓名',
			name: 'userName',
			sortable: true
			// 特殊处理字段函数
		},
		{
			display: '归属',
			name: 'officeName',
			sortable: true,
			width : 70
		},
		{
			display: '当前项目',
			name: 'currentProName',
			sortable: true,
			process : function(v) {
					if(v==""){
						return "无";
					}else{
						return v;
				}
			},
			width: 180
		},
		{
			display: '（预计）结束',
			name: 'proEndDate',
			sortable: true,
			width: 90,
			process : function(v) {
					if(v=="0000-00-00" || v==""){
						return "无";
					}else{
						return v;
				}
			}
		},
		{
			display: '所在地',
			name: 'locationName',
			sortable: true,
			width: 50
		},
		{
			display: '考勤',
			name: 'attendStatus',
			datacode :  'KQZT',
			sortable: true,
			width: 40
		}],
		// 快速搜索
		searchitems: [{
			display: '姓名',
			name: 'userName'
		}],
		// 默认搜索顺序
		sortorder: "ASC"

	});
});