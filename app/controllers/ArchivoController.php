<?php

require_once "./models/Trabajador.php";

class ArchivoController 
{
    public static function AltaCSV($trabajadores)
    {
        if(file_exists('trabajadores.csv'))
        {
            if(filesize('trabajadores.csv') > 0 )
            {
                $archivo = fopen('trabajadores.csv', 'w');
            }
        }
        else
        {
            $archivo = fopen('trabajadores.csv', 'w');
        }
        
        if(is_array($trabajadores) && $archivo != false)
        {
            foreach($trabajadores as $t)
            { 
                fputcsv($archivo, get_object_vars($t));
            }

            $seGuardo = ArchivoController::UploadFile("trabajadores.csv", "./CSV/");
            fclose($archivo);
        }
        else
        {
            die("Error al abrir archivo"); //exit 
        }
        if($seGuardo){
            return true;
        }else{
            return false;
        }
    }

    public static function CargarArrayDeDatos($rutaArchivo)
    {
        $arrayRetorno = array();
        $archivo = fopen($rutaArchivo, "r");
        
        while(!feof($archivo))
        {
            $str = fgets($archivo); 
            if(!empty($str)) 
            {
                $dataPorLinea = explode(",", $str); 
                array_push($arrayRetorno, new Trabajador($dataPorLinea[0], $dataPorLinea[1], $dataPorLinea[2], $dataPorLinea[3],
                            $dataPorLinea[4], $dataPorLinea[5], $dataPorLinea[6]));
                            var_dump($dataPorLinea);
            }
        }
        fclose($archivo);
        return $arrayRetorno;
    }

    public static function LeerCSV($path="./CSV/trabajadores.csv")
    {
        $archivo = fopen($path, 'r');
        if($archivo != false)
        {   
            $str = "";
            $str.= " <table style='border: 1px solid black; border-collapse: collapse;'>
                        <theader>
                            <tr>
                                <th style='border: 1px solid black; padding: 1px 2px;text-align: center;'> id </th>
                                <th style='border: 1px solid black; padding: 1px 2px;text-align: center;'> Username </th>
                                <th style='border: 1px solid black; padding: 1px 2px;text-align: center;'> Password </th>
                                <th style='border: 1px solid black; padding: 1px 2px;text-align: center;'> Es admin </th>
                                <th style='border: 1px solid black; padding: 1px 2px;text-align: center;'> Rol </th>
                                <th style='border: 1px solid black; padding: 1px 2px;text-align: center;'> Fecha inicio de actividades </th>
                                <th style='border: 1px solid black; padding: 1px 2px;text-align: center;'> Fecha fin de actividades </th>
                            </tr>
                        </theader>
                    ";
            $contar = 0;
            while(($datos = fgetcsv($archivo))!== false)
            {
                for($i = 0; $i < count($datos); $i++)
                {
                    if($contar == 0){
                        $str .= "<tr>";
                    }
                    
                    $str.= "<td style='border: 1px solid black; padding: 5px 10px;
                    text-align: center;'> {$datos[$i]} </td>"; 
                    
                    if($contar == 6){
                        $str.= "</tr>";
                        $contar = 0;
                    }else{
                        $contar++;
                    }

                }
            }
            $str.="</table>";
            fclose($archivo);
        }
        else
        {
            echo "<br>Error al leer el archivo<br>";
        }

        return $str;
    }

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