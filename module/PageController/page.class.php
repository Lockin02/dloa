<?php
/**
��ʾ 
require_once('../libs/classes/page.class.php');
$page=new pageClass(array('total'=>1000,'perpage'=>20));
echo 'mode:1<br>'.$page->show();
echo '<hr>mode:2<br>'.$page->show(2);
echo '<hr>mode:3<br>'.$page->show(3);
echo '<hr>mode:4<br>'.$page->show(4);
echo '<hr>��ʼAJAXģʽ:';
$ajaxpage=new page(array('total'=>1000,'perpage'=>20,'ajax'=>'ajax_page','page_name'=>'test'));
echo 'mode:1<br>'.$ajaxpage->show();

modify by xgq
 */
class pageClass
{
	/**
  * config ,public
  */
	var $page_name="page";//page��ǩ����������urlҳ������˵xxx.php?PB_page=2�е�PB_page
	var $next_page='>';//��һҳ
	var $pre_page='<';//��һҳ
	var $first_page='First';//��ҳ
	var $last_page='Last';//βҳ
	var $pre_bar='<<';//��һ��ҳ��
	var $next_bar='>>';//��һ��ҳ��
	var $format_left='[';
	var $format_right=']';
	var $is_ajax=false;//�Ƿ�֧��AJAX��ҳģʽ

	/**
  * private
  *
  */ 
	var $pagebarnum=10;//���Ƽ�¼���ĸ�����
	var $totalpage=0;//��ҳ��
	var $ajax_action_name='';//AJAX������
	var $nowindex=1;//��ǰҳ
	var $url="";//url��ַͷ
	var $offset=0;
	var $total=0;

	/**
  * constructor���캯��
  *
  * @param array $array['total'],$array['perpage'],$array['nowindex'],$array['url'],$array['ajax']...
  */
	function pageClass($array)
	{
		if(is_array($array)){
			if(!array_key_exists('total',$array))$this->error(__FUNCTION__,'need a param of total');
			$total=intval($array['total']);
			if($total==0) $total=1;
			$perpage=(array_key_exists('perpage',$array))?intval($array['perpage']):10;
			$nowindex=(array_key_exists('nowindex',$array))?intval($array['nowindex']):'';
			$url=(array_key_exists('url',$array))?$array['url']:'';
		}else{
			$total=$array;
			$perpage=10;
			$nowindex='';
			$url='';
		}
		if((!is_int($total))||($total<0))$this->error(__FUNCTION__,$total.' is not a positive integer!');
		if((!is_int($perpage))||($perpage<=0))$this->error(__FUNCTION__,$perpage.' is not a positive integer!');
		if(!empty($array['page_name']))$this->set('page_name',$array['page_name']);//����pagename
		$this->_set_nowindex($nowindex);//���õ�ǰҳ
		$this->_set_url($url);//�������ӵ�ַ
		$this->totalpage=ceil($total/$perpage);
		$this->offset=($this->nowindex-1)*$perpage;
		$this->total=$total;
		if(!empty($array['ajax']))$this->open_ajax($array['ajax']);//��AJAXģʽ
	}
	/**
  * �趨����ָ����������ֵ������ı�������������࣬��throwһ��exception
  *
  * @param string $var
  * @param string $value
  */
	function set($var,$value)
	{
		if(in_array($var,get_object_vars($this)))
		$this->$var=$value;
		else {
			$this->error(__FUNCTION__,$var." does not belong to PB_Page!");
		}

	}
	/**
  * �򿪵�AJAXģʽ
  *
  * @param string $action Ĭ��ajax�����Ķ�����
  */
	function open_ajax($action)
	{
		$this->is_ajax=true;
		$this->ajax_action_name=$action;
	}
	/**
  * ��ȡ��ʾ"��һҳ"�Ĵ���
  * 
  * @param string $style
  * @return string
  */
	function next_page($style='')
	{
		if($this->nowindex<$this->totalpage){
			return $this->_get_link($this->_get_url($this->nowindex+1),$this->next_page,$style);
		}
		return '<span class="'.$style.'">'.$this->next_page.'</span>';
	}

