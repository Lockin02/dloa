var myTree = new GridTree();

function myfun(jquery){

}($);

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
		dataUrl : "?model=rdproject_project_rdproject&action=projectAndGroup&parentId=-1&projectType=" + $('#projectType').val() + url,
		lazyLoadUrl : "?model=rdproject_project_rdproject&action=projectAndGroup",
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
		hidddenProperties : ['projectName','status','type'],// ����������һ���е�����,�ʺϴ���ֵ��һ�ַ�ʽ.
		contextMenu : {
			// width : 150,
			// alias:"1-1",
			items : [{
				text : "���",
				alias : "1-1",
				showMenuFn : showMenuFnG,
				type : 'group',
				icon: oa_cMenuImgArr['add'],
				items : [{
							text : "�½����",
							alias : "1-1-1",
							icon: oa_cMenuImgArr['add'],
							action : function(row) {
								showThickboxWin('?model=rdproject_group_rdgroup&action=toAdd&gpId='
									+ row.pid
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
							}
						},{
							text : "�½���Ŀ",
							alias : "1-1-2",
							icon: oa_cMenuImgArr['add'],
							action : function(row) {
								showThickboxWin('?model=rdproject_project_rdproject&action=rpToAdd&gpId='
									+ row.pid
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
							}
						},{
							text : "�½���Ŀ(��������)",
							alias : "1-1-3",
							icon: oa_cMenuImgArr['add'],
							action : function(row) {
								showThickboxWin('?model=rdproject_project_rdproject&action=toAddNoApproval&gpId='
									+ row.pid
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
							}
						}]
			},{
				text : "�鿴",
				// ����true��ʾ�˵�������flase����ʾ�˵�
				// showMenuFn : function(row) {
				// return true;
				// },
				alias : "1-2",
				icon: oa_cMenuImgArr['read'],
				action : function(row) {
					//�����Ƿ�����Ŀ�����ж�����Ŀ�������
					if ( showMenuFnG(row) ) {
						showOpenWin('?model=rdproject_group_rdgroup&action=rgRead&gpId='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					} else {
						showOpenWin('?model=rdproject_project_rdproject&action=rpRead&pjId='
								+ row.pid);
					}
					// alert(t.pid)
				}
			},{
				text : "����",
				alias : "1-3",
				showMenuFn : function(obj){
					if(showMenuFnP(obj)&&obj.status!=7&&obj.status!=8  ){
						return true;
					}else{
						return false;
					}
				},
				icon: oa_cMenuImgArr['open'],
				action : function(row) {
				showOpenWin('?model=rdproject_project_rdproject&action=rpOpenManage&proCenter=1&pjId='+ row.pid);
				}
			},{
				text : "�޸�",
				alias : "1-4",
				icon: oa_cMenuImgArr['edit'],
				showMenuFn : function(obj){
					if(obj.status!=1 && obj.status!=4 && showMenuFnP(obj) ){
						return false;
					}else{
						return true;
					}
				},
				action : function(row) {
					if ( showMenuFnG(row) ) {
						showThickboxWin('?model=rdproject_group_rdgroup&action=rgUpdateTo&gpId='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					} else {
						showOpenWin('?model=rdproject_project_rdproject&action=rpUpdateTo&pjId='
								+ row.pid);
					}
				}
			},{
				text : "���з������޸�",
				alias : "1-5",
				icon: oa_cMenuImgArr['edit'],
				showMenuFn : function(obj){
					if( !showMenuFnP(obj) ){
						return false;
					}else{
						return true;
					}
				},
				action : function(row) {
					showOpenWin('?model=rdproject_project_rdproject&action=rpUpdateToOld&pjId='
							+ row.pid);
				}
			},{
				text : "ɾ��",
				alias : "1-6",
				icon: oa_cMenuImgArr['del'],
				showMenuFn : function(obj){
					if(obj.status!=1 && obj.status!=4 && showMenuFnP(obj) ){
						return false;
					}else{
						return true;
					}
				},
				action : function(row) {
					if( showMenuFnG(row) ){
						if( confirm("ȷ��ɾ�������������´�����Ŀ��������򲻿�ɾ��") ){
							location = "?model=rdproject_group_rdgroup&action=rgDel&gpId="+row.pid;
						}
					}else{
						location = "?model=rdproject_project_rdproject&action=rpDel&pjId="+row.pid;
					}
				}
				//TODO:
//			},{
//				text : "�޸�",
//				alias : "1-4",
//				icon: oa_cMenuImgArr['edit'],
//				showMenuFn : function(obj){
//					if( obj.status!=6 && showMenuFnP(obj) ){
//						return false;
//					}else{
//						return true;
//					}
//				},
//				action : function(row) {
//					if ( row ) {
//						showThickboxWin('?model=rdproject_project_rdproject&action=toEditProject&pjId='
//								+ row.pid
//								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
//					}
//				}
			}
//			,{
//				text : "����Ϊģ��",
//				alias : "1-8",
//				icon: oa_cMenuImgArr['template']
//				//TODO:
//			}
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

$(function(){
	var importExcel = "?model=rdproject_task_rdtask&action=toImportExcel"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700";
	$("#importTask").attr("alt", importExcel);
});

