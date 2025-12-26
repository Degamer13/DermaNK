<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoriaMedica>
 */
class HistoriaMedicaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generamos un género aleatorio para coordinar con el nombre si quisieras,
        // pero por simpleza lo haremos aleatorio simple.
        $genero = fake()->randomElement(['Masculino', 'Femenino']);
        $estadoCivil = fake()->randomElement(['Soltero', 'Casado', 'Divorciado', 'Viudo']);

        return [
            // Cédula única tipo V-12345678
            'cedula' => 'V-' . fake()->unique()->numberBetween(1000000, 35000000),

            'nombres' => fake()->firstName($genero == 'Masculino' ? 'male' : 'female'),
            'apellidos' => fake()->lastName() . ' ' . fake()->lastName(),

            'fecha_nacimiento' => fake()->dateTimeBetween('-80 years', '-18 years'),
            'lugar_nacimiento' => fake()->city(),

            'direccion' => fake()->address(),

            // Teléfonos estilo móvil
            'telefono' => '04' . fake()->randomElement(['12', '14', '16', '24']) . fake()->numerify('#######'),
            'telefono_casa' => '02' . fake()->numerify('#########'), // Opcional

            'email' => fake()->unique()->safeEmail(),

            'profesion' => fake()->jobTitle(),
            'ocupacion' => fake()->randomElement(['Comerciante', 'Estudiante', 'Jubilado', 'Docente', 'Ingeniero', 'Abogado']),
            'referido' => fake()->name(),

            'estado_civil' => $estadoCivil,
            'genero' => $genero,

            'seguro' => fake()->randomElement(['Seguros Mercantil', 'Seguros Caracas', 'Particular', 'Seguros La Previsora']),
            'medico' => 'Dr. ' . fake()->lastName(),
        ];
    }
}
