Ext.namespace('Ext.business.combogrid');

var typeArr = new Array();
typeArr[0] = '�ⲿ';
typeArr[1] = '�ڲ�';
Ext.business.combogrid.TeamComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
	// ��ʼ�����
	initComponent : function() {
		this.init();
		Ext.business.combogrid.TeamComboGrid.superclass.initComponent
				.call(this);
	},
	init : function() {
		// �������ݽṹ
		this.structure = [{
			name : 'id',
			header : 'id',
			hidden : true
		}, {
			name : 'memberId',
			header : '��ԱId',
			hidden : true
		}, {
			name : 'memberName',
			header : '��Ա����'
		}, {
			name : 'projectId',
			header : '��ĿId',
			hidden : true
		}, {
			name : 'isInternal',
			header : '��Ա����',
			fobj : typeArr,
			renderer : function(v) {
				return typeArr[v];
			}
		}];
	},
	urlAction : 'index1.php?model=rdproject_team_rdmember=',// �ύ��actionǰ׺
	boName : '�����ƻ�'

});
Ext.reg('teamComboGrid', Ext.business.combogrid.TeamComboGrid);