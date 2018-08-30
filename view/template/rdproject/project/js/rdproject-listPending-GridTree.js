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
	var content = {
		iconShowIndex : 1,
		columnModel : GridColumnType,
//		dataUrl : "?model=rdproject_group_rdgroup&action=ajaxGroupByParentPending&parentId=-1",
//		lazyLoadUrl : "?model=rdproject_group_rdgroup&action=ajaxGroupAndProjectPending",
		dataUrl : "?model=rdproject_project_rdproject&action=projectAndGroupPending&parentId=-1",
		lazyLoadUrl : "?model=rdproject_project_rdproject&action=projectAndGroupPending",
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
		hidddenProperties : ['projectName','status','type'],// ����������һ���е�����,�ʺϴ���ֵ��һ�ַ�ʽ.
		contextMenu : {
			// width : 150,
			// alias:"1-1",
			items : [{
				text : "���",
				alias : "1-1",
				icon: oa_cMenuImgArr['add'],
				showMenuFn : showMenuFnG,
				type : 'group',
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
				type:"splitLine" //����
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
								+ row.pid+
								'&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					} else {
						showOpenWin('?model=rdproject_project_rdproject&action=rpRead&pjId='
								+ row.pid);
					}
					// alert(t.pid)
				}
			}, {
				text : "�޸�",
				alias : "1-3",
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
			}, {
				text : "ɾ��",
				alias : "1-5",
				icon: oa_cMenuImgArr['del'],
				showMenuFn : function(obj){
					if(obj.status!=1 && obj.status!=4 && showMenuFnP(obj) ){
						return false;
					}else{
						return true;
					}
				},
				action : function(row) {
					if(showMenuFnG(row)){
						if( confirm("ȷ��ɾ�������������´�����Ŀ��������򲻿�ɾ��") ){
							location = "?model=rdproject_group_rdgroup&action=rgDel&gpId="+row.pid;
						}
					}else{
						location = "?model=rdproject_project_rdproject&action=rpDel&pjId="+row.pid;
					}
				}
				//TODO:
			}, {
				type:"splitLine" //����
			},{
				text : "ִ����Ŀ",
				icon: oa_cMenuImgArr['published'],
				alias : "1-9",
				showMenuFn : function(obj){
					if(obj.status==5 && showMenuFnP(obj) ){
						return true;
					}else{
						return false;
					}
				},
				action : function(row) {
					if ( confirm("ȷ��ִ����Ŀ��") ) {

					}
				}
			},{
				text : "�ύ����",
				alias : "1-6",
				icon: oa_cMenuImgArr['readExa'],
				showMenuFn : function(obj){
					if(  (obj.status==1 ||obj.status==4  ) && showMenuFnP(obj) ){
						return true;
					}else{
						return false;
					}
				},
				action : function(row) {
					if ( confirm("ȷ���ύ������") ) {
						var url = "./controller/rdproject/project/ewf_index.php?actTo=ewfSelect&billId=" + row.pid +
							"&examCode=oa_rd_project&formName=��Ŀ����" +
							"&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750";
						showThickboxWin( url );
					}
				}
			},{
				text : "�鿴����",
				alias : "1-7",
				icon: oa_cMenuImgArr['readExa'],
				showMenuFn :  function(obj){
					if( obj.status!=1 && showMenuFnP(obj) ){
						return true;
					}else{
						return false;
					}
				},
				action : function(row) {
						var url = "controller/common/readview.php?itemtype=oa_rd_project&pid=" + row.pid +
								"&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750";
						showThickboxWin( url );
				}
			}
//			,{
//				text : "����Ϊģ��",
//				alias : "1-8",
//				icon: oa_cMenuImgArr['template'],
//				showMenuFn : function(row) {
//					if (row.projectName) {
//						return true;
//					}
//					return false;
//				}
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

	// չ��ȫ���ڵ�
	// _$('bt3').onclick=function(){myTree.expandAll();};
	// չ����һ��ڵ�
	// _$('bt4').onclick=function(){myTree.closeAll();};
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


/**
 * ˫���¼�,˫��һ�е��ø÷���.
 *
 * @param {�ж���}
 *            obj
 */
function doubleClickOnRow(obj) {
	debugObjectInfo(obj);
}

/**
 * �����鿴һ�����������
 */
function debugObjectInfo(obj) {
	traceObject(obj);

	function traceObject(obj) {
		var str = '';
		if (obj.tagName && obj.name && obj.id)
			str = "<table border='1' width='100%'><tr><td colspan='2' bgcolor='#ffff99'>traceObject ����tag: &lt;"
					+ obj.tagName
					+ "&gt;���� name = \""
					+ obj.name
					+ "\" ����id = \"" + obj.id + "\" </td></tr>";
		else {
			str = "<table border='1' width='100%'>";
		}
		var key = [];
		for (var i in obj) {
			key.push(i);
		}
		key.sort();
		for (var i = 0; i < key.length; i++) {
			var v = new String(obj[key[i]]).replace(/</g, "&lt;").replace(/>/g,
					"&gt;");
			if (typeof obj[key[i]] == 'string' && v != null && v != '')
				str += "<tr><td valign='top'>" + key[i] + "</td><td>" + v
						+ "</td></tr>";
		}
		str = str + "</table>";
		writeMsg(str);
	}
	function trace(v) {
		var str = "<table border='1' width='100%'><tr><td bgcolor='#ffff99'>";
		str += String(v).replace(/</g, "&lt;").replace(/>/g, "&gt;");
		str += "</td></tr></table>";
		writeMsg(str);
	}
	function writeMsg(s) {
		traceWin = window.open("", "traceWindow",
				"height=600, width=800,scrollbars=yes");
		traceWin.document.write(s);
	}
}

function showHtml() {
	jQuery('#ans').text(jQuery('#newtableTree').html());
}

function setGridTreeDisabled(v) {
	myTree.setDisabled(v);
}

//�鿴ѡ��Ľڵ�
function showChoosed() {
	var ans = getAllCheckValue();
	if (ans != '')
		alert(ans);
	else
		alert('û��ѡ��');
}

//�����нڵ�
function openAll() {
	myTree.expandAll();
}

//�ر����нڵ�
function closeAll() {
	myTree.closeAll();
}

function show_page(page) {
	myTree._reload();
}