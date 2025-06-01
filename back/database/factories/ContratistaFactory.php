<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contratista>
 */
class ContratistaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->name,
            'direccion' => $this->faker->address,
            'telefono' => $this->faker->phoneNumber,
            'ci' => $this->faker->unique()->randomNumber(9),
            'estado' => 'Activo',
        ];
    }
}
