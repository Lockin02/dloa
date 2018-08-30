// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".contactGrid").yxgrid("reload");
};
$(function() {
	$(".contactGrid").yxgrid({
		model : 'engineering_personnel_personnel',
		title : "员工信息",
			/**
			 * 表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 表单默认宽度
			 */
			formHeight : 550,
			/**
			 * 是否显示批量查看按钮/菜单
			 *
			 * @type Boolean
			 */
			isBatchAction : true,
			isAddAction: false,
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
			comboEx: [{
			text: "归属",
			key: 'officeId',
			data : [{
				text : ' 西安办事处 ',
				value : '46'
				}, {
				text : ' 成都办事处 ',
				value : '45'
				}, {
				text : ' 长沙办事处 ',
				value : '44'
				}, {
				text : ' 南京办事处 ',
				value : '43'
				}, {
				text : ' 沈阳办事处 ',
				value : '42'
				}, {
				text : ' 广州办事处 ',
				value : '41'
				}
			]
		},{
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
		//扩展按钮
		buttonsEx : [{
			name : 'Batch',
			text : '批量导入',
			icon : 'add',
			action : function(row,rows,grid) {
				   showThickboxWin("?model=engineering_personnel_personnel&action=batch" +
				   		"&placeValuesBefore&TB_iframe=true&modal=false" +
				   		"&height=400&width=800");
			}
		}],


		menusEx : [{
				name : 'edit',
				text : "编辑",
				icon : 'edit',
				action : function(row,rows,grid) {
							showThickboxWin("?model=engineering_personnel_personnel&action=init&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}, {
				name : 'view',
				text : "查看",
				icon : 'view',
				action : function(row,rows,grid) {
							showOpenWin("?model=engineering_personnel_personnel&action=viewTab&id="
							+ row.id
							+ "&userCode="
							+ row.userCode
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}],


		// 列信息
		colModel : [
				{
					display : 'id',
					name : 'id',
					hide : true
				},{
					display : 'userCode',
					name : 'userCode',
					hide : true
				}, {
					display : '姓名',
					name : 'userName',
					sortable : true
					// 特殊处理字段函数
				}, {
					display : '性别',
					name : 'sex',
					sortable : true,
					width : '35',
					align : 'center',
					process : function(v,row){
						if(row.sex == 0){
							return "男";
						}else{
							return "女";
						}
					}
				}
				, {
					display : '等级',
					name : 'userLevel',
					sortable : true,
					align : 'center',
					width : '35'
				}, {
					display : '当前项目',
					name : 'currentProName',
					sortable : true,
					process : function(v) {
							if(v==""){
								return "无";
							}else{
								return v;
						}
					},
					width : '200'
				}, {
					display : '项目（预计）结束时间',
					name : 'proEndDate',
					sortable : true,
					process : function(v) {
							if(v=="0000-00-00" || v==""){
								return "无";
							}else{
								return v;
						}
					},
					width : '130'
				}, {
					display : '归属',
					name : 'officeName',
					sortable : true,
					width : '80'
				}, {
					display : '所在地',
					name : 'locationName',
					sortable : true,
					width : '60'
				}, {
					display : '籍贯',
					name : 'originPlace',
					sortable : true,
					width : '60'
				}, {
					display : '考勤状态',
					name : 'attendStatus',
					sortable : true,
					datacode : 'KQZT',
					width : '60'
				}, {
					display : '资质数量',
					name : 'aptitudeNum',
					sortable : true,
					width : '60'
//				}, {
//					display : '入职日期',
//					name : 'checkDate',
//					sortable : true
				}, {
					display : '合同期',
					name : 'conYear',
					sortable : true,
					width : '60'
				}, {
					display : '直属上级',
					name : 'leaderName',
					sortable : true
				}, {
					display : '非津贴市',
					name : 'ncityName',
					sortable : true,
					width: 60
				}],
				// 快速搜索
				searchitems : [{
					display : '名称',
					name : 'userName'
				}, {
					display : '所属办事处',
					name : 'officeName'
				}, {
					display : '职位',
					name : 'positionCode'
				}],
				// 默认搜索顺序
				sortorder : "ASC"

			});
});