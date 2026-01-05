<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PatologiaFactory extends Factory
{
    public function definition(): array
    {
        // Lista de diagnósticos dermatológicos comunes
        $diagnosticos = [
            'Acné Vulgar Grado II',
            'Acné Quístico',
            'Dermatitis Atópica',
            'Dermatitis Seborreica',
            'Rosácea Eritematotelangiectásica',
            'Psoriasis en Placas',
            'Melasma Facial',
            'Alopecia Areata',
            'Alopecia Androgenética',
            'Onicomicosis (Hongos en uñas)',
            'Tinea Corporis',
            'Carcinoma Basocelular',
            'Queratosis Actínica',
            'Verrugas Virales',
            'Vitíligo',
            'Urticaria Crónica'
        ];

        // Observaciones típicas de la especialidad
        $observaciones = [
            'Paciente refiere brotes con el estrés.',
            'Se indica tratamiento con Isotretinoína.',
            'Uso estricto de protector solar SPF 50+.',
            'Lesión asimétrica con bordes irregulares.',
            'Prurito intenso en zona afectada.',
            'Se realiza biopsia de piel (Punch 4mm).',
            'Antecedentes familiares positivos.',
            'Mejora con corticoides tópicos.',
            'Se programa sesión de Crioterapia.',
            'Control en 21 días.'
        ];

        return [
            'nombre' => fake()->randomElement($diagnosticos),
            // 70% de probabilidad de tener una observación, 30% vacío
            'observaciones' => fake()->optional(0.7)->randomElement($observaciones),
        ];
    }
}
