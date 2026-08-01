<?php

namespace Database\Seeders;

use App\Models\Barberia;
use App\Models\HorarioAtencion;
use App\Models\PreguntaFrecuente;
use Illuminate\Database\Seeder;

class LandingConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        $barberia = Barberia::first();

        if (! $barberia) {
            return;
        }

        foreach (range(0, 6) as $dia) {
            HorarioAtencion::updateOrCreate(
                ['id_barberia' => $barberia->id_barberia, 'dia_semana' => $dia],
                [
                    'abierto' => $dia !== 6,
                    'hora_apertura' => $dia !== 6 ? '09:00' : null,
                    'hora_cierre' => $dia !== 6 ? '19:00' : null,
                ]
            );
        }

        $preguntas = [
            ['¿Necesito reservar antes de acudir?', 'Sí. Escríbenos por WhatsApp para consultar disponibilidad y confirmar tu horario.'],
            ['¿La solicitud enviada por WhatsApp confirma mi cita?', 'No automáticamente. Tu cita queda confirmada cuando nuestro equipo responde y acuerda contigo el horario.'],
            ['¿Qué métodos de pago aceptan?', 'Consulta por WhatsApp los métodos de pago disponibles antes de tu visita.'],
            ['¿Qué ocurre si llego tarde?', 'Te recomendamos avisarnos por WhatsApp. Dependiendo de la agenda, podremos ajustar el servicio o proponerte otro horario.'],
        ];

        foreach ($preguntas as $orden => [$pregunta, $respuesta]) {
            PreguntaFrecuente::updateOrCreate(
                ['id_barberia' => $barberia->id_barberia, 'pregunta' => $pregunta],
                ['respuesta' => $respuesta, 'orden' => $orden + 1, 'activo' => true]
            );
        }
    }
}
