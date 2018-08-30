Ext.namespace("Ext.ux.form");
/**
 * ��myFormԪ�ؿؼ��Ĺ�������
 * @type 
 */
Ext.ux.form.FormItem = {
	/**
	 * �Ƿ��ڱ��� true�� flase��
	 * 
	 * @type Boolean
	 */
	isInForm : true,
	/**
	 * �Ƿ������ӵı���
	 * 
	 * @type Boolean
	 */
	isInAddForm : true,
	/**
	 * �Ƿ��ڱ༭�ı���
	 * 
	 * @type Boolean
	 */
	isInEditForm : true,
	/**
	 * �Ƿ���ش˿ؼ���Ӧ���ֶ�
	 * 
	 * @type Boolean
	 */
	isLoad : true,
	/**
	 * ҳ���ϵ�ֵId
	 * 
	 * @type String
	 */
	valueId : '',
	/**
	 * �ؼ����ͣ�Ĭ��textfield
	 * 
	 * @type String
	 */
	formType : '',
	/**
	 * string date datetime
	 * 
	 * @type String
	 */
	type : '',
	/**
	 * �Ƿ�����ʾ����
	 * 
	 * @type Boolean
	 */
	isInView : true,
	/**
	 * �ؼ����ƣ��Ա���objName+'.'+name����
	 * 
	 * @type String
	 */
	name : '',
	/**
	 * �ؼ�����������,ĳЩ�ؼ���Ҫ�õ��������洢�ؼ���ʵ��ֵ���磺radioTree������ѡ����checkboxtree��ѡ����combogrid��������
	 * 
	 * @type String
	 */
	hiddenName : '',
	/**
	 * ǰ̨�ؼ�ƥ���̨json���ݵĸ�ʽ��һ�����name
	 * 
	 * @type String
	 */
	mapping : '',
	/**
	 * �ڱ�����ռ�ٷֱȣ�Ĭ��95%��isOneRow==true��Ϊ97%
	 * 
	 * @type String
	 */
	anchor : '95%',
	/**
	 * �Ƿ�ռ��һ��
	 * 
	 * @type Boolean
	 */
	isOneRow : false,
	/**
	 * �ؼ�id Ĭ��myform.id + "_" + c.name
	 * 
	 * @type String
	 */
	id : '',
	/**
	 * type==dateĬ��ΪY-m-d type==datetimeĬ��ΪY-m-d H:i:s
	 * 
	 * @type String
	 */
	format : '',
	/**
	 * �Ƿ�����ֶ� true�Ҳ�����˱�form��isAudit==false����ÿؼ�����
	 * 
	 * @type Boolean
	 */
	isAuditField : false,
	/**
	 * �鿴��ʱ�Ŀؼ����ͣ�Ĭ�ϲ鿴���Ŀؼ�������statictextfield
	 * 
	 * @type String
	 */
	viewFormType : 'statictextfield',
	/**
	 * ����ʼ����ʱ���Ƿ�Ӻ�̨���ش�����ֵ
	 * 
	 * @type Boolean
	 */
	isLoad : true

}