	/**
  * ��ȡ��ʾ����һҳ���Ĵ���
  *
  * @param string $style
  * @return string
  */
	function pre_page($style='')
	{
		if($this->nowindex>1){
			return $this->_get_link($this->_get_url($this->nowindex-1),$this->pre_page,$style);
		}
		return '<span class="'.$style.'">'.$this->pre_page.'</span>';
	}

	/**
  * ��ȡ��ʾ����ҳ���Ĵ���
  *
  * @return string
  */
	function first_page($style='')
	{
		if($this->nowindex==1){
			return '<span class="'.$style.'">'.$this->first_page.'</span>';
		}
		return $this->_get_link($this->_get_url(1),$this->first_page,$style);
	}

	/**
  * ��ȡ��ʾ��βҳ���Ĵ���
  *
  * @return string
  */
	function last_page($style='')
	{
		if($this->nowindex==$this->totalpage){
			return '<span class="'.$style.'">'.$this->last_page.'</span>';
		}
		return $this->_get_link($this->_get_url($this->totalpage),$this->last_page,$style);
	}

	function nowbar($style='',$nowindex_style='')
	{
		$plus=ceil($this->pagebarnum/2);
		if($this->pagebarnum-$plus+$this->nowindex>$this->totalpage)$plus=($this->pagebarnum-$this->totalpage+$this->nowindex);
		$begin=$this->nowindex-$plus+1;
		$begin=($begin>=1)?$begin:1;
		$return='';
		for($i=$begin;$i<$begin+$this->pagebarnum;$i++)
		{
			if($i<=$this->totalpage){
				if($i!=$this->nowindex)
				$return.=$this->_get_text($this->_get_link($this->_get_url($i),$i,$style));
				else
				$return.=$this->_get_text('<span class="'.$nowindex_style.'">'.$i.'</span>');
			}else{
				break;
			}
			$return.="\n";
		}
		unset($begin);
		return $return;
	}
	/**
  * ��ȡ��ʾ��ת��ť�Ĵ���
  *
  * @return string
  */
	function select($url="")
	{
		$return='<select name="PB_Page_Select"  onchange="window.location.href = this.value;">';
		for($i=1;$i<=$this->totalpage;$i++)
		{
			if($i==$this->nowindex){
				$return.='<option value='.$this->url.$i.' selected>'.$i.'</option>';
			}else{
				$return.='<option value='.$this->url.$i.'>'.$i.'</option>';
			}
		}
		unset($i);
		$return.='</select>';
		return $return;
	}

	/**
  * ��ȡmysql �����limit��Ҫ��ֵ
  *
  * @return string
  */
	function offset()
	{
		return $this->offset;
	}

	/**
  * ���Ʒ�ҳ��ʾ��������������Ӧ�ķ��
  *
  * @param int $mode
  * @return string
  */
	function show($mode=1,$url='')
	{
		switch ($mode)
		{
			case '1':
				$this->next_page='��һҳ';
				$this->pre_page='��һҳ';
				return $this->pre_page().$this->nowbar().$this->next_page().'��'.$this->select($url).'ҳ';
				break;
			case '2':
				$this->next_page='��һҳ';
				$this->pre_page='��һҳ';
				$this->first_page='��ҳ';
				$this->last_page='βҳ';
				return $this->first_page().$this->pre_page().'[��'.$this->nowindex.'ҳ]'.$this->next_page().$this->last_page().'��'.$this->select($url).'ҳ';
				break;
			case '3':
				$this->next_page='��һҳ';
				$this->pre_page='��һҳ';
				$this->first_page='��ҳ';
				$this->last_page='βҳ';
				return $this->first_page().$this->pre_page().$this->next_page().$this->last_page();
				break;
			case '4':
				$this->next_page='��һҳ';
				$this->pre_page='��һҳ';
				return $this->pre_page().$this->nowbar().$this->next_page();
				break;
			case '5':
				return $this->pre_bar().$this->pre_page().$this->nowbar().$this->next_page().$this->next_bar();
				break;
			case '6':
				$this->next_page='��һҳ';
				$this->pre_page='��һҳ';
				$this->first_page='��ҳ';
				$this->last_page='βҳ';
				return $this->first_page()."&nbsp;&nbsp;".$this->pre_page()."&nbsp;&nbsp;".$this->next_page()."&nbsp;&nbsp;".$this->last_page()."&nbsp;&nbsp;".'��&nbsp;<font color=ff0000>'.$this->nowindex.'</font>/'.$this->totalpage."&nbsp;&nbsp;".'ת����'.$this->select().'ҳ'."&nbsp;&nbsp;".'�ܹ�'.$this->total.'��';
				break;
		}

	}
	/*----------------private function (˽�з���)-----------------------------------------------------------*/
	/**
  * ����urlͷ��ַ
  * @param: String $url
  * @return boolean
  */
	function _set_url($url="")
	{
		if(!empty($url)){
			//�ֶ�����
			//$this->url=$url.((stristr($url,'?'))?'&':'?').$this->page_name."=";
			if (!$_SERVER['QUERY_STRING'])
			{
				$this->url=$_SERVER['REQUEST_URI']."?".$url.$this->page_name."=";
			}
		}else{
			//�Զ���ȡ
			if(empty($_SERVER['QUERY_STRING'])){
				//������QUERY_STRINGʱ
				$this->url=$_SERVER['REQUEST_URI']."?".$this->page_name."=";
			}else{
				$page_get=$_GET;
				$tmp="";
				foreach($page_get as $key=>$val){
					if($key!="PB_Page_Select"&&$key!=$this->page_name){
						$tmp.="&".$key."=".$val;
					}
				}
				$tmp=trim($tmp,"&");
				$tmpS=explode("?",$_SERVER['REQUEST_URI']);
				$this->url=$tmpS[0]."?".$tmp.'&'.$this->page_name.'=';
			}//end if
		}//end if
	}

	/**
  * ���õ�ǰҳ��
  *
  */
	function _set_nowindex($nowindex)
	{
		if(empty($nowindex)){
			//ϵͳ��ȡ

			if(isset($_GET[$this->page_name])){
				$this->nowindex=intval($_GET[$this->page_name]);
				if(trim($this->nowindex)==""||trim($this->nowindex)==0){
					$this->nowindex=1;
				}
			}
		}else{
			//�ֶ�����
			$this->nowindex=intval($nowindex);
		}
	}

	/**
  * Ϊָ����ҳ�淵�ص�ֵַ
  *
  * @param int $pageno
  * @return string $url
  */
	function _get_url($pageno=1)
	{
		return $this->url.$pageno;
	}

	/**
  * ��ȡ��ҳ��ʾ���֣�����˵Ĭ�������_get_text('<a href="">1</a>')������[<a href="">1</a>]
  *
  * @param String $str
  * @return string $url
  */ 
	function _get_text($str)
	{
		return $this->format_left.$str.$this->format_right;
	}

	/**
   * ��ȡ���ӵ�ַ
 */
	function _get_link($url,$text,$style=''){
		$style=(empty($style))?'':'class="'.$style.'"';
		if($this->is_ajax){
			//�����ʹ��AJAXģʽ
			return '<a '.$style.' href="javascript:'.$this->ajax_action_name.'(\''.$url.'\')">'.$text.'</a>';
		}else{
			return '<a '.$style.' href="'.$url.'">'.$text.'</a>';
		}
	}
	/**
   * ������ʽ
 */
	function error($function,$errormsg)
	{
		die('Error in file <b>'.__FILE__.'</b> ,Function <b>'.$function.'()</b> :'.$errormsg);
	}
}
?>
