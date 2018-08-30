

var myTree = new GridTree();

/**
 * ��Ҫ�Ĳ��Է���
 */
function newRoleTreeGrid() {
	
	var GridColumnType = [
			/**
			 * { header : 'id', headerIndex : 'id', width : '10%' },
			 */
			{
		header : '����',
		headerIndex : 'roleName',
		align : 'left',
		// valΪĬ�ϴ���ֵ,row��ǰ�ж���,cellΪ��ǰ��,tcΪ�������
		render : function(val, row, cell, tc) {
			return "<img src='" + tc.imgPath + "user.gif'>" + val;
		}
	}, {
		header : '��ע',
		headerIndex : 'notes'
	}];
	var content = {
		columnModel : GridColumnType,
		dataUrl : "?model=engineering_role_rdrole&action=pageAll",
		idColumn : 'id',// id���ڵ���,һ��������(��һ��Ҫ��ʾ����)
		parentColumn : 'parentId', // ������id parentId
		pageBar : true,
		pageSize : 15,
		pageBar : true,
		debug : false,
		analyzeAtServer : false,// ������dataUrl���Ե�ʱ�������������false��ʾ�������νṹ��ǰ̨���У�Ĭ���Ǻ�̨��������֧��java��,������json��ʽ���ã�
		// multiChooseMode : 4,// ѡ��ģʽ������1��2��3��4��5�֡�
		tableId : 'testTable',// ��������id
		checkOption : '',// 1:���ֵ�ѡ��ť,2:���ֶ�ѡ��ť,����:������ѡ��ť
		rowCount : true,
		// hidddenProperties : ['name', 'projectName'],//
		// ����������һ���е�����,�ʺϴ���ֵ��һ�ַ�ʽ.
		contextMenu : {
			// width : 150,
			// alias:"1-1",
			items : [{
				text : "�༭",
				alias : "1-1",
				action : function(row) {
					showThickboxWin('?model=engineering_role_rdrole&action=init&id='
							+ row.pid
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=650');
				}
			}, {
				text : "ɾ��",
				alias : "1-2",
				action : function(row) {
					GridTree.prototype
							._delete('engineering_role_rdrole', row.pid);
				}
			}, {
				text : "��Ȩ",
				alias : "1-3",
				action : function(row) {
					showThickboxWin('?model=engineering_role_rdrole&action=authorize&id='
							+ row.pid
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600');
				}
			}]
		},
		onLazyLoadSuccess : function(gt) {
			// alert('������ִ������..');
		},
		onSuccess : function(gt) {
			// alert('���μ��ر�����ִ������..');
		},
		onPagingSuccess : function(gt) {
			// alert('��ҳִ������..');
		},
		lazy : false,// ʹ��������ģʽ����ʱ��ȫ�����ر�ȫ�����ܲ���ʹ�ã�
		leafColumn : 'leaf',// �����жϽڵ��ǲ�����Ҷ
		el : 'roleTableTree'// Ҫ������Ⱦ��div id
	};
	myTree.loadData(content);
	myTree.makeTable();

	// չ��ȫ���ڵ�
	// _$('bt3').onclick=function(){myTree.expandAll();};
	// չ����һ��ڵ�
	// _$('bt4').onclick=function(){myTree.closeAll();};
}