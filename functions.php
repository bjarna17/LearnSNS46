<?php
    function h($val){
      return htmlspecialchars($val);
    }

    function v($val,$var_name){
      if (true) {
        echo '<pre>';
        echo $var_name . '=';

        
        var_dump($val);
        echo '</pre>';
        }
    }

    function e($val){
      if(true){
      echo $val;
      }
    }


?>