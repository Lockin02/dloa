var show_page = function(page) {
	$("#registerGrid").yxgrid("reload");
};
$(function() {
	$("#registerGrid").yxgrid({
		model : 'projectmanagent_clues_clues',
		param : {
			"createName" : $('#userName').val()
		},
		title : '我注册的线索',
		showcheckbox : false, // 是否显示checkbox
		/**
		 * 是否显示删除按钮/菜单
		 */
		isDelAction : false,
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
		isAddAction : false,
        //扩展按钮
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "新增",
			icon : 'add',

			action : function(row) {
				showOpenWin('?model=projectmanagent_clues_clues&action=toAdd');
			}
		}],

		// 扩展右键菜单

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=projectmanagent_clues_clues&action=toViewTab&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		},{

			text : '暂停',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toPause&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');

			}
		}, {
			text : '修改',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=projectmanagent_clues_clues&action=init&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {

			text : '填写跟踪记录',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_track_track&action=toCluesTrack&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}
//		, {
//
//			text : '商机申请',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.status == '0') {
//					return true;
//				}
//				return false;
//			},
//
//			action : function(row) {
//
//				parent.location="?model=projectmanagent_chance_chance&action=toAdd&id="
//				              +row.id
//				              +"&perm=clues";
//
//           }
//		}
		,{

			text : '恢复',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3') {
					return true;
				}
				return false;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toRecover&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');

			}
		},{

			text : '关闭线索',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toCluesclose&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');

			}
		}],

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'cluesName',
			display : '线索名称',
			sortable : true,
			width : 150
		}, {
			name : 'status',
			display : '状态',
			sortable : true,
			process : function(v){
//				switch(v){
//					case '0' : "正常";break;
//					case '1' : "已转商机";break;
//					case '2' : "关闭";break;
//				}
				if( v == '0' ){
					return "正常";
				}else if(v == '1'){
					return "已转商机";
				}else if(v == '2'){
					return "关闭";
				}else if(v == '3'){
					return "暂停";
				}
			},
			width : 80
//			datacode : 'XSZT'
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 80
		}, {
			name : 'customerType',
			display : '客户类型',
			sortable : true,
			datacode : 'KHLX'
		}, {
			name : 'customerProvince',
			display : '所属省份',
			sortable : true,
			width : 80
		}, {
			name : 'trackman',
			display : '线索跟踪人',
			sortable : true,
			width : 300
		}],
		comboEx : [ {
			text : '状态',
			key : 'status',
			data : [ {
				text : '正常',
				value : '0'
			}, {
				text : '已转商机',
				value : '1'
			},{
				text : '关闭',
				value : '2'
			},{
				text : '暂停',
				value : '3'
			}  ]
		}],
		 /**
		 * 快速搜索
		 */
		searchitems : [{
			display : '线索名称',
			name : 'cluesName'
		}],
		//设置新增页面宽度
          toAddConfig : {
			formHeight : 500 ,
			formWidth : 900
          },
          //设置编辑页面宽度
          toEditConfig : {
			formHeight : 500 ,
			formWidth : 900
          },
          //设置查看页面宽度
          toViewConfig : {
			formHeight : 500 ,
			formWidth : 900
          }
	});
});