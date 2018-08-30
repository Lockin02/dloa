$(function() {
	var treeObj = $('#materialGrid');
	var thisHeight = document.documentElement.clientHeight - 30;

	var productCode=$("#productCode").val();
	var productName=$("#productName").val();

	treeObj.treegrid({
		title : '���ϡ�'+productCode+'-'+productName+'��BOM�嵥',
		width : '98%',
		height : thisHeight,
		nowrap : false,
		rownumbers : true,
		animate : true,
		collapsible : true,
		idField : 'id',
		treeField : 'productCode',//��������
		fitColumns : false,//�����Ӧ
		pagination : false,//��ҳ
		showFooter : true,//��ʾͳ��
		columns : [[
			{
				title : '���ϱ���',
				field : 'productCode',
				width : 210,
				formatter : function(v,row) {
					if(row.id == 'noId') return v;
					if((row.rgt - row.lft) == 1){
						return v ;
					}else{
						return v ;
					}
				}
			},{
				field : 'productName',
				title : '��������',
				width : 250
			},{
				field : 'pattern',
				title : '����ͺ�',
				width : 250
			},{
				field : 'unitName',
				title : '��λ',
				width : 60
			},{
				field : 'materialNum',
				title : '����',
				width : 80
			}
		]],
		onBeforeLoad : function(row,param) {//��̬��ֵȡֵ·��
			if(row){
				if(row.id * 1 == row.id){
					$(this).treegrid('options').url =  '?model=stock_material_material&action=treeJson&parentProductID=' + $("#parentProductID").val() + "&parentId=" + row.id;
				}else{
					$(this).treegrid('options').url =  '?model=stock_material_material&action=treeJson&parentProductID=' + $("#parentProductID").val() + "&parentId=" + $("#parentId").val();
				}
			}else{
				$(this).treegrid('options').url =  '?model=stock_material_material&action=treeJson&parentProductID=' + $("#parentProductID").val() + "&parentId=" + $("#parentId").val();
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
	$('#materialGrid').treegrid('reload');
}

//��������
function add(){
	var node = $('#materialGrid').treegrid('getSelected');
	if (node){
		//���ѡ�нڵ�û���ӽڵ㣬����ʾ
		if((node.rgt - node.lft) == 1){
				//��ʱȡ���������¼�����
				showThickboxWin("?model=stock_material_material&action=toAdd&parentId="
					+ node.id + "&parentProductID=" + $("#parentProductID").val()
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=620&width=1000");
		}else{
			//��ʱȡ���������¼�����
			showThickboxWin("?model=stock_material_material&action=toAdd&parentId="
				+ node.id + "&parentProductID=" + $("#parentProductID").val()
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=620&width=1000");
		}
	}else{
		showThickboxWin("?model=stock_material_material&action=toAdd&parentId=-1"
			+ "&parentProductID=" + $("#parentProductID").val()
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=620&width=1000");
	}
}

//�༭�ڵ�
function edit(){
	var node = $('#materialGrid').treegrid('getSelected');
	if (node){
		if((node.rgt - node.lft) == 1){
			showThickboxWin("?model=stock_material_material&action=toEdit&id="
				+ node.id  + "&skey=" + node.skey_
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=620&width=1000");
		}else{
			showThickboxWin("?model=stock_material_material&action=toEdit&id="
				+ node.id  + "&skey=" + node.skey_
				+ "&parentId=" + node._parentId
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
		}
	}else{
		alert('��ѡ��һ���ڵ�');
	}
}

//ɾ������
function removeActivity(){
	var node = $('#materialGrid').treegrid('getSelected');
	if (node){
		//�ж���ʾ��Ϣ
		if((node.rgt - node.lft) == 1){
			var alertText = 'ȷ��Ҫɾ����';
		}else{
			var alertText = 'ɾ�������ϣ��Ὣ�¼�����һ��ɾ����ȷ��Ҫִ�д˲�����';
		}
		if(confirm(alertText)){
			//�첽ɾ���ڵ�
			$.ajax({
			    type: "POST",
			    url: "?model=stock_material_material&action=ajaxdeletes",
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
	}else{
		alert('��ѡ��һ���ڵ�');
	}
}

//ȡ��ѡ��
function cancelSelect(){
	var node = $('#materialGrid').treegrid('unselectAll');
}