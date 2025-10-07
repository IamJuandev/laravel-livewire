<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estudiante>
 */
class EstudianteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombres' => fake()->firstName(),
            'apellidos' => fake()->lastName(),
            'identificacion' => fake()->unique()->numerify('##########'),
            'carrera' => fake()->randomElement(['Ingeniería de Sistemas', 'Diseño Gráfico', 'Administración de Empresas', 'Medicina']),
            'id_qr' => fake()->unique()->uuid(),
        ];
    }
}
