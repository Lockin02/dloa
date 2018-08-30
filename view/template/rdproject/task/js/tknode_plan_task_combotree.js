Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var tree = new Ext.ux.tree.MyTree({
		url : 'index1.php?model=rdproject_task_tknode&action=getTkNodeTreeByParentId&planId='
				+ $("#planId").val() + '&parentId=',
		rootId : -1,
		rootText : '节点',
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
		emptyText : '请选择附件类型...',
		applyTo : 'uploadfiletype',
		hiddenField : 'uploadfiletypeId',
		tree : filetree
	});

	// 前置任务
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
		gridName : 'name',// 下拉表格显示的属性
		gridValue : 'id',
		hiddenFieldId : 'frontTaskId',
		myGrid : beforeTaskGrid
	});
	//责任人：项目内选择
	var teamGrid = {
		id : 'teamGrid',
		xtype : 'teamComboGrid',
		urlAction : 'index1.php?model=rdproject_team_rdmember&action=',
		listUrl : 'pageJson&projectId=' + $('#projectId').val(),
		width : 200,
		listeners : {
			'dblclick' : function(e) { // mydelAll();
				var record = this.getSelectionModel().getSelected();
				$("#chargeId").val(record.get('chargeId'));// 计划名称
				$("#chargeName").val(record.get('chargeName'));// 计划名称
				mailTo();
				$('#chargeName').focus();
			}
		}
	};
	var tg = new Ext.ux.combox.MyGridComboBox({
		listWidth : 200,
		applyTo : 'chargeName',
		gridName : 'memberName',// 下拉表格显示的属性
		gridValue : 'memberId',
		hiddenFieldId : 'chargeId',
		myGrid : teamGrid
	});
	//参与人：项目内选择
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
		gridName : 'memberName',// 下拉表格显示的属性
		gridValue : 'memberId',
		hiddenFieldId : 'withinActId',
		myGrid : teamsGrid
	});

	//审核人：项目内选择
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
		gridName : 'memberName',// 下拉表格显示的属性
		gridValue : 'memberId',
		hiddenFieldId : 'auditId',
		myGrid : auditGrid
	});
});

function arrayCon(arr1, arr2) {
	for (var i = 0; i < arr1.length; i++) {
		for (var j = 0; j < arr2.length; j++) {
			if (arr1[i] == arr2[j] || arr1[i] == '') {
				arr1.splice(i, 1); // 利用splice函数删除元素，从第i个位置，截取长度为1的元素
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
	//参与人id字符串
	var withinId = $('#withinActId').val();
	var withoutId = $('#withoutActId').val();
	withinIdArr = withinId.split(',');
	withoutIdArr = withoutId.split(',');
	var actUserIdArr = new Array();
	actUserIdArr = arrayCon(withinIdArr,withoutIdArr);
	var actUserIdStr = actUserIdArr.join(',');

	//参与人name字符串
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