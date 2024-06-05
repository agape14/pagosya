<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Propietario;

class UpdateDepartamentos extends Command
{
    protected $signature = 'update:departamentos';
    protected $description = 'Actualiza el campo departamento de los propietarios según los números de piso y departamento especificados';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Definir la cantidad de pisos y departamentos por piso
        $pisos = 15;
        $departamentosPorPiso = 8;

        // Obtener todos los propietarios
        $propietarios = Propietario::all();

        // Inicializar el contador de propietarios
        $contador = 0;

        // Recorrer cada propietario
        foreach ($propietarios as $propietario) {
            // Calcular el piso y el número de departamento
            $piso = floor($contador / $departamentosPorPiso) + 1;
            $departamentoEnPiso = ($contador % $departamentosPorPiso) + 1;

            // Calcular el número de departamento
            $numeroDepartamento = ($piso * 100) + $departamentoEnPiso;

            // Actualizar el departamento del propietario
            $propietario->nombre = "Nombre".$numeroDepartamento;
            $propietario->apellido = "Apellido".$numeroDepartamento;
            $propietario->correo_electronico = "correo".$numeroDepartamento."@example.com";
            $propietario->departamento = $numeroDepartamento;
            $propietario->save();

            // Mostrar información en la consola
            $this->info("Actualizado propietario ID {$propietario->id} a departamento {$numeroDepartamento}");

            // Incrementar el contador
            $contador++;
        }

        $this->info('Actualización de departamentos completada.');
    }
}
