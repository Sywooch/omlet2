<?php


function dump() {

    //дабы не было кракозябр, если этот хедер еще не посылался
    header('Content-Type: text/html; charset=utf-8');

    $i=0;
    $args = func_get_args();

    foreach ($args as $arg) {

        echo '<h4>Var #'.$i.'</h4>';
        echo '<pre style="margin=20px 0; line-height: 1.5rem;">';
        var_dump($arg);
        echo '</pre>';

        $i++;
    }

    if(end($args) !== -1)
        exit();
}

function LogIt($data, $mode_append=false, $path=''){
    $path = (empty($path))?$_SERVER['DOCUMENT_ROOT'].'/test.txt':$_SERVER['DOCUMENT_ROOT'].'/'.$path;

    ob_start();
    var_dump($data);
    $resString = ob_get_contents();
    ob_end_clean();

    if($mode_append)
        file_put_contents($path, $resString, FILE_APPEND);
    else
        file_put_contents($path, $resString);
}