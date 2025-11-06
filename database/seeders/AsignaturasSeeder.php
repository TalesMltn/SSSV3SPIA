<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asignatura;

class AsignaturasSeeder extends Seeder
{
    public function run()
    {
        $asignaturas = [
            ['nombre' => 'Algoritmos y Estructuras de Datos', 'codigo' => 'ASIG001', 'creditos' => 4],
            ['nombre' => 'Programación Avanzada / Lenguajes de Programación', 'codigo' => 'ASIG002', 'creditos' => 4],
            ['nombre' => 'Sistemas Operativos y Concurrencia', 'codigo' => 'ASIG003', 'creditos' => 3],
            ['nombre' => 'Bases de Datos y SQL Avanzado', 'codigo' => 'ASIG004', 'creditos' => 3],
            ['nombre' => 'Redes y Protocolos de Comunicación', 'codigo' => 'ASIG005', 'creditos' => 3],
            ['nombre' => 'Ingeniería de Software y Metodologías Ágiles', 'codigo' => 'ASIG006', 'creditos' => 4],
            ['nombre' => 'Electrónica y Sistemas Embebidos', 'codigo' => 'ASIG007', 'creditos' => 3],
            ['nombre' => 'Control de Procesos y Automatización Industrial', 'codigo' => 'ASIG008', 'creditos' => 3],
            ['nombre' => 'Inteligencia Artificial y Machine Learning', 'codigo' => 'ASIG009', 'creditos' => 4],
            ['nombre' => 'Ciberseguridad y Seguridad de Sistemas', 'codigo' => 'ASIG010', 'creditos' => 3],
            ['nombre' => 'Simulación y Modelado de Sistemas', 'codigo' => 'ASIG011', 'creditos' => 3],
            ['nombre' => 'Cloud Computing y Arquitectura de Sistemas Distribuidos', 'codigo' => 'ASIG012', 'creditos' => 4],
        ];

        foreach ($asignaturas as $asig) {
            Asignatura::updateOrCreate(
                ['codigo' => $asig['codigo']],
                $asig
            );
        }
    }
}
