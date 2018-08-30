$(function() {
	var thisHeight = document.documentElement.clientHeight - 40;
    $('#esmactivityGrid').treegrid({
		title : '��Ŀ����',
		width : '98%',
		height : thisHeight,
		nowrap : false,
		rownumbers : true,
		idField : 'id',
		treeField : 'activityName',//��������
		fitColumns : false,//�����Ӧ
		pagination : false,//��ҳ
		showFooter : true,//��ʾͳ��
		columns : [[
			{
				title : '��������',
				field : 'activityName',
				width : 210,
				formatter : function(v,row) {
					if(row.id == 'noId') return v;
					if((row.rgt - row.lft) == 1){
						return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\",1,668,1000,"+row.id+")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toViewNode&id=" + row.id + '&skey=' + row.skey_ + "\",1,668,1000,"+row.id+")'>" + v + "</a>";
					}
				}
			},{
				field : 'workRate',
				title : '����ռ��',
				width : 70,
				formatter : function(v,row) {
					if(row.parentId == "-1"){
						return "<font style='font-weight:bold;'>" + v + " %</font>";
					}else{
						return v + " %";
					}
				}
			},{
				field : 'planBeginDate',
				title : 'Ԥ�ƿ�ʼ',
				width : 80
			},{
				field : 'planEndDate',
				title : 'Ԥ�ƽ���',
				width : 80
			},{
				field : 'days',
				title : 'Ԥ�ƹ���',
				width : 60
			},{
				field : 'workload',
				title : '������',
				width : 60,
                formatter : function(v,row) {
                    return row.isTrial == '1' ? '--' : v;
                }
			},{
				field : 'workloadUnitName',
				title : '��λ',
				width : 60
			},{
				field : 'workContent',
				title : '��������',
				width : 300
			}
		]],
		onBeforeLoad : function(row,param) {//��̬��ֵȡֵ·��
			if(row){
				if(row.id * 1 == row.id){
					$(this).treegrid('options').url =  '?model=engineering_activity_esmactivity&action=treeJson&projectId=' + $("#projectId").val() + "&parentId=" + row.id;
				}else{
					$(this).treegrid('options').url =  '?model=engineering_activity_esmactivity&action=treeJson&projectId=' + $("#projectId").val() + "&parentId=" + $("#parentId").val();
				}
			}else{
				$(this).treegrid('options').url =  '?model=engineering_activity_esmactivity&action=treeJson&projectId=' + $("#projectId").val() + "&parentId=" + $("#parentId").val();
			}
		},
		onContextMenu : function(e, row) {
			e.preventDefault();
			$(this).treegrid('unselectAll');
			$(this).treegrid('select', row.id);
			$('#menuDiv').menu('show', {
				left : e.pageX,
				top : e.pageY
			});
		}
	});
});

//ԭҳ��ˢ�·���
function show_page(){
	reload();
}

//ˢ��
function reload(){
	$('#esmactivityGrid').treegrid('reload');
}

//�༭����
function editActivity(){
	var node = $('#esmactivityGrid').treegrid('getSelected');
	if (node){
		if(node.isTrial == 1){
			showOpenWin("?model=engineering_activity_esmactivity&action=toEditTrial&id="
					+ node.id  + "&skey=" + node.skey_ , 1, 400 ,800 ,node.id);
		}else{
			if((node.rgt - node.lft) == 1){
				showOpenWin("?model=engineering_activity_esmactivity&action=toEdit&id="
					+ node.id  + "&skey=" + node.skey_ , 1, 668 ,1000 ,node.id);
			}else{
				showOpenWin("?model=engineering_activity_esmactivity&action=toEditNode&id="
					+ node.id  + "&skey=" + node.skey_
					+ "&parentId=" + node._parentId , 1, 668 ,1000 ,node.id);
			}
		}
	}else{
		alert('��ѡ��һ������');
	}
}

//��������
function addActivity(){
	var node = $('#esmactivityGrid').treegrid('getSelected');
	if (node){
		if(node.isTrial == 1){
			alert('�����ڴ���������������');
		}else{
			//���ѡ������û������������ʾ
			if((node.rgt - node.lft) == 1){
				if(confirm("������һ���¼�����Ὣ��" + node.activityName + "�����������ת���������У�ȷ�Ͻ�����")){
					showOpenWin("?model=engineering_activity_esmactivity&action=toMove&parentId="
						+ node.id + "&projectId=" + $("#projectId").val() , 1, 668 ,1000 ,node.id );
				}
			}else{
				showOpenWin("?model=engineering_activity_esmactivity&action=toAdd&parentId="
					+ node.id + "&projectId=" + $("#projectId").val() , 1, 668 ,1000 ,node.id );
			}
		}
	}else{
		showOpenWin("?model=engineering_activity_esmactivity&action=toAdd&parentId=-1"
			+ "&projectId=" + $("#projectId").val() , 1, 668 ,1000 );
	}
}

//ɾ������
function removeActivity(){
	var node = $('#esmactivityGrid').treegrid('getSelected');
	if (node){
		if(node.isTrial == 1){
			alert("��������ɾ����");
		}else{
			//�ж���ʾ��Ϣ
			if((node.rgt - node.lft) == 1){
				var alertText = 'ȷ��Ҫɾ����';
			}else{
				var alertText = 'ɾ�������񣬻Ὣ�¼�����һ��ɾ����ȷ��Ҫִ�д˲�����';
			}
			if(confirm(alertText)){
				//�첽ɾ������
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_activity_esmactivity&action=ajaxdeletes",
				    data: {
				    	id : node.id
				    },
				    async: false,
				    success: function(data){
				   		if(data == "1"){
							alert('ɾ���ɹ�');
							reload();
				   	    }else{
							alert('ɾ��ʧ��');
							return false;
				   	    }
					}
				});
			}
		}
	}else{
		alert('��ѡ��һ������');
	}
}

//ȡ��ѡ��
function cancelSelect(){
	$('#esmactivityGrid').treegrid('unselectAll');
}