<?php
    function storeFile($file,$type,$dirver) : string
    {
        $fileName = rand(100000, 999999) . time() . $file->getClientOriginalName();
        $path = $file->storeAs($type, $fileName,$dirver);
        return $path;
    }

?>
