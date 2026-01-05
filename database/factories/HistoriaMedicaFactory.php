<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\HistoriaMedica;
use App\Models\Patologia;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoriaMedica>
 */
class HistoriaMedicaFactory extends Factory
{
    public function definition(): array
    {
        $genero = fake()->randomElement(['Masculino', 'Femenino']);
        $estadoCivil = fake()->randomElement(['Soltero', 'Casado', 'Divorciado', 'Viudo']);

        return [
            'cedula' => 'V-' . fake()->unique()->numberBetween(3000000, 32000000), // Rango de cédulas realista Venezuela

            'nombres' => fake()->firstName($genero == 'Masculino' ? 'male' : 'female'),
            'apellidos' => fake()->lastName() . ' ' . fake()->lastName(),

            'fecha_nacimiento' => fake()->dateTimeBetween('-70 years', '-2 years'), // Desde niños hasta ancianos
            'lugar_nacimiento' => fake()->city(),

            'direccion' => fake()->address(),

            // Generador de teléfonos móviles venezolanos (0414, 0424, 0412)
            'telefono' => '04' . fake()->randomElement(['12', '14', '24', '16', '26']) . '-' . fake()->numerify('#######'),
            'telefono_casa' => fake()->optional(0.4)->numerify('02##-#######'), // Solo 40% tiene fijo

            'email' => fake()->unique()->safeEmail(),

            'profesion' => fake()->jobTitle(),
            'ocupacion' => fake()->randomElement(['Estudiante', 'Docente', 'Ingeniero', 'Comerciante', 'Abogado', 'Ama de Casa', 'Agricultor']),

            // Referidos típicos en consulta privada
            'referido' => fake()->randomElement([
                'Instagram @dermatologia',
                'Dr. Pérez (Pediatra)',
                'Dra. Silva (Medicina Interna)',
                'Recomendación familiar',
                'Google Maps',
                'Seguro Médico'
            ]),

            'estado_civil' => $estadoCivil,
            'genero' => $genero,

            'seguro' => fake()->randomElement(['Particular', 'Seguros Mercantil', 'Seguros Caracas', 'Fasmij', 'Seguros Altamira']),

            // El médico tratante (normalmente serás tú o el dueño del sistema)
            'medico' => 'Dr. ' . fake()->lastName() . ' (Dermatólogo)',
        ];
    }

    /**
     * Configurar el factory para crear patologías dermatológicas automáticamente.
     */
    public function configure()
    {
        return $this->afterCreating(function (HistoriaMedica $historia) {
            // Entre 0 y 4 patologías por paciente
            $cantidad = rand(0, 4);

            if ($cantidad > 0) {
                Patologia::factory()
                    ->count($cantidad)
                    ->create([
                        'historia_medica_id' => $historia->id
                    ]);
            }
        });
    }
}
