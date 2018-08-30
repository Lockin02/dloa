var show_page = function(page) {
	$("#cluesGrid").yxgrid("reload");
};
$(function() {
	$("#cluesGrid").yxgrid({
		model : 'projectmanagent_clues_clues',
		title : '售前线索',
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
		/**
		 * 是否显示添加按钮/菜单
		 *
		 * @type Boolean
		 */
		isAddAction : false,
        customCode : 'cluesgrid',
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
		},{

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
		}, {

			text : '指定跟踪人',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toTrackman&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');

			}
		}, {

			text : '转为商机',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},

			action : function(row) {
                showOpenWin("?model=projectmanagent_chance_chance&action=toAdd&id="
				              +row.id
				              + "&skey="+row['skey_']
				              +"&perm=clues");

           }
		}, {

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
						+ "&skey="+row['skey_']
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
			name : 'status',
			display : '状态',
			process : function(v) {
				if (v == 0) {
					return "正常";
				}else if(v == 1){
					return "已转商机";
				}else if(v == 2){
					return "关闭";
				}else if(v == 3){
					return "暂停";
				}
			},
			sortable : true,
			width : 70
//			datacode : 'XSZT'
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			process : function(v) {
				return v.substr(0, 10);
			}
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true
		}, {
			name : 'trackman',
			display : '线索跟踪人',
			sortable : true,
			width : 300
		}],
		buttonsEx : [{
			name : 'export',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				var status = $("#status").val();
				var i = 1;
				var colId = "";
				var colName = "";
				$("#cluesGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})
				window.open("?model=projectmanagent_clues_clues&action=exportExcel&colId="
								+ colId + "&colName=" + colName
								+ "&status=" + status
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
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
		}]
	});
});