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
		header : ' ',
		headerIndex : 'warpRateMig'
	},{
		header : '���',
		headerIndex : 'code',
		align : 'left',
		// valΪĬ�ϴ���ֵ,row��ǰ�ж���,cellΪ��ǰ��,tcΪ������
		render : function(val, row, cell, tc) {
			if ( showMenuFnG(row) ) {// �������ϣ�ǰ�����ͼ��
				return "<img src='" + tc.imgPath + "group.gif'>" + val;
			}else{
				return "<img src='" + tc.imgPath + "project.gif'>" + val;
			}
		}
	}, {
		header : '����',
		headerIndex : 'name',
		align : 'left',
		// valΪĬ�ϴ���ֵ,row��ǰ�ж���,cellΪ��ǰ��,tcΪ������
		render : function(val, row, cell, tc) {
			if ( showMenuFnG(row) ) {// �������ϣ�ǰ�����ͼ��
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
		headerIndex : 'statusCN'
	}, {
		header : "ƫ����",
		headerIndex : 'warpRate'
	}, {
		header : "�����",
		headerIndex : 'effortRate'
	}, {
		header : "��ǰ��̱�",
		headerIndex : 'pointName'
	}, {
		header : "��Ŀ����",
		headerIndex : 'projectTypeC'
	}, {
		header : "�ƻ���������",
		headerIndex : 'planDateStart'
	}, {
		header : "�ƻ��ر�����",
		headerIndex : 'planDateClose'
	}];
	var statusStr = $('#statusStr').val();
	var url='';
	if(statusStr.length==0){
		var url='';
	}else{
		url='&'+statusStr+'=1';
	}
	var content = {
		iconShowIndex : 1,
		columnModel : GridColumnType,
		//dataUrl : "?model=rdproject_group_rdgroup&action=ajaxGroupByParentMine&parentId=-1",
		dataUrl : "?model=rdproject_project_rdproject&action=myProjectAndGroup&projectType=" + $('#projectType').val() + url,
		lazyLoadUrl : "?model=rdproject_project_rdproject&action=myProjectAndGroup",
		//lazyLoadUrl : "?model=rdproject_group_rdgroup&action=ajaxGroupAndProjectMine",
		idColumn : 'oid',// id���ڵ���,һ��������(��һ��Ҫ��ʾ����)
		parentColumn : 'oParentId', // ������id
		pageBar : true,// ��ʾҪչʾ��ҳ����Ҳ���ǻ���ַ�ҳ��Ч��
		pageSize : 15,
		debug : false,//����һ��չʾ������ļ�����Ϣ��div��
		analyzeAtServer : false,// ������dataUrl���Ե�ʱ�������������false��ʾ�������νṹ��ǰ̨���У�Ĭ���Ǻ�̨��������֧��java��,������json��ʽ���ã�
		multiChooseMode : 5,// ѡ��ģʽ������1��2��3��4��5�֡�
		tableId : 'testTable',// �������id
		checkOption : '',// 1:���ֵ�ѡ��ť,2:���ֶ�ѡ��ť,����:������ѡ��ť
		rowCount : true,//Ĭ����û����һ��
		explandAll : true,//ȫչ
		hidddenProperties : ['projectName','status','type','managerId'],// ����������һ���е�����,�ʺϴ���ֵ��һ�ַ�ʽ.
		contextMenu : {
			// width : 150,
			// alias:"1-1",
			items : [{
						text : "����",
						alias : "1-1",
						showMenuFn : function(obj){
							if(showMenuFnP(obj)&&obj.status!=7&&obj.status!=8  ){
								return true;
							}else{
								return false;
							}
						},
						icon: oa_cMenuImgArr['open'],
						action : function(row) {
//							showThickboxWin('?model=rdproject_project_rdproject&action=rpOpenManage&pjId='
//										+ row.pid
//										+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=950');
						showOpenWin('?model=rdproject_project_rdproject&action=rpOpenManage&pjId='+ row.pid);

						}
					},{
						text : "��������",
						alias : "1-5",
//						showMenuFn : function(obj,row){
//							if(showMenuFnP(obj)&&obj.status!=7&&obj.status!=8&&$('#userId').val()==obj.managerId  ){
//								return true;
//							}else{
//								return false;
//							}
//						},
						icon: oa_cMenuImgArr['open'],
						action : function(row) {
//							showThickboxWin('?model=rdproject_project_rdproject&action=rpOpenManage&pjId='
//										+ row.pid
//										+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=950');
						showOpenWin('?model=rdproject_task_rdtask&action=toImportExcelbypro&pjId='+ row.pid
										+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=500');

						}
					},{
						text : "�鿴",
						alias : "1-2",
						icon: oa_cMenuImgArr['read'],
						action : function(row) {
							//�����Ƿ�����Ŀ�����ж�����Ŀ�������
							if ( showMenuFnG(row) ) {
								showOpenWin('?model=rdproject_group_rdgroup&action=rgRead&gpId='
										+ row.pid + '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
							} else {
								showOpenWin('?model=rdproject_project_rdproject&action=rpRead&pjId='
										+ row.pid);
							}
							// alert(t.pid)
						}
					},{
						text : "�ر���Ŀ",
						alias : "1-3",
						icon: oa_cMenuImgArr['close'],
						showMenuFn : function(obj){
							if(showMenuFnP(obj)&&obj.status!=7&&obj.status!=8  ){
								return true;
							}else{
								return false;
							}
						},
						action : function(row) {
							showThickboxWin('?model=rdproject_project_rdproject&action=rpCloseTo&pjId='
									+ row.pid
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
						}
					}, {
						text : "�鿴����",
						alias : "1-7",
						icon: oa_cMenuImgArr['readExa'],
						showMenuFn : showMenuFnP,
						action : function(row) {
								var url = "controller/common/readview.php?itemtype=oa_rd_project&pid=" + row.pid +
										"&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750";
								showThickboxWin( url );
						}
					}
//					,{
//						//��2010��10��25����ӣ�
//						text : "����Ϊģ��",
//						alias : "1-8",
//						icon: oa_cMenuImgArr['add'],
//						showMenuFn : function(obj){
//							if(showMenuFnP(obj) ){
//								return true;
//							}else{
//								return false;
//							}
//						},
//						action : function(row) {
//							showThickboxWin('?model=rdproject_template_rdprjtemplate&action=toSetAsTemplate&pjId='
//									+ row.pid
//									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=230&width=650');
//						}
//					}

			]
		},
		onLazyLoadSuccess : function(gt) {
			// alert('������ִ������..');
		},
		onSuccess : function(gt) {
			// alert('���μ��ر����ִ������..');
		},
		onPagingSuccess : function(gt) {
			// alert('��ҳִ������..');
		},
		lazy : true,// ʹ��������ģʽ����ʱ��ȫ�����ر�ȫ�����ܲ���ʹ�ã�
		leafColumn : 'leaf',// �����жϽڵ��ǲ�����Ҷ
		el : 'newtableTree'// Ҫ������Ⱦ��div id
	};
	myTree.loadData(content);
	myTree.makeTable();
}


//�ж��Ƿ������
function showMenuFnG(obj){
	if(obj.type==2){
		return true;
	}else{
		return false;
	}
}

//�ж��Ƿ�����Ŀ
function showMenuFnP(obj){
	if(obj.type==1){
		return true;
	}else{
		return false;
	}
}

function show_page(page) {
	myTree._reload();
}