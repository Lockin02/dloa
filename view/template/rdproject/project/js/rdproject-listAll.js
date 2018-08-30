function show_page(page) {
	myTree._reload();
}

/*
 * ������Ŀ��ϸ���Ŀ
 */
function search() {
	var searchfield = $('#searchfield').val();
	var searchvalue = $('#searchvalue').val();
	var param = {};
	if (searchfield != '')
		param[searchfield] = searchvalue;
	myTree._searchGrid(param);
}

var myTree = new GridTree();

/**
 * ��Ҫ�Ĳ��Է���
 */
function newProjectTreeGrid() {
	var GridColumnType = [
			/**
			 * { header : 'id', headerIndex : 'id', width : '10%' },
			 */
			{
		header : '����',
		headerIndex : 'name',
		align : 'left',
		// valΪĬ�ϴ���ֵ,row��ǰ�ж���,cellΪ��ǰ��,tcΪ�������
		render : function(val, row, cell, tc) {
			if (!row.projectName) {// �������ϣ�ǰ�����ͼ��
				return "<img src='" + tc.imgPath + "group.gif'>" + val;
			}else{
				return "<img src='" + tc.imgPath + "project.gif'>" + val;
			}
		}
	}, {
		header : '��������',
		headerIndex : 'depName'
	}, {
		header : "������",
		headerIndex : 'managerName'
	}, {
		header : "״̬",
		headerIndex : 'status'
	}, {
		header : "ƫ����",
		headerIndex : 'warpRate'
	}, {
		header : "��Ͷ�빤����",
		headerIndex : 'workload'
	}, {
		header : "��ǰ��̱�",
		headerIndex : 'milestonePoint'
	}, {
		header : "���",
		headerIndex : 'businessCode'
	}, {
		header : "��Ŀ����",
		headerIndex : 'projectType'
	}, {
		header : "�ƻ���������",
		headerIndex : 'planDateStart'
	}, {
		header : "�ƻ��ر�����",
		headerIndex : 'planDateClose'
	}];
	var content = {
		columnModel : GridColumnType,
		dataUrl : "?model=rdproject_group_rdgroup&action=pageGroupAndProjectAll",
		//lazyLoadUrl : "?model=rdproject_group_rdgroup&action=pageGroupAndProject",
		idColumn : 'oid',// id���ڵ���,һ��������(��һ��Ҫ��ʾ����)
		parentColumn : 'oParentId', // ������id  parentId
		pageBar : true,
		pageSize : 15,
		pageBar : true,
		debug : false,
		analyzeAtServer : false,// ������dataUrl���Ե�ʱ�������������false��ʾ�������νṹ��ǰ̨���У�Ĭ���Ǻ�̨��������֧��java��,������json��ʽ���ã�
		multiChooseMode : 5,// ѡ��ģʽ������1��2��3��4��5�֡�
		tableId : 'testTable',// ��������id
		checkOption : '',// 1:���ֵ�ѡ��ť,2:���ֶ�ѡ��ť,����:������ѡ��ť
		rowCount : true,
		hidddenProperties : ['name', 'projectName'],// ����������һ���е�����,�ʺϴ���ֵ��һ�ַ�ʽ.
		contextMenu : {
			// width : 150,
			// alias:"1-1",
			items : [{
				text : "��",
				// ����true��ʾ�˵�������flase����ʾ�˵�
				// showMenuFn : function(row) {
				// return true;
				// },
				action : function(row) {
					// �����Ƿ�����Ŀ�����ж�����Ŀ�������
					if (!row.projectName) {
						showThickboxWin('?model=rdproject_group_rdgroup&action=rgUpdateTo&gpId='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					} else {
						showThickboxWin('?model=rdproject_project_rdproject&action=rpUpdateTo&pjId='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					}
					// alert(t.pid)
				}
			}, {
				text : "�������",
				alias : "1-2",
				showMenuFn : function(row) {
					if (row.projectName) {
						return false;
					}
					return true;
				}
			}, {
				text : "������Ŀ",
				alias : "1-3",
				showMenuFn : function(row) {
					if (row.projectName) {
						return true;
					}
					return false;
				}
			}, {
				text : "������Ŀ�˵���",
				alias : "1-4",
				type : 'group',
				showMenuFn : function(row) {
					if (row.projectName) {
						return true;
					}
					return false;
				},
				items : [{
							text : "������Ŀ1",
							alias : "1-4-1",
							showMenuFn : function(row) {
								if (row.projectName) {
									return true;
								}
								return false;
							}
						}]
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
		el : 'newtableTree'// Ҫ������Ⱦ��div id
	};
	myTree.loadData(content);
	myTree.makeTable();

	// չ��ȫ���ڵ�
	// _$('bt3').onclick=function(){myTree.expandAll();};
	// չ����һ��ڵ�
	// _$('bt4').onclick=function(){myTree.closeAll();};
}