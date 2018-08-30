var show_page = function(page) {
	$("#orderGrid").yxgrid("reload");
};
$(function() {
	$("#orderGrid").yxgrid({
		model: 'projectmanagent_order_order',
		title: '订单列表',

           /**
			 * 是否显示查看按钮/菜单
			 *
			 * @type Boolean
			 */
			isViewAction : false,
			/**
			 * 是否显示修改按钮/菜单
			 *
			 * @type Boolean
			 */
			isEditAction : false,
			/**
			 * 是否显示删除按钮/菜单
			 *
			 * @type Boolean
			 */
			isDelAction : false,
			showcheckbox : false,
		// 扩展右键菜单

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=projectmanagent_order_order&action=toViewTab&id='
						+ row.id
                        +'&perm=view'
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		},
			{

			text : '指定负责人',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.status == '2') {
					return true;
				}
				return true;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toTrackman&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');

			}
		},

			{

			text : '关闭订单',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.status == '2') {
					return true;
				}
				return true;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toCluesclose&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');

			}
		}],

		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'orderCode',
			display: '订单编号',
			sortable: true
		},
		{
			name: 'orderName',
			display: '订单名称',
			sortable: true
		},
		{
			name: 'deliveryDate',
			display: '交货日期',
			sortable: true
		},
		{
			name: 'prinvipalName',
			display: '订单负责人',
			sortable: true
		},
		{
			name: 'state',
			display: '订单状态',
			sortable: true,
			process : function(v){
  						if( v == '0'){
  							return "未提交";
  						}else if(v == '1'){
  							return "保存";
  						}else if(v == '2'){
  							return "执行中";
  						}else if(v == '3'){
  							return "关闭";
  						}else if(v == '4'){
  							return "已生成订单";
  						}else if(v == '5'){
  							return "已签合同";
  						}
  					}
		},
		{
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true
		},
		{
			name: 'saleman',
			display: '销售员',
			sortable: true
		}],

		 /**
		 * 快速搜索
		 */
		searchitems : [{
			display : '订单名称',
			name : 'orderName'
		}]


	});
});