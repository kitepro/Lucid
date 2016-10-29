<?php
    function get_result_all($x){
        $x->store_result();
        $array = array();
        $variables = array();
        $data = array();
        $meta = $x->result_metadata();        
        while($field = $meta->fetch_field()){
            $variables[] = &$data[$field->name]; 
        }
        call_user_func_array(array($x, 'bind_result'), $variables);        
        $i=0;
        while($x->fetch()){
            $array[$i] = array();
            foreach($data as $k=>$v)
                $array[$i][$k] = $v;
            $i++;
        }
        $temparr = array();
        for($i=0;$i<count($array);$i++){
            $j=0;
            foreach($array[$i] as $k=>$v){
                $temparr[$i][$j] = $v;
                $j++;
            }
        }
        $array = $temparr;
        return $array;
    }    

    function get_result_assoc($x){
        $x->store_result();
        $array = array();
        $variables = array();
        $data = array();
        $meta = $x->result_metadata();        
        while($field = $meta->fetch_field()){
            $variables[] = &$data[$field->name]; 
        }
        call_user_func_array(array($x, 'bind_result'), $variables);        
        $i=0;
        while($x->fetch()){
            $array[$i] = array();
            foreach($data as $k=>$v)
                $array[$i][$k] = $v;
            $i++;
        }
        $temparr = array();
        foreach($array[0] as $k=>$v){
            $temparr[$k] = $v;
        }
        $array = $temparr;
        return $array;
    }
?>
