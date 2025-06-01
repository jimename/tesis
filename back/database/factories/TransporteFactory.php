<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transporte>
 */
class TransporteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'tipo' => $this->faker->word,
            'marca' => $this->faker->word,
            'placa' => $this->faker->word,
            'color' => $this->faker->word,
            'estado' => 'Activo',
        ];
    }
}
