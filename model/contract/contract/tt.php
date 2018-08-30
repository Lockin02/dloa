
<?php
/**
 * �۲���ģʽ
 */


class Paper{ /* ����    */
    private $_observers = array();

    public function register($sub){ /*  ע��۲��� */
        $this->_observers[] = $sub;
    }


    public function trigger(){  /*  �ⲿͳһ����    */
        if(!empty($this->_observers)){
            foreach($this->_observers as $observer){
                $observer->update();
            }
        }
    }
}

/**
 * �۲���Ҫʵ�ֵĽӿ�
 */
interface Observerable{
    public function update();
}

class Subscriber implements Observerable{
    public function update(){
        echo "Callback\n";
    }
}




/*  ����    */
$paper = new Paper();
$paper->register(new Subscriber());
//$paper->register(new Subscriber1());
//$paper->register(new Subscriber2());
$paper->trigger();


?>