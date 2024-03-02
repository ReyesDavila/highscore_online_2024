<?php
    // Verificar si la clave "accion" está presente en el método POST
    if (isset($_POST["accion"])) 
    {
        $accion = $_POST["accion"];

        // Ruta del archivo JSON
        $rutaJson = "puntaje.json";
        if(!file_exists($rutaJson))
        {

          // Definir la clave "serial"
          $serial = "DXN".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 17);

          // Crear un array para la clave "jugadores"
          $jugadores = array();

          // Bucle for para crear 10 jugadores
          for ($i = 0; $i < 10; $i++) {
            // Agregar jugador al array
            $jugadores[] = array(
              "nombre" => "<nobody>",
              "puntaje" => 0,
              "dato" => ""
            );
          }

          // Crear el string JSON
          $datosJson = array(
            "serial" => $serial,
            "jugadores" => $jugadores
          );

          // Escribir archivo JSON
          file_put_contents($rutaJson, json_encode($datosJson, JSON_PRETTY_PRINT));

        }

        // Decodificar el archivo JSON
        $datosJson = json_decode(file_get_contents($rutaJson), true);

        //Si la accion no es adecuada
        $respuesta = array(
            "mensaje" => "La acción no puede ser procesada",
            "serial" => $datosJson["serial"]
          );
    } 
    else 
    {
        echo "Falta la clave 'accion'";
        exit();
    }

    if($accion=="solicitar serial")
    {
        $respuesta = array(
            "mensaje" => "Serial enviado",
            "serial" => $datosJson["serial"]
          );
    }

    if($accion=="solicitar puntaje")
    {
        $respuesta = array(
            "mensaje" => "Puntaje enviado",
            "serial" => $datosJson["serial"],
            "puntaje" => json_encode($datosJson)
          );
    }

    // Si la acción es "agregar puntaje"
    if ($_POST["accion"] === "agregar puntaje") {
    
      // Obtener datos del POST
      $nombre = $_POST["nombre"];
      $puntaje = floatval($_POST["puntaje"]);//numero real
      $dato = $_POST["dato"];
    
      // Obtener puntaje mínimo
      $puntajeMinimo = min(array_column($datosJson["jugadores"], "puntaje"));
    
      // Si el nuevo puntaje es mayor o igual al mínimo
      if ($puntaje >= $puntajeMinimo) 
      {
    
        // Eliminar último jugador
        array_pop($datosJson["jugadores"]);
    
        // Agregar nuevo jugador
        $datosJson["jugadores"][] = ["nombre" => $nombre, "puntaje" => $puntaje, "dato" => $dato];
    
        // Ordenar por puntaje
        usort($datosJson["jugadores"], function($a, $b) {
          return $b["puntaje"] <=> $a["puntaje"];
        });

        $datosJson["serial"] = "DXN".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 17);
    
        // Escribir archivo JSON
        file_put_contents($rutaJson, json_encode($datosJson, JSON_PRETTY_PRINT));

        $respuesta = array(
            "mensaje" => "Puntaje agregado",
            "serial" => $datosJson["serial"],
            "puntaje" => json_encode($datosJson)
          );

      } else {

        $respuesta = array(
            "mensaje" => "Puntaje no agregado",
            "serial" => $datosJson["serial"],
            "puntaje" => json_encode($datosJson)
          );

      }
    }

    // Si la acción es "borrar puntaje"
    if ($_POST["accion"] === "borrar puntaje") 
    {

        // Recorrer el array de jugadores
        foreach ($datosJson["jugadores"] as &$jugador) {

        // Cambiar el nombre a "<nobody>"
        $jugador["nombre"] = "<nobody>";

        // Cambiar el puntaje a "0"
        $jugador["puntaje"] = 0;

        // Cambiar el dato a ""
        $jugador["dato"] = "";
        }

        $datosJson["serial"] = "DXN".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 17);
    
        // Escribir el archivo JSON
        file_put_contents($rutaJson, json_encode($datosJson, JSON_PRETTY_PRINT));

        // Respuesta
        $respuesta = array(
        "mensaje" => "Puntaje borrado",
        "serial" => $datosJson["serial"],
        "puntaje" => json_encode($datosJson)
        );
    }   






    // Si la acción es "default"
    if ($_POST["accion"] === "default") {
    
        // Variable con el JSON por defecto
        $default = '{
            "serial": "xg0007",
            "jugadores": [
              {
                "nombre": "Marie Curie",
                "puntaje": 1500,
                "dato": ""
              },
              {
                "nombre": "Albert Einstein",
                "puntaje": 1400,
                "dato": ""
              },
              {
                "nombre": "Isaac Newton",
                "puntaje": 1300,
                "dato": ""
              },
              {
                "nombre": "Galileo Galilei",
                "puntaje": 1200,
                "dato": ""
              },
              {
                "nombre": "Stephen Hawking",
                "puntaje": 1100,
                "dato": ""
              },
              {
                "nombre": "Louis Pasteur",
                "puntaje": 1000,
                "dato": ""
              },
              {
                "nombre": "Charles Darwin",
                "puntaje": 900,
                "dato": ""
              },
              {
                "nombre": "Nikola Tesla",
                "puntaje": 800,
                "dato": ""
              },
              {
                "nombre": "Thomas Edison",
                "puntaje": 700,
                "dato": ""
              },
              {
                "nombre": "Alexander Graham Bell",
                "puntaje": 600,
                "dato": ""
              }
            ]
          }';

        // Decodificar el JSON por defecto
        $datosJson = json_decode($default, true);

        $datosJson["serial"] = "DXN".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 17);
    
        // Escribir archivo JSON
        file_put_contents($rutaJson, json_encode($datosJson, JSON_PRETTY_PRINT));

        $respuesta = array(
            "mensaje" => "Puntaje por defecto",
            "serial" => $datosJson["serial"],
            "puntaje" => json_encode($datosJson)
          );
    }








    $json = json_encode($respuesta);
    echo $json;
?>
