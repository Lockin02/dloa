Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var tree = new Ext.ux.tree.MyTree({
		url : 'index1.php?model=rdproject_task_tknode&action=getTkNodeTreeByParentId&planId='
				+ $("#planId").val() + '&parentId=',
		rootId : -1,
		rootText : '�ڵ�',
		rootVisible : false,
		listeners : {
			click : function(node) {
				Ext.Ajax.request({
					url : 'index1.php?model=rdproject_task_tknode&action=getChargeInfo&id='
							+ node.id,
					success : function(result, request) {
						var json = result.responseText;
						var o = eval("(" + json + ")");
						if (o['charegeName'] != "" || o['charegeName'] != null) {
							$("#chargeName").val(o['charegeName']);
							$("#chargeId").val(o['charegeId']);
							mailTo();
						}

					}
				})

			}

		}
	});
	new Ext.ux.combox.ComboBoxTree({
		applyTo : 'belongNode',
		hiddenField : 'belongNodeId',
		tree : tree
	});

	var projectId = $("#projectId").val();
	var filetree = new Ext.ux.tree.MyTree({
		url : '?model=rdproject_uploadfile_uploadfiletype&action=tree&projectId='
				+ projectId + "&parentId=",
		rootId : -1,
		rootVisible : false,
		listeners : {
			click : function(node) {
				uploadfile.addPostParam("typeId", node.id);
				uploadfile.addPostParam("typeName", node.text);
			}

		}
	});
	new Ext.ux.combox.ComboBoxTree({
		emptyText : '��ѡ�񸽼�����...',
		applyTo : 'uploadfiletype',
		hiddenField : 'uploadfiletypeId',
		tree : filetree
	});

	// ǰ������
	var beforeTaskGrid = {
		xtype : 'taskcombogrid',
		selectType : 'check',
		searchFields : ['projectId'],
		searchValues : [$("#projectId").val()],
		listeners : {
			'dblclick' : function(e) {

			}
		}
	};
	new Ext.ux.combox.MyGridComboBox({
		applyTo : 'frontTaskName',
		// renderTo : 'contractName',
		gridName : 'name',// ���������ʾ������
		gridValue : 'id',
		hiddenFieldId : 'frontTaskId',
		myGrid : beforeTaskGrid
	});
	//�����ˣ���Ŀ��ѡ��
	var teamGrid = {
		id : 'teamGrid',
		xtype : 'teamComboGrid',
		urlAction : 'index1.php?model=rdproject_team_rdmember&action=',
		listUrl : 'pageJson&projectId=' + $('#projectId').val(),
		width : 200,
		listeners : {
			'dblclick' : function(e) { // mydelAll();
				var record = this.getSelectionModel().getSelected();
				$("#chargeId").val(record.get('chargeId'));// �ƻ�����
				$("#chargeName").val(record.get('chargeName'));// �ƻ�����
				mailTo();
				$('#chargeName').focus();
			}
		}
	};
	var tg = new Ext.ux.combox.MyGridComboBox({
		listWidth : 200,
		applyTo : 'chargeName',
		gridName : 'memberName',// ���������ʾ������
		gridValue : 'memberId',
		hiddenFieldId : 'chargeId',
		myGrid : teamGrid
	});
	//�����ˣ���Ŀ��ѡ��
	var teamsGrid = {
		id : 'teamGrid',
		xtype : 'teamComboGrid',
		selectType : 'check',
		urlAction : 'index1.php?model=rdproject_team_rdmember&action=',
		listUrl : 'pageJson&projectId=' + $('#projectId').val(),
		width : 500,
		listeners : {
			'dblclick' : function(e) { // mydelAll();
			},
			'click' : function(e){
				$('#withinActName').focus();
			}
		}
	};
	var tgs = new Ext.ux.combox.MyGridComboBox({
		listWidth : 500,
		applyTo : 'withinActName',
		gridName : 'memberName',// ���������ʾ������
		gridValue : 'memberId',
		hiddenFieldId : 'withinActId',
		myGrid : teamsGrid
	});

	//����ˣ���Ŀ��ѡ��
	var auditGrid = {
		id : 'teamGrid',
		xtype : 'teamComboGrid',
		urlAction : 'index1.php?model=rdproject_team_rdmember&action=',
		listUrl : 'pageJson&projectId=' + $('#projectId').val(),
		width : 500,
		listeners : {
			'dblclick' : function(e) { // mydelAll();
			},
			'click' : function(e){
				$('#withinActName').focus();
			}
		}
	};
	var atgs = new Ext.ux.combox.MyGridComboBox({
		listWidth : 500,
		applyTo : 'auditName',
		gridName : 'memberName',// ���������ʾ������
		gridValue : 'memberId',
		hiddenFieldId : 'auditId',
		myGrid : auditGrid
	});
});

function arrayCon(arr1, arr2) {
	for (var i = 0; i < arr1.length; i++) {
		for (var j = 0; j < arr2.length; j++) {
			if (arr1[i] == arr2[j] || arr1[i] == '') {
				arr1.splice(i, 1); // ����splice����ɾ��Ԫ�أ��ӵ�i��λ�ã���ȡ����Ϊ1��Ԫ��
				i--;
				break;
			}
		}
	}
	// alert(arr1.length)
	for (var i = 0; i < arr2.length; i++) {
		arr1.push(arr2[i]);
	}
	return arr1;
}
function actUserFun() {
	//������id�ַ���
	var withinId = $('#withinActId').val();
	var withoutId = $('#withoutActId').val();
	withinIdArr = withinId.split(',');
	withoutIdArr = withoutId.split(',');
	var actUserIdArr = new Array();
	actUserIdArr = arrayCon(withinIdArr,withoutIdArr);
	var actUserIdStr = actUserIdArr.join(',');

	//������name�ַ���
	var withinName = $('#withinActName').val();
	var withoutName = $('#withoutActName').val();
	withinNameArr = withinName.split(',');
	withoutNameArr = withoutName.split(',');
	var userNameArr = new Array();
	userNameArr = arrayCon(withinNameArr,withoutNameArr);
	var userNameStr = userNameArr.join(',');

	$('#actUserId').val(actUserIdStr);
	$('#userName').val(userNameStr);
	mailTo();
}