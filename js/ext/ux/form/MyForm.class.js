$import("Ext.FormPanel");
$import("Ext.form.Hidden");
$import("Ext.Msg");
$import("Ext.data.JsonReader");
$import("Ext.data.Store");
$import("Ext.data.MemoryProxy");
$import("Ext.data.HttpProxy");
$import("Ext.form.VTypes");
$import("Ext.DatePicker");
$import("Ext.form.DateField");

$package("Ext.ux.form");
$import("Ext.ux.form.FieldHelp");
$import("Ext.ux.form.FileUploadField");
$import("Ext.ux.form.MultiSelect");
$import("Ext.ux.form.StaticTextField"); // ��̬�ı��ؼ�
$import("Ext.ux.plugins.RemoteValidator"); // ajax���ؼ���������֤���

Ext.namespace("Ext.ux.form");
/**
 * ͨ�õı��ؼ� ����Ϊ�ؼ��Ĺ�������
 * 
 * 1.isInForm �Ƿ��ڱ��� true�� flase��
 * 
 * 2.isInAddForm �Ƿ������ӵı���
 * 
 * 3.isInEditForm �Ƿ��ڱ༭�ı���
 * 
 * 4.isLoad �Ƿ���ش˿ؼ���Ӧ���ֶ�
 * 
 * 5.valueId ҳ���ϵ�ֵId
 * 
 * 6.formType �ؼ����ͣ�Ĭ��textfield
 * 
 * 7.type string date datetime
 * 
 * 8.isInView �Ƿ�����ʾ����
 * 
 * 9.name �ؼ����ƣ��Ա���objName+'.'+name����
 * 
 * 10.hiddenName �ؼ�����������
 * ĳЩ�ؼ���Ҫ�õ��������洢�ؼ���ʵ��ֵ���磺radioTree������ѡ����checkboxtree��ѡ����combogrid��������
 * 
 * 11.mapping ǰ̨�ؼ�ƥ���̨json���ݵĸ�ʽ��һ�����name
 * 
 * 12.anchor �ڱ�����ռ�ٷֱȣ�Ĭ��95%��isOneRow==true��Ϊ97%
 * 
 * 13.isOneRow �Ƿ�ռ��һ��
 * 
 * 14.id �ؼ�id Ĭ��myform.id + "_" + c.name
 * 
 * 15.format type==dateĬ��ΪY-m-d type==datetimeĬ��ΪY-m-d H:i:s
 * 
 * 16.isAuditField �Ƿ�����ֶ� true�Ҳ�����˱�form��isAudit==false����ÿؼ�����
 * 
 * 17.viewFormType �鿴��ʱ�Ŀؼ����ͣ�Ĭ�ϲ鿴���Ŀؼ�������statictextfield
 * 
 * 18.isLoad ����ʼ����ʱ���Ƿ�Ӻ�̨���ش�����ֵ
 * 
 * @class Ext.ux.form.MyForm
 * @extends Ext.FormPanel
 */
Ext.ux.form.MyForm = Ext.extend(Ext.FormPanel, {
	/**
	 * ��label���
	 * 
	 * @type Number
	 */
	labelWidth : 80,
	/**
	 * ���ؼ�labelĬ�Ͼ���
	 * 
	 * @type String
	 */
	labelAlign : 'right',
	/**
	 * �Ƿ��Զ�����
	 * 
	 * @type Boolean
	 */
	autoScroll : true,
	/**
	 * ������͸��
	 * 
	 * @type Boolean
	 */
	frame : true,
	/**
	 * ���߿�
	 * 
	 * @type Boolean
	 */
	border : false,
	/**
	 * �Ƿ���ʾ����ť��false������а�ť����ʾ
	 * 
	 * @type Boolean
	 */
	isButton : true,
	/**
	 * �Ƿ���ʾ���水ť
	 * 
	 * @type Boolean
	 */
	isSave : true,
	/**
	 * �Ƿ���ʾ���ð�ť
	 * 
	 * @type Boolean
	 */
	isReset : true,
	/**
	 * �Ƿ���ʾ���ذ�ť
	 * 
	 * @type Boolean
	 */
	isFormReturn : false,
	/**
	 * �Ƿ���ʾ�رհ�ť
	 * 
	 * @type Boolean
	 */
	isClose : true,
	/**
	 * ����ʱ�Ƿ�ִ�йرմ����¼�
	 * 
	 * @type Boolean
	 */
	isSaveClose : true,
	/**
	 * ��ť�ڱ��ϵ�λ��,3.0 Ĭ����right 2.xĬ����center
	 * 
	 * @type String
	 */
	buttonAlign : 'center',
	/**
	 * ���Զ��尴ť ������buttonOrder˳��
	 * 
	 * @type
	 */
	formButtons : [],
	/**
	 * �����url�����Ϊ�գ������ʹ��Ĭ��url
	 * 
	 * @type String
	 */
	newUrl : '',
	/**
	 * ����url
	 * 
	 * @type String
	 */
	returnUrl : '',
	/**
	 * �Ƿ��ϴ���
	 * 
	 * @type Boolean
	 */
	fileUpload : false,
	/**
	 * ��Ĭ�ϸ߸���
	 * 
	 * @type Number
	 */
	// height : 400,
	/**
	 * ���÷�inpuԪ������Ч��
	 * 
	 * @type Array
	 */
	componentArray : [],
	/**
	 * �Ƿ�鿴��������������пؼ���Ϊ��̬�ı�
	 * 
	 * @type Boolean
	 */
	isViewForm : false,
	/**
	 * Ĭ�ϱ�����
	 * 
	 * @type Number
	 */
	formCol : 1,
	/**
	 * �����ݽṹ
	 * 
	 * @type Array
	 */
	structure : [],
	/**
	 * �Ƿ���˱��������������false���ʾΪisAuditField�Ŀؼ�����Ϊ����
	 * 
	 * @type Boolean
	 */
	isAudit : false,
	/**
	 * ��ʼ�����
	 */
	initComponent : function() {
		var mygrid = this.myGrid ? this.myGrid : this;
		mygrid.radioText = mygrid.radioText ? mygrid.radioText : this.radioText;
		mygrid.radioText2 = mygrid.radioText2
				? mygrid.radioText2
				: this.radioText2;
		this.initStructure();
		// ͨ��initialConfig����ʼ��BasicForm
		// this.initialConfig.fileUpload = true;
		this.initialConfig.fileUpload = mygrid.fileUpload; // �����ϴ�

		this.initialConfig.reader = this.reader;// �ǳ���Ҫ��һ�仰�������ܼ��ر�����
		Ext.ux.form.MyForm.superclass.initComponent.call(this);
		this.end();

	},
	/**
	 * ��ʼ���¼�
	 */
	initEvents : function() {
		Ext.ux.form.MyForm.superclass.initEvents.call(this);

	},
	/**
	 * ��ʼ�����ṹ
	 * 
	 * @param {}
	 *            id ��ʼ������id
	 */
	initStructure : function(id) {
		var passwordId;
		var repasswordId;
		this.componentArray = [];
		// �����myGrid��Ϊ�������룬��ʹ��myGrid�ϵĲ���
		if (this.myGrid) {
			this.myGrid.myForm = this;// ��myForm����myGrid
			var mygrid = this.myGrid;
			this.labelWidth = mygrid.labelWidth;// ����ǩ����
			this.labelAlign = mygrid.labelAlign;// ������
			this.formCol = mygrid.formCol;
			this.structure = mygrid.structure;
			this.isViewForm = mygrid.isViewForm;
			if (this.isViewForm) {
				mygrid.isSave = false;
				mygrid.isReset = false;
			}
			// Ext.copyTo(this,mygrid,"labelWidth,labelAlign,formCol,structure");
			this.width = '100%';
			this.height = mygrid.formHeight ? (mygrid.formHeight == '100%'
					? '100%'
					: mygrid.formHeight - 30) : '';
			// this.isAudit = mygrid.isAudit;
			this.autoHeight = mygrid.formAutoHeight;

		} else {
			var mygrid = this;
		}
		var myform = this;
		var readerArr = [];// �����ݽ���������
		var objName = mygrid.objName;
		function doFormItems(structure, formCol) {
			var oField = [];// ���ؼ�����
			// ========== ��ʼ���ֶ���Ϣ ��ʼ==============
			for (var i = 0, l = structure.length; i < l; i++) {
				var c = structure[i];
				var isInForm = c.isInForm == false ? false : true;
				if (Ext.isEmpty(mygrid.initId)) {
					if (c.isInAddForm == false)
						isInForm = false;
				} else {
					c.isLoad == c.isLoad == false ? false : true;
					if (c.isInEditForm == false)
						isInForm = false;
				}
				if (c.valueId && Ext.fly(c.valueId)) {
					c.value = Ext.fly(c.valueId).getValue();
				}
				c.formType = c.formType || 'textfield'; // Ĭ������Ϊtextfield
				if (c.formType == 'datefield' || c.type == 'datetime') {
					c.type = 'date';
				}
				// c.isInForm = c.isInForm == false ? c.isInForm : true;
				var isInView = c.isInView == true ? true : false;
				if (c.isParentCmp)// ���������Ľڵ��д����������Ƿ��Զ����ؽڵ���ĸ��ڵ�ؼ�
					mygrid.parentCmpId = c.name;
				if (isInForm || isInView) {
					if (c.hiddenName) {
						readerArr.push({
									name : objName + '.' + c.hiddenName,
									mapping : c.hiddenName
								});
					}
					if (c.name) {
						readerArr.push({
									name : objName + '.' + c.name,
									type : c.type,
									mapping : c.mapping ? c.mapping : c.name,
									dateFormat : c.type == 'date'
											|| c.type == 'datetime'
											? 'Y-m-d H:i:s'
											: ''
								});
					}

					c.anchor = c.anchor ? c.anchor : (c.isOneRow
							? '97%'
							: '95%');

					// if (!mygrid.isView) {
					var fieldId = myform.id + "_" + c.name;
					c.format = c.format ? c.format : (c.type == 'date'
							? 'Y-m-d'
							: (c.type == 'datetime' ? 'Y-m-d H i s' : ''));
					// �鿴���Ҳ�Ϊ��˿ؼ�
					if (mygrid.isViewForm == true && c.isAuditField != true
							&& c.formType != 'hidden'
							&& c.formType != 'editgrid'
							&& c.formType != 'fieldset'
							&& c.formType != 'upload'
							&& c.formType != 'panelForm') {
						c.formType = c.viewFormType
								? c.viewFormType
								: "statictextfield";
					} else if (c.isAuditField == true)// �������˿ؼ������Ҳ�����˱�������˿ؼ�����
					{
						if (!mygrid.isAudit) {
							c.formType = "statictextfield";
							c.isLoad = true;
						} else if (c.auditValue) {// ���ʱ���Ĭ��ֵ
							c.value = c.auditValue;
						}
					}
					/**
					 * ��ʼ���б��ؼ������ж�
					 */
					switch (c.formType) {
						/**
						 * �ı�����ؼ�
						 */
						case 'textfield' :
							oField.push({
								xtype : 'textfield',
								id : c.id ? c.id : fieldId,
								name : objName + '.' + c.name,
								fieldLabel : c.header,
								anchor : c.anchor,
								vtype : c.vtype ? c.vtype : '',// Ĭ����alpha��ĸ��alphanum��ĸ���֣�email,url
								listeners : c.listeners,
								allowBlank : c.required ? false : true,
								readOnly : c.readOnly,
								maxLength : c.maxLength ? c.maxLength : 50,
								isOneRow : c.isOneRow,
								value : c.value,
								hidden : c.formHidden,// ����formHidden�����ֶΣ�Ҳ�����ֶα�ǩ
								hideLabel : c.formHidden,
								disabled : c.disabled,
								isLoad : c.isLoad
									// �Ƿ���ش��ֶ����ݣ�flase�����load��ʱ�򲻼��ش��ֶ�
								});
							break;
						/**
						 * �����ı��ؼ�
						 */
						case 'numberfield' :
							oField.push({
										xtype : 'numberfield',
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										listeners : c.listeners,
										allowBlank : c.required ? false : true,
										maxLength : c.maxLength
												? c.maxLength
												: 12,
										isOneRow : c.isOneRow,
										value : c.value,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled
									});
							break;
						/**
						 * ���ı���ؼ�
						 */
						case 'textarea' :
							oField.push({
										xtype : 'textarea',
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										fieldLabel : c.header,
										height : c.height,
										anchor : c.anchor,
										allowBlank : c.required ? false : true,
										maxLength : c.maxLength
												? c.maxLength
												: 300,
										isOneRow : c.isOneRow,
										// grow : c.grow ? false :
										// true,//textarea�Ƿ��Զ��߶�
										value : c.value,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled
									});
							break;
						/**
						 * �����ı���ؼ�
						 */
						case 'password' :
							passwordId = c.id ? c.id : fieldId;
							oField.push({
										xtype : 'textfield',
										id : c.name,
										name : objName + '.' + c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										inputType : 'password',
										allowBlank : c.required ? false : true,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden
									});
							break;
						/**
						 * �ظ������ı���ؼ�
						 */
						case 'repassword' :
							repasswordId = c.id ? c.id : fieldId;
							oField.push({
										xtype : 'textfield',
										id : c.id ? c.id : fieldId,
										name : c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										inputType : 'password',
										vtype : 'password',// ��֤����
										initialPassField : passwordId,
										allowBlank : c.required ? false : true,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden
									});
							break;
						/**
						 * Ext�Դ��༭���ؼ�
						 */
						case 'htmleditor' :
							oField.push({
										xtype : 'htmleditor',
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										allowBlank : c.required ? false : true,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										disabled : c.disabled
									});
							break;
						/**
						 * fckeditor�༭���ؼ�
						 */
						case 'fckeditor' :
							mygrid.fckeditorId = c.id ? c.id : fieldId;
							oField.push({
								xtype : "textarea",
								name : objName + '.' + c.name,
								id : mygrid.fckeditorId,
								fieldLabel : c.header,
								listeners : {
									"render" : function(f) {
										mygrid.fckEditor = new FCKeditor(mygrid.fckeditorId);
										mygrid.fckEditor.Height = 400;
										mygrid.fckEditor.Width = 650;
										mygrid.fckEditor.ToolbarSet = "CRMToolbar";
										// fckEditor.BasePath =
										// "crm/common/js/myext/common/fckeditor/";
										// fckEditor.Config['CustomConfigurationsPath']
										// =
										// "/crm/crm/common/js/myext/common/fckeditor/fckconfig.js"
										mygrid.fckEditor.ReplaceTextarea();
									}
								},
								isOneRow : c.isOneRow
							});
							break;
						/**
						 * ���ڿؼ�
						 */
						case 'datefield' :
							oField.push({
								xtype : 'datefield',
								id : c.id ? c.id : fieldId,
								name : objName + '.' + c.name,
								fieldLabel : c.header,
								value : c.valueId ? new Date(c.value) : c.value,
								maxValue : c.maxValue,
								minValue : c.minValue,
								format : c.format,
								anchor : c.anchor,
								allowBlank : c.required ? false : true,
								isOneRow : c.isOneRow,
								disabledDays : c.disabledDays,
								vtype : c.vtype,
								startDateField : c.startDateField ? objName
										+ '.' + c.startDateField : '',
								endDateField : c.endDateField ? objName + '.'
										+ c.endDateField : '',
								listeners : c.listeners,
								hidden : c.formHidden,
								hideLabel : c.formHidden,
								readOnly : c.readOnly,
								disabled : c.disabled,
								plugins : [Class
										.forName("Ext.ux.form.DateFieldPlus")
										.newInstance('week')]
							});
							break;
						/**
						 * ����ʱ��ؼ�
						 */
						case 'datetimefield' :
							Class.forName("Ext.ux.form.DateTimeField");
							oField.push({
								xtype : 'datetimefield',
								id : c.id ? c.id : fieldId,
								name : objName + '.' + c.name,
								fieldLabel : c.header,
								value : c.valueId ? new Date(c.value) : c.value,
								anchor : c.anchor,
								allowBlank : c.required ? false : true,
								isOneRow : c.isOneRow,
								listeners : c.listeners,
								hidden : c.formHidden,
								hideLabel : c.formHidden,
								readOnly : c.readOnly,
								disabled : c.disabled
							});
							break;
						/**
						 * ʱ��ؼ�
						 */
						case 'timefield' :
							oField.push({
										xtype : 'timefield',
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										fieldLabel : c.header,
										value : c.value,
										format : c.format ? c.format : 'H:i',
										invalidText : "{0} ����һ����Ч��ʱ�䣡",
										minText : "ʱ������� {0}֮��",
										maxText : "ʱ�������{0}֮ǰ��",
										minValue : c.minValue
												? c.minValue
												: '6:00',
										maxValue : c.maxValue
												? c.maxValue
												: '24:00',
										anchor : c.anchor,
										allowBlank : c.required ? false : true,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										vtype : c.vtype,
										listeners : c.listeners,
										id : c.id,
										disabled : c.disabled
									});
							break;
						/**
						 * �ϴ��ؼ�
						 */
						case 'fileuploadfield' :
							Class.forName("Ext.ux.form.FileUploadField");
							oField.push({
										xtype : 'fileuploadfield',
										id : c.id ? c.id : fieldId,
										// name : objName + '.' + c.name,
										name : c.name,
										emptyText : '��ѡ��...',
										buttonText : c.buttonText,
										fieldLabel : c.header,
										anchor : c.anchor,
										fileType : c.fileType,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										disabled : c.disabled
									});
							break;
						/**
						 * ��ѡ�ؼ�
						 */
						case 'checkbox' :
							oField.push({
								xtype : 'checkbox',
								id : c.id ? c.id : fieldId,
								name : objName + '.' + c.name,
								fieldLabel : c.header,
								inputValue : c.inputValue ? c.inputValue : true,
								checked : c.checked == true ? true : false,
								isOneRow : c.isOneRow,
								hidden : c.formHidden,
								hideLabel : c.formHidden,
								readOnly : c.readOnly,
								disabled : c.disabled
							});
							break;
						/**
						 * ��ѡ�ؼ�1 ����ʹ��radioArr
						 */
						case 'radio' :
							var radioText = c.radioText ? c.radioText : ['��Ч',
									'��Ч'];
							oField.push({
										fieldLabel : c.header,
										xtype : 'radiogroup',
										columns : 2,
										isOneRow : c.isOneRow,
										listeners : c.listeners,
										items : [{
													boxLabel : radioText[1],
													name : objName + '.'
															+ c.name,
													inputValue : 1,
													checked : c.value == 1
												}, {
													boxLabel : radioText[0],
													name : objName + '.'
															+ c.name,
													inputValue : 0,
													checked : c.value == 0
												}],
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled

									});
							break;
						/**
						 * ��ѡ�ؼ�2 ����ʹ��radioArr
						 */
						case 'radio2' :
							var radioText = c.radioText ? c.radioText : ['��',
									'��'];
							oField.push({
										fieldLabel : c.header,
										xtype : 'radiogroup',
										columns : 2,
										isOneRow : c.isOneRow,
										items : [{
													boxLabel : radioText[1],
													name : objName + '.'
															+ c.name,
													inputValue : 1,
													checked : c.value == 1,
													listeners : c.listeners
												}, {
													boxLabel : radioText[0],
													name : objName + '.'
															+ c.name,
													inputValue : 0,
													checked : c.value == 0,
													listeners : c.listeners
												}],
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled

									});
							break;
						/**
						 * ��ѡ�ؼ�
						 */
						case 'radioArr' :
							var radioArr = [];
							for (var u = 0, t = c.fobj.length; u < t; u++) {
								radioArr.push({
											boxLabel : c.fobj[u].dataName,
											inputValue : c.fobj[u].dataCode,
											name : objName + '.' + c.name,
											listeners : c.fobj[u].listeners,
											checked : c.fobj[u].checked
													? c.fobj[u].checked
													: false
										});
							}
							oField.push({
										fieldLabel : c.header,
										xtype : 'radiogroup',
										columns : c.fobj.length,
										isOneRow : c.isOneRow,
										items : radioArr,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										// listeners : c.listeners,
										disabled : c.disabled
									});
							break;
						/**
						 * ������ؼ�
						 */
						case 'hidden' :
							var hiddenName = objName + '.' + c.name;
							oField.push({
										xtype : 'hidden',
										id : c.id ? c.id : fieldId,
										name : hiddenName,
										value : c.value ? c.value : null
									});
							break;
						/**
						 * ������ѡ�ؼ���ֻ�ύ��ʵֵ
						 */
						case 'combo' :
							// ��ʼ�������б�����
							c.valueField = c.valueField || 'dataCode';
							c.displayField = c.displayField || 'dataName';
							c.tips = c.tips || 'tips';
							var ds = myform.initCombo(c);
							oField.push({
										xtype : 'combo',
										id : c.id ? c.id : fieldId,
										// name : objName + '.' + c.name,
										hiddenName : objName + '.' + c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										// lazyInit : false, //3.0Ӱ��Ĭ�Ͽ��
										isOneRow : c.isOneRow,
										// valueNotFoundText : rootText,
										store : ds,
										value : c.value,
										tpl : '<tpl for="."><div  ext:qtip="{'
												+ c.tips
												+ '}" class="x-combo-list-item">{'
												+ c.displayField
												+ '}</div></tpl>',
										displayField : c.displayField,
										valueField : c.valueField,
										typeAhead : true,// ����������Ƿ��Զ�ƥ��ʣ�ಿ���ı�
										triggerAction : 'all',// ����������ťʱִ�е�Ĭ�ϲ���
										selectOnFocus : true,// ����ý���ʱ����ѡ��һ���Ѿ����ڵı���
										forceSelection : c.forceSelection
												? false
												: true,// ����ֵ�Ƿ�Ϊ��ѡ�б��д��ڵ�ֵ
										pageSize : c.pageSize ? c.pageSize : '',
										queryParam : 'searchValue',// �������û�Ѵ�combo�����ֵ��Ϊ��������action,Ĭ����query
										minChars : 1,// �Զ�ѡ��ǰ��������С�ַ�����
										resizable : true,
										allowBlank : c.required ? false : true,
										listeners : c.listeners,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled,
										isLoad : c.isLoad
									});
							break;
						/**
						 * ������ѡ�ؼ���ͬʱ�ύ��ʾֵ����ʵֵ
						 */
						case 'comboEx' :
							Class.forName("Ext.ux.combox.MyComboBox");
							// ��ʼ�������б����� ֧�������ֶ�
							c.valueField = c.valueField || 'dataCode';
							c.displayField = c.displayField || 'dataName';
							c.tips = c.tips || 'tips';
							var ds = myform.initCombo(c);
							oField.push({
										xtype : 'mycombo',
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										hiddenName : c.hiddenName ? objName
												+ '.' + c.hiddenName : null,
										fieldLabel : c.header,
										anchor : c.anchor,
										// lazyInit : false, //3.0Ӱ��Ĭ�Ͽ��
										isOneRow : c.isOneRow,
										store : ds,
										value : c.value,
										tpl : '<tpl for="."><div  ext:qtip="{'
												+ c.tips
												+ '}" class="x-combo-list-item">{'
												+ c.displayField
												+ '}</div></tpl>',
										displayField : c.displayField,
										valueField : c.valueField,
										typeAhead : true,// ����������Ƿ��Զ�ƥ��ʣ�ಿ���ı�
										triggerAction : 'all',// ����������ťʱִ�е�Ĭ�ϲ���
										selectOnFocus : true,// ����ý���ʱ����ѡ��һ���Ѿ����ڵı���
										forceSelection : c.forceSelection
												? false
												: true,// ����ֵ�Ƿ�Ϊ��ѡ�б��д��ڵ�ֵ
										pageSize : c.pageSize ? c.pageSize : '',
										queryParam : 'searchValue',// �������û�Ѵ�combo�����ֵ��Ϊ��������action,Ĭ����query
										minChars : 1,// �Զ�ѡ��ǰ��������С�ַ�����
										resizable : true,
										allowBlank : c.required ? false : true,
										listeners : c.listeners,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled,
										isLoad : c.isLoad
									});
							break;
						/**
						 * ������ѡ�ؼ�
						 */
						case 'multiSelect' :
							Class.forName("Ext.ux.form.MultiSelect")
							c.valueField = c.valueField || 'code';
							c.displayField = c.displayField || 'name';
							c.tips = c.tips || 'tips';
							var ds = myform.initCombo(c);
							oField.push({
										xtype : 'multiSelect',
										id : c.id ? c.id : fieldId,
										hiddenName : objName + '.' + c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										store : ds,
										value : c.value,
										displayField : c.displayField,
										valueField : c.valueField,
										tips : c.tips,
										triggerAction : 'all',
										resizable : true,
										// mode:'local',
										isOneRow : c.isOneRow,
										allowBlank : c.required ? false : true,
										listeners : c.listeners
									});
							break;
						/**
						 * ������ѡ���ؼ�
						 */
						case 'radioTree' :
							Class.forName("Ext.ux.combox.ComboBoxTree");
							var tree = Class.forName("Ext.ux.tree.MyTree")
									.newInstance({
										url : c.url,
										rootVisible : false,
										rootText : c.rootText
												? c.rootText
												: mygrid.boName + '��',
										parentFieldType : c.parentFieldType
												? c.parentFieldType
												: '',
										listeners : c.listeners
									});
							if (c.hiddenName)
								oField.push(new Ext.form.Hidden({
											name : objName + '.' + c.hiddenName,
											disabled : c.disabled,
											hideTag : true,
											value : c.hiddenValue
										}));
							oField.push({
										xtype : 'combotree',
										fieldLabel : c.header,
										anchor : c.anchor,
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										isOneRow : c.isOneRow,
										// hiddenName : objName + '.' +
										// c.hiddenName,
										hiddenField : c.hiddenName
												? oField[oField.length - 1].id
												: null,
										resizable : true,
										listWidth : c.listWidth,
										tree : tree,
										keyUrl : c.keyUrl,
										value : c.value,
										allowBlank : c.required ? false : true,
										listeners : c.listeners,
										hidden : c.formHidden,
										selectNodeModel : c.selModel,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled
									});
							break;
						/**
						 * ������ѡ���ؼ�
						 */
						case 'checkTree' :
							Class.forName("Ext.ux.combox.ComboBoxCheckTree");
							var tree = Class.forName("Ext.ux.tree.MyTree")
									.newInstance({
										url : c.url,
										checkModel : c.checkModel
												? c.checkModel
												: 'cascade',
										onlyLeafCheckable : false,
										rootVisible : false,
										rootText : c.rootText
												? c.rootText
												: mygrid.boName + '��',
										parentFieldType : c.parentFieldType
												? c.parentFieldType
												: '',
										listeners : c.listeners,
										keyUrl : c.keyUrl
									});
							if (c.hiddenName)
								oField.push(new Ext.form.Hidden({
											name : objName + '.' + c.hiddenName,
											disabled : c.disabled,
											hideTag : true,
											value : c.hiddenValue
										}));
							oField.push({
										xtype : 'combochecktree',
										fieldLabel : c.header,
										anchor : c.anchor,
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										isOneRow : c.isOneRow,
										hiddenField : c.hiddenName
												? oField[oField.length - 1].id
												: null,
										resizable : true,
										listWidth : c.listWidth,
										tree : tree,
										allowBlank : c.required ? false : true,
										listeners : c.listeners,
										selectValueModel : c.selectModel
												? c.selectModel
												: 'leaf',
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled,
										value : c.value
									});
							break;
						/**
						 * �������ؼ�
						 */
						case 'combogrid' :
							Class.forName("Ext.ux.combox.MyGridComboBox");
							c.myGrid.selectType = c.myGrid.selectType
									? c.myGrid.selectType
									: '';
							c.myGrid.isToExcel = false;
							c.myGrid.isToPDF = false;
							c.myGrid.isReturn = false;
							c.myGrid.viewConfig = {
								forceFit : true
							};
							c.myGrid.height = 200;
							if (c.lazyLoad == false) {
								c.myGrid.lazyLoad = false;
								c.myGrid = Class.forName("Ext.ComponentMgr")
										.create(c.myGrid, c.myGrid.xtype);
							} else
								c.myGrid.lazyLoad = true;
							if (!c.myGrid.objName) {// ���gridû��ʼ����ͨ���������name����ȡobjName�������ڼ��ر���ʱ������Ի�ȡ����ʾֵ
								c.myGrid.objName = c.name.substring(0, c.name
												.indexOf('.'));
							}
							// c.myGrid.removeListener('rowdblclick',
							// c.myGrid.editFunction);// ���α��˫���༭�¼�
							if (c.hiddenName) {
								oField.push(new Ext.form.Hidden({
											name : objName + '.' + c.hiddenName,
											disabled : c.disabled,
											hideTag : true
										}));
							}
							oField.push({
										xtype : 'combogrid',
										fieldLabel : c.header,
										anchor : c.anchor,
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										allowBlank : c.required ? false : true,
										myGrid : c.myGrid,
										myForm : myform,
										listeners : c.listeners,
										hiddenFieldId : c.hiddenName
												? oField[oField.length - 1].id
												: null,
										gridName : c.gridName,// ���������ʾ������
										gridValue : c.gridValue,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled,
										hideTrigger : c.hideTrigger,
										// plugins:[Class.forName("Ext.ux.form.FieldHelp")
										// .newInstance("aaaa")],
										listWidth : c.listWidth
												? c.listWidth
												: '500'
									});
							break;
						/**
						 * ��̬�༭���
						 */
						case 'editgrid' :
							if (c.xtype) {
								c.myForm = myform;
								c = Class.forName("Ext.ComponentMgr").create(c,
										c.xtype);
								myform.componentArray.push(c);
							}

							oField.push(c);
							break;
						case 'grid' :
							if (c.xtype) {
								c.myForm = myform;
								c = Class.forName("Ext.ComponentMgr").create(c,
										c.xtype);
								myform.componentArray.push(c);
							}

							oField.push(c);
							break;
						/**
						 * �������
						 */
						case 'panelForm' :
							if (c.xtype) {
								c = Class.forName("Ext.ComponentMgr").create(c,
										c.xtype);
							}
							oField.push(c);
							break;
						/**
						 * ��̬�ı���
						 */
						case 'statictextfield' :
							Class.forName("Ext.ux.form.StaticTextField");
							if (c.hiddenName)
								oField.push(new Ext.form.Hidden({
											name : objName + '.' + c.hiddenName,
											hideTag : true,
											value : c.hiddenValue
										}));
							oField.push({
								xtype : 'statictextfield',
								id : c.id ? c.id : fieldId,
								name : objName + '.' + c.name,
								fieldLabel : c.header,
								anchor : c.anchor,
								value : c.value,
								isOneRow : c.isOneRow,
								height : c.height,
								format : c.format ? c.format : c.renderer,
								submitValue : c.submitValue == false
										? false
										: true,
								hidden : c.formHidden,
								hideLabel : c.formHidden
									// �Ƿ��ύ��̬����ֵ
								});
							break;
						/**
						 * ��ť
						 */
						case 'button' :
							oField.push({
										xtype : 'button',
										id : c.id ? c.id : fieldId,
										text : c.text,
										handler : c.handler
									});
							break;
						/**
						 * �����ϴ����
						 */
						case 'upload' :
							oField.push(new Ext.form.Hidden({
										name : 'fileIds',
										hideTag : true
									}));
							var url = c.url;
							var fileId = oField[oField.length - 1].id;
							var dialog = null;
							var isViewed = c.isView;
							if (mygrid.isViewForm == true) {
								isViewed = mygrid.isViewForm;
							}
							oField.push({
								xtype : 'button',
								text : c.header ? c.header : '�ϴ�����',
								id : objName + '.' + c.name,
								listeners : {
									click : function() {
										if (dialog == null) {
											dialog = new Ext.ux.UploadDialog.Dialog(
													{
														url : url,
														isView : isViewed == true
																? true
																: false,
														// animateTarget :
														// 'south',
														width : 450,
														height : 300,
														minWidth : 450,
														minHeight : 300,
														draggable : true,
														resizable : true,
														reset_on_hide : true,
														allow_close_on_upload : false, // �ر��ϴ������Ƿ���Ȼ�ϴ��ļ�
														upload_autostart : false, // �Ƿ��Զ��ϴ��ļ�
														hiddenFieldId : fileId,
														base_params : {
															refId : myform.form
																	.findField(objName
																			+ '.id')
																	.getValue()
														}
													});
										}
										dialog.show();
										var refId = myform.form
												.findField(objName + '.id')
												.getValue();
										if (!Ext.isEmpty(refId)) {
											dialog.grid_panel.store.proxy = new Ext.data.HttpProxy(
													{
														url : 'fileupload!getFilesToJson.action?refId='
																+ refId
													});
											dialog.grid_panel.store.reload();
										}
									}
								}
							});
							break;
						/**
						 * �ؼ���
						 */
						case 'fieldset' :
							oField.push({
										xtype : 'fieldset',
										title : c.title,
										id : c.id,
										collapsible : true,
										autoHeight : true,
										anchor : c.anchor,
										hidden : c.hidden == true
												? true
												: false,
										collapsed : c.collapsed == true
												? true
												: false,
										isOneRow : c.isOneRow
												? c.isOneRow
												: true,
										items : doFormItems(c.items, 2),
										listeners : c.listeners
									});
							break;
					}// switch����
					var f = oField[oField.length - 1];
					// if (c.formType == 'textfield' || c.formType ==
					// 'combogrid') {
					if (!f.plugins) {
						f.plugins = [];
					}
					/**
					 * �ı���ߵ���ʾ��Ϣ
					 */
					if (!Ext.isEmpty(c.helpText)) {
						f.plugins.push(Class.forName("Ext.ux.form.FieldHelp")
								.newInstance(c.helpText));
					}
					/**
					 * ���isCheck==true,�����ajax��⣬������ں�̨�ظ���У��
					 */
					if (c.isCheck == true) {
						f.plugins.push(Ext.ux.plugins.RemoteValidator)
						f.rvOptions = {
							url : c.checkUrl ? c.checkUrl : mygrid.urlAction
									+ 'checkRepeat.action',// ����ظ���url·��
							objName : objName,
							fieldName : c.name,
							checkName : c.checkName ? c.checkName : [],
							checkValue : c.checkValue ? c.checkValue : [],
							fieldId : myform.id + "_id"
						}
					}
					// }
					// ���й��������Ը�ֵ��������
					f.isUnReset = c.isUnReset;
					f.emptyText = c.emptyText
							? c.emptyText
							: ((c.formType == 'textfield'
									|| c.formType == 'numberfield' || c.formType == 'textarea')
									? ''
									: '��ѡ��...');

					// if (f.name == 'projectInfo.projectName') {
					// alert(f.name + ":" + f.emptyText)
					// }
					// f.id = c.id ? c.id : fieldId;
					// f.name = objName + '.' + c.name;
					// f.fieldLabel = c.header;
					// f.anchor = c.anchor;
					// Ext.applyIf(oField[oField.length - 1], c);
				} else {
					// if (c.isInView != false)
					// oField[oField.length] = {
					// xtype : 'statictextfield',
					// id : c.id ? c.id : fieldId,
					// name : objName + '.' + c.name,
					// fieldLabel : c.header,
					// anchor : c.anchor,
					// value : c.value,
					// renderer : c.viewRenderer
					// ? c.viewRenderer
					// : c.renderer
					// };
				}

			}// for����

			var fieldArr = []; // �������
			var evenArr = []; // �����
			var oddArr = []; // �ұ���
			var j = 1;// j���ڹ�����ż�к���ʱ�������б������¸�ֵ
			for (var i = 0, l = oField.length; i < l; i++) {
				if (formCol == 1 || oField[i].hideTag == true
						|| oField[i].xtype == 'hidden') { // ���л��������� ֱ�Ӹ�ֵ
					fieldArr.push(oField[i]);
				} else {
					if (oField[i].isOneRow != true) {
						if (j++ % 2 == 0) {
							oddArr.push(oField[i]);
						} else {
							evenArr.push(oField[i]);
						}
					} else {
						fieldArr = pushColumn(evenArr, oddArr, fieldArr); // �����ж���
						fieldArr.push(oField[i]); // ����oneRow����
						// ��ձ������¸�ֵ
						evenArr = [];
						oddArr = [];
						j = 1;
					}
				}
			}
			// ���һ�������OneRow��������ѭ���Ѿ�������ϣ���������������ж�����
			if (!oField[oField.length - 1].isOneRow) {
				fieldArr = pushColumn(evenArr, oddArr, fieldArr); // �����ж���
			}

			return fieldArr;
		};
		// �����ж���
		function pushColumn(evenArr, oddArr, fieldArr) {
			var columnField = [];
			if (evenArr.length != 0)
				columnField.push({
							columnWidth : .5,
							layout : 'form',
							items : evenArr
						});
			if (oddArr.length != 0)
				columnField.push({
							columnWidth : .5,
							layout : 'form',
							items : oddArr
						});
			if (columnField.length != 0)
				fieldArr.push({
							layout : 'column',
							items : columnField
						});
			return fieldArr;
		}

		if (this.structure)
			this.items = doFormItems(this.structure, this.formCol);// �������ؼ�
		/**
		 * ������Ծ�����load��submit�ж����ݵĴ���collection������һ���������ͣ�json��ʽӦ����[]������һ������
		 */
		this.reader = new Ext.data.JsonReader({
					root : 'collection',
					successProperty : 'success'
				}, readerArr);
		// ------------- ��ʼ������Ϣ ����------------

		// this.items = fieldArr;// �������ؼ�
		if (this.isButton) {
			this.buttons = [];
			// ��������ť
			if (mygrid.isView)
				this.buttons = [{
							text : '�༭',
							name : 'edit',
							iconCls : 'save',
							handler : function() {
								// ����Ӧ�ø��ݸ��������ȥ�ж�ִ��ʲô�����������window����رգ������panel�����
								myform.closeWin();
								mygrid.doEdit(mygrid.initId);
								if (!myform.ownerCt.maximizable) {
									// ����myform����
									// myform.load();
								}
							}
						}];
			if (mygrid.isSave)
				this.buttons.push({
							text : '����',
							name : 'save',
							iconCls : 'save',
							buttonOrder : 1,// ��ť˳��
							handler : function() {
								myform.doSubmitForm()
							}
						});
			/**
			 * ������˰�ť
			 */
			if (mygrid.isAudit)
				this.buttons.push({
							text : 'ȷ�����',
							name : 'audit',
							iconCls : 'save',
							buttonOrder : 2,
							handler : function() {
								myform.doSubmitAuditForm()
							}
						});
			/**
			 * �༶��˹������ύ��ť
			 */
			if (mygrid.topicField || mygrid.topicFieldPlus)
				this.buttons.push({
							text : '�ύ����',
							name : 'submitAudit',
							iconCls : 'save',
							buttonOrder : 1,
							handler : function() {
								myform.doSubmitWorkFlowForm();
							}
						});
			if (mygrid.isReset)
				this.buttons.push({
							text : '����',
							name : 'reset',
							iconCls : 'clean',
							buttonOrder : 997,
							handler : function() {
								if (!Ext.isEmpty(myform.initId)) {
									myform.initForm();
								} else {
									myform.getForm().reset();
								}
								// mygrid.resetFn(myform);
							}
						});
			if (mygrid.isClose)
				this.buttons.push({
							text : '�ر�',
							name : 'close',
							iconCls : 'close',
							buttonOrder : 998,
							handler : function() {
								myform.closeWin();
							}
						});
			if (mygrid.isFormReturn) {
				this.buttons.push({
							// iconCls : 'icon-pdf',
							name : 'return',
							tooltip : "������һҳ",
							buttonOrder : 999,
							text : "����",
							handler : function() {
								if (!Ext.isEmpty(myform.returnUrl)) {
									document.location = myform.returnUrl;
								} else {
									history.go(-1);
								}
							}
						});
			}

			/**
			 * ����Զ��尴ť
			 */
			if (!Ext.isEmpty(mygrid.formButtons)) {
				this.buttons = this.buttons.concat(mygrid.formButtons);
			}
			if (!Ext.isEmpty(this.formButtons)) {
				this.buttons = this.buttons.concat(this.formButtons);
			}
			/**
			 * ��ť����
			 */
			this.buttons.sort(function(x, y) {
						return (x.buttonOrder ? x.buttonOrder : 0)
								- (y.buttonOrder ? y.buttonOrder : 0);
					});
		}
	},
	/**
	 * �ӷ�������ʼ��������
	 */
	initForm : function() {
		var mygrid = this.myGrid ? this.myGrid : this;
		var myform = this;
		var id = mygrid.initId;
		myform.initId = id;
		if (!Ext.isEmpty(id)) {
			/**
			 * ��ʼ����ǰִ���¼�
			 */
			if (mygrid.beforeInitForm)
				mygrid.beforeInitForm(myform);
			var initUrl = mygrid.initUrl ? mygrid.initUrl : mygrid.urlAction
					+ "init.action?" + mygrid.objName + ".id=" + id;
			this.form.load({
				url : initUrl,
				waitTitle : '���Ժ�',
				waitMsg : '���ڼ�������,���Ժ�......',
				success : function(form, action) {
					/**
					 * �ɹ���ʼ������ִ���¼�
					 */
					if (mygrid.afterInitForm)
						mygrid.afterInitForm(myform);
					/**
					 * �ж��Ƿ���fck�༭�����а�areatextֵ����fck�༭��
					 */
					if (mygrid.fckEditor && typeof(FCKeditorAPI) != 'undefined') {
						mygrid.fckEditor.Value = Ext.getCmp(mygrid.fckeditorId)
								.getValue();
						var editor = FCKeditorAPI
								.GetInstance(mygrid.fckeditorId);
						editor.SetHTML(Ext.getCmp(mygrid.fckeditorId)
								.getValue());

					}
				},
				failure : doFailure

			});
		} else {
			/**
			 * ��ӱ���ִ���¼�
			 */
			if (mygrid.afterAddForm)
				mygrid.afterAddForm(myform);
		}
		/**
		 * ���/�༭����ִ���¼�
		 */
		if (mygrid.afterForm)
			mygrid.afterForm(myform);

	},
	/**
	 * �رձ�����
	 */
	closeWin : function() {
		var mygrid = this.myGrid ? this.myGrid : this;
		// if (this.ownerCt.maximizable == true) {// �����������windows,��ر�
		if (mygrid.isClose) { // update time 09-09-15 swb
			// this.destroy();
			this.ownerCt.close();
			// this.ownerCt.destroy();
			this.destroy(); // 3.0
		}
	},

	/**
	 * �ύ������
	 * 
	 * @param {}
	 *            isShowMessage �Ƿ���ʾ��ʾ��Ϣ
	 * @return {Boolean}
	 */
	doSubmitForm : function(isShowMessage) {
		isShowMessage = (isShowMessage == false ? false : true);
		var mygrid = this.myGrid ? this.myGrid : this;
		var myform = this;
		var fn = true;
		if (myform.componentArray.length > 0) {// Ч���inputԪ��
			for (var i = 0, l = myform.componentArray.length; i < l; i++) {
				if (!myform.componentArray[i].isValid()) {
					fn = false;
					break;
				}
			}
		}
		if (myform.form.isValid() && fn) {
			if (mygrid.fckeditorId && mygrid.fckeditorId != '') {// �ж��Ƿ���fck�༭�����а�ֵ����areatext
				var editor = FCKeditorAPI.GetInstance(mygrid.fckeditorId);
				Ext.getCmp(mygrid.fckeditorId).setValue(editor.GetHTML(true));
			}

			if (mygrid.beforeSave) {
				var saveFn = mygrid.beforeSave(myform);
				if (saveFn == false) {
					return false;
				}
			}
			myform.form.submit({
						waitTitle : "���Ժ�",
						waitMsg : "�����ύ�����ݣ����Ժ�.......",
						url : mygrid.newUrl ? mygrid.newUrl : mygrid.urlAction
								+ "save.action",
						params : mygrid.formParams
								? mygrid.formParams
								: myform.formParams,
						success : function(form, action) {
							if (mygrid.store)
								mygrid.getStore().reload();
							if (isShowMessage) {
								Ext.Msg.info({
											message : action.result.message
													? action.result.message
													: '������ѡ' + mygrid.boName
															+ '�ɹ���'
										});
							}
							// ִ�б���ɹ����¼�
							if (mygrid.afterSave)
								mygrid.afterSave(myform, action.result);
							if (myform.isSaveClose)
								myform.closeWin();
							if (mygrid.selectedNode) {
								var parentNode = mygrid.selectedNode.parentNode;
								if (!mygrid.initId) {// �������ӽڵ�
									if (mygrid.selectedNode.attributes.leaf == true) {
										parentNode.reload();
										parentNode.expand(true);
									} else
										mygrid.selectedNode.reload();
								} else {// ������޸Ľڵ�
									parentNode.reload();
									parentNode.expand(true);
								}
							};

						},
						failure : function(form, action) {
							if (isShowMessage) {
								var message = null;
								if (action == null)
									return;
								if (action.message)
									message = action.message;
								else if (action.result.message)
									message = action.result.message;
								Ext.Msg.info({
											success : false,
											message : message
													? message
													: '������δ��Ӧ�����Ժ����ԣ�'
										});
							}
							if (mygrid.afterFailSave)
								mygrid.afterFailSave();
						}
					});
		} else {
			Ext.Msg.info({
						message : '����ʧ�ܣ�����Ч���ֶ�',
						success : false
					});
		}

	},

	/**
	 * �ύ����������Ϣ
	 * 
	 * @return {Boolean}
	 */
	doSubmitWorkFlowForm : function() {
		var mygrid = this.myGrid ? this.myGrid : this;
		var myform = this;
		var fn = true;
		if (myform.componentArray.length > 0) {// Ч���inputԪ��
			for (var i = 0, l = myform.componentArray.length; i < l; i++) {
				if (!myform.componentArray[i].isValid()) {
					fn = false;
					break;
				}
			}
		}
		if (myform.form.isValid() && fn) {
			if (mygrid.beforeSave) {
				var saveFn = mygrid.beforeSave(myform);
				if (saveFn == false) {
					return false;
				}
			}
			myform.form.submit({
				url : mygrid.newUrl ? mygrid.newUrl : mygrid.urlAction
						+ "save.action",
				waitTitle : "���Ժ�",
				waitMsg : "�����ύ�����Ϣ�����Ժ�......",
				params : mygrid.formParams
						? mygrid.formParams
						: myform.formParams,
				success : function(form, action) {
					var objId = mygrid.initId;
					var basicForm = myform.form;
					if (Ext.isEmpty(mygrid.initId)) {
						objId = action.result.collection[0].id
					}
					var url = mygrid.urlAction + 'toAudit.action?'
							+ mygrid.objName + '.id=' + objId;
					var topic = "";
					if (mygrid.topicField
							&& basicForm.findField(mygrid.objName + "."
									+ mygrid.topicField)) {
						topic = basicForm.findField(mygrid.objName + "."
								+ mygrid.topicField).getValue();
						if (Ext.isDate(topic)) {
							topic = topic.format('Y-m-d');
						}

					}
					if (mygrid.topicFieldPlus) {
						topic = mygrid.topicFieldPlus + topic;
					}
					// ֻ�����״̬�Ŷ���˿ؼ����ݽ����ύ
					if (mygrid.operType == "1") {
						var auditOption = ""
						if (basicForm
								.findField(mygrid.objName + '.auditOption')) {
							auditOption = basicForm.findField(mygrid.objName
									+ '.auditOption').getValue();
						}
						if (basicForm.findField(mygrid.objName + '.auditState')) {
							var state = basicForm.findField(mygrid.objName
									+ '.auditState').getValue();
							if (state == 0) {
								location.href = 'pendingtask!rollBackWorkitem.action?businessFlowParam.businessId='
										+ objId
										+ '&businessFlowParam.url='
										+ url
										+ '&businessFlowParam.topic='
										+ topic
										+ '&businessFlowParam.auditOption='
										+ auditOption;
								return;
							}
						};
					}
					// ���̽�����ִ�е�service��spring��beanid
					var executeClass = mygrid.objName + "Service";
					// ���̽�����ִ�еķ���:�������״̬Ϊ�����
					var executeMethod = "saveState";
					var url = 'pendingtask!submitWorkItem.action?businessFlowParam.businessId='
							+ objId
							+ '&businessFlowParam.url='
							+ url
							+ '&businessFlowParam.topic='
							+ topic
							+ '&businessFlowParam.executeClass='
							+ executeClass
							+ '&businessFlowParam.executeMethod='
							+ executeMethod;
					if (!Ext.isEmpty(auditOption)) {
						url += '&businessFlowParam.auditOption=' + auditOption;
					}
					if (mygrid.isToParentUrl == true) {
						parent.location.href = url;
					} else {
						location.href = url;
					}
				},
				failure : doFailure
			});
		} else {
			Ext.Msg.info({
						message : '����ʧ�ܣ�����Ч���ֶ�',
						success : false
					});
		}
	},
	/**
	 * �ύ�����Ϣ��������ˣ�
	 */
	doSubmitAuditForm : function() {
		var mygrid = this.myGrid ? this.myGrid : this;
		var myform = this;
		myform.form.submit({
					url : mygrid.auditUrl ? mygrid.auditUrl : mygrid.urlAction
							+ "audit.action",
					waitTitle : "���Ժ�",
					waitMsg : "�����ύ�����Ϣ�����Ժ򡣡���������",
					success : function(form, action) {
						Ext.Msg.info({
									message : '��˳ɹ���',
									success : true
								});
					}
				})
	},

	/**
	 * ���ܣ���ʼ����combo�ؼ�����
	 * 
	 * @param {}
	 *            c:combo�ؼ�
	 * @return {} Store
	 */
	initCombo : function(c) {
		var ds = null;
		if (typeof c.fobj != 'object') {// fobjΪ��̬json��������
			var reader = new Ext.data.JsonReader({
						totalProperty : 'totalSize',
						root : 'collection'
					}, [{
								name : c.valueField
							}, {
								name : c.displayField
							}, {
								name : c.tips
							}]);

			ds = new Ext.data.Store({
						proxy : new Ext.data.HttpProxy({
									url : c.url
								}),
						reader : reader
					});

			ds.on('beforeload', function() {
						var para = {
							pagesize : c.pageSize,
							name : c.fobj
						};
						Ext.apply(ds.baseParams, para);
					});

		} else {
			ds = new Ext.data.Store({
						proxy : new Ext.data.MemoryProxy(c.fobj),
						reader : new Ext.data.JsonReader({}, [{// new
									// Ext.data.ArrayReader
									name : c.valueField
								}, {
									name : c.displayField
								}, {
									name : c.tips
								}])
					});
			ds.reload();
		}
		return ds;
	},
	end : function() {
		var myform = this;
		/**
		 * ��ʼ���ڲ��ܴ��ڽ��������ж�
		 */
		Ext.apply(Ext.form.VTypes, {
			daterange : function(val, field) {
				var date = field.parseDate(val);
				if (!date) {
					return;
				}
				if (field.startDateField
						&& (!this.dateRangeMax || (date.getTime() != this.dateRangeMax
								.getTime()))) {
					var start = myform.form.findField(field.startDateField);
					start.setMaxValue(date);
					start.validate();
					this.dateRangeMax = date;
				} else if (field.endDateField
						&& (!this.dateRangeMin || (date.getTime() != this.dateRangeMin
								.getTime()))) {
					var end = myform.form.findField(field.endDateField);
					end.setMinValue(date);
					end.validate();
					this.dateRangeMin = date;
				}
				/*
				 * Always return true since we're only using this vtype to set
				 * the min/max allowed values (these are tested for after the
				 * vtype test)
				 */
				return true;
			}
		});
	}
})
Ext.reg('myform', Ext.ux.form.MyForm);