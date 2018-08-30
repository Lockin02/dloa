Ext.namespace('Ext.business.combogrid');

var typeArr = new Array();
typeArr[0] = '外部';
typeArr[1] = '内部';
Ext.business.combogrid.TeamComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
	// 初始化组件
	initComponent : function() {
		this.init();
		Ext.business.combogrid.TeamComboGrid.superclass.initComponent
				.call(this);
	},
	init : function() {
		// 定义数据结构
		this.structure = [{
			name : 'id',
			header : 'id',
			hidden : true
		}, {
			name : 'memberId',
			header : '成员Id',
			hidden : true
		}, {
			name : 'memberName',
			header : '成员名称'
		}, {
			name : 'projectId',
			header : '项目Id',
			hidden : true
		}, {
			name : 'isInternal',
			header : '成员类型',
			fobj : typeArr,
			renderer : function(v) {
				return typeArr[v];
			}
		}];
	},
	urlAction : 'index1.php?model=rdproject_team_rdmember=',// 提交的action前缀
	boName : '所属计划'

});
Ext.reg('teamComboGrid', Ext.business.combogrid.TeamComboGrid);