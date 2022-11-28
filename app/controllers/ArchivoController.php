<?php

class ArchivoController 
{
    public static function UploadFile($filePath, $path ="./Encuestas/")
    {
        if(!file_exists($path))
        {
            mkdir($path, 0777);
        }
  
        $destino = "{$path}".$_FILES["file"]['name'];   
        rename($destino, "{$path}{$filePath}");

        $tipoArchivo = pathinfo($destino, PATHINFO_EXTENSION); 
        $exito = false;
  
        if($tipoArchivo == "csv" || $tipoArchivo == "xlsx")
        {
            move_uploaded_file($_FILES["file"]["tmp_name"], $destino);
            $exito = true;
        }else{
            echo "No es csv ni excel, el formato es incorrecto";
        }
  
        return $exito;
    }

    public static function UploadPhoto($imgPath, $path ="./FotosPedidos/")
    {
        if(!file_exists($path))
        {
            mkdir($path, 0777);
        }
  
        $destino = "{$path}".$_FILES["foto"]['name'];   
        rename($destino, "{$path}{$imgPath}");
  
        $esImagen = getimagesize($_FILES["foto"]["tmp_name"]); 
        $tipoArchivo = pathinfo($destino, PATHINFO_EXTENSION); 
        $exito = false;
  
        if($esImagen != false && ($tipoArchivo == "jpg" || $tipoArchivo == "png" || $tipoArchivo == "jpeg" ))
        {
            move_uploaded_file($_FILES["foto"]["tmp_name"], $destino);
            $exito = true;
        }else{
            echo "No es imagen o el formato es incorrecto";
        }
  
        return $exito;
    }

    public static function MoverPhoto($imgPath, $path ="./Backup_FotosPedidos/")
    {
        try
        {
            if(!file_exists("./Backup_FotosPedidos/"))
            {
                mkdir($path, 0777);
            }
                    
            $origen="./FotosPedidos/{$imgPath}";
            $destino="{$path}{$imgPath}";
                    
            copy($origen, $destino);
            unlink($origen);
        }
        catch (Exception $e) {
            $e->getMessage();
        }
    }
}

?>