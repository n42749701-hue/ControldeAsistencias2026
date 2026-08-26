<?php
require_once __DIR__ . "/../Models/producto.php";
class ProductoController{
    public function getAll()
    {
        $producto=Productos::all();
        echo json_encode($producto);
         
    }
    //actualizar producto
    public function update($id)
    {
        $jsonData=file_get_contents('php://input');
         $data = json_decode($jsonData, true);
       if(json_last_error()!=JSON_ERROR_NONE)
        {
            echo json_encode(
            [
                "status"=>"error codificacion",
                "message"=>json_last_error_msg(),
            ]);
            return;
        }
       
     //"codBarras":"7401005500120",
     if(!isset($data['codBarras']) || trim($data['codBarras'])=="")
        {
         echo json_encode([
                "status" => "error",
                "message" => "El campo Codigo de barras es obligatorio"
            ]);
            return;
        }
     //"descripcion":"Arroz Integral 2kg",
     if(!isset($data['descripcion']) || trim($data['descripcion'])=="")
        {
         echo json_encode(
            [
                "status"=>"error",
                "message"=>"El campo descripcion es obligatorio"
            ]);
            return;
        }
     //"stok":70,
     if(!isset($data['stock']) || trim($data['stock'])=="")
        {
         echo json_encode(
            [
                "status"=>"error",
                "message"=>"El campo stock es obligatorio"
            ]);
            return;
        } elseif(!is_numeric($data['stock']) || $data['stock'] <= 0) {
         echo json_encode(
            [
                "status"=>"error",
                "message"=>"El campo stock debe ser numerico y mayor a 0"
            ]);
            return;
        }
     //precio_unitario:15,
    if(!isset($data['precio_unitario']) || trim($data['precio_unitario'])=="")
        {
         echo json_encode(
            [
                "status"=>"error",
                "message"=>"El campo precio_unitario es obligatorio"
            ]);
            return;
        } elseif(!is_numeric($data['precio_unitario']) || $data['precio_unitario'] <= 0) {
         echo json_encode(
            [
                "status"=>"error",
                "message"=>"El campo precio_unitario debe ser numerico y mayor a 0"
            ]);
            return;
        }
    if(!isset($data['fecha_registro']) || trim($data['fecha_registro'])=="")
        {
         echo json_encode(
            [
                "status"=>"error",
                "message"=>"El campo fecha_registro es obligatorio"
            ]);
            return;
        } elseif(!$this->fechaValida($data['fecha_registro'])) {
         echo json_encode(
            [
                "status"=>"error",
                "message"=>"El campo fecha_registro debe tener formato YYYY-MM-DD"
            ]);
            return;
        }
        $producto=Productos::update($id,$data);
        if($producto) {
            echo json_encode([
                "estado" => true,
                "message" => "Producto actualizado correctamente",
            ]);
            return;
        }
        echo json_encode($producto);  
    }
    //adicionar producto
    public function add()
    {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);
        //validacion
        if(json_last_error()!=JSON_ERROR_NONE)
        {
            echo json_encode(
            [
                "status"=>"error codificacion",
                "message"=>json_last_error_msg(),
            ]);
            return;
        }

        $errores = [];

        if(!isset($data['codBarras']) || trim($data['codBarras'])=="")
        {
            $errores[] = "El campo Codigo de barras es obligatorio";
        }

        if(!isset($data['descripcion']) || trim($data['descripcion'])=="")
        {
            $errores[] = "El campo descripcion es obligatorio";
        }

        if(!isset($data['stock']) || trim($data['stock'])=="")
        {
            $errores[] = "El campo stock es obligatorio";
        } elseif(!is_numeric($data['stock']) || $data['stock'] <= 0) {
            $errores[] = "El campo stock debe ser numerico y mayor a 0";
        }

        if(!isset($data['precio_unitario']) || trim($data['precio_unitario'])=="")
        {
            $errores[] = "El campo precio_unitario es obligatorio";
        } elseif(!is_numeric($data['precio_unitario']) || $data['precio_unitario'] <= 0) {
            $errores[] = "El campo precio_unitario debe ser numerico y mayor a 0";
        }

        if(!isset($data['fecha_registro']) || trim($data['fecha_registro'])=="")
        {
            $errores[] = "El campo fecha_registro es obligatorio";
        } elseif(!$this->fechaValida($data['fecha_registro'])) {
            $errores[] = "El campo fecha_registro debe tener formato YYYY-MM-DD";
        }

        if(count($errores)>0)
        {
            echo json_encode([
                "status" => "error",
                "message" => "Existen errores de validacion",
                "errores" => $errores
            ]);
            return;
        }

        $producto = Productos::add($data);
        if ($producto) {
            echo json_encode([
                "estado" => true,
                "message" => "Producto adicionado correctamente",
            ]);
            return;
        }
        echo json_encode($producto); 
    }
    //eliminar producto
    public function delete($id)
    {
        $producto = Productos::delete($id);
        if ($producto) {
            echo json_encode([
                "estado" => true,
                "message" => "Producto eliminado correctamente",
            ]);
            return;
        }
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar el producto",
        ]);
    }

    private function fechaValida($fecha)
    {
        $fechaFormateada = DateTime::createFromFormat('Y-m-d', $fecha);
        return $fechaFormateada && $fechaFormateada->format('Y-m-d') === $fecha;
    }
}
