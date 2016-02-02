<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 16/2/2
 * Time: 下午8:50
 */

require_once "data.php";

$html = new html($data);

$html->run();


class html{
    public $data;

    function __construct($data){
        $this->data=$data;
    }

    public function run(){
        foreach($this->data['html'] as $h => $v){
            $html = new DOMDocument('1.0');
            $this->addSub($html,$v);
            $this->write($h,$html->saveHTML());
        }
    }

    public function addSub(&$html,$sub,$parent=false){

        foreach($sub as $s=>$v){


            if(is_int($s)){
                $attr_value=$v;
            }else{
                $attr_value=$s;
            }

            if(strpos($attr_value,':')>0){
               $values=explode(':',$attr_value);
                $attr_value=$values[0];
                $div= $html->createElement($values[1]);
            }else{
                $div= $html->createElement('div');
            }


            if(strpos($attr_value,'#')===0){
                $attr_value=str_replace('#','',$attr_value);
                $domAttribute = $html->createAttribute('id');
            }else{
                $domAttribute = $html->createAttribute('class');
            }

            $domAttribute->value = $attr_value;

            $div->appendChild($domAttribute);

            if(is_array($v)){
                $this->addSub($html,$v,$div);
            }

            if(!$parent){
               $html->appendChild($div);
            }else{
                $parent->appendChild($div);
            }
        }


    }
    public function write($htmlName,$str){
        $fp= fopen('html/'.$htmlName.'.html',w);
        fwrite($fp,$str);
        fclose($fp);
    }
}


