<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = \App\Models\Contact::class;

    public function definition()
    {
        return [
            'last_name'   => $this->faker->lastName(),
            'first_name'  => $this->faker->firstName(),
            'gender'      => $this->faker->randomElement([1, 2, 3]),
            'email'       => $this->faker->unique()->safeEmail(),
            'tel'         => $this->faker->numerify('080########'),
            'address'     => $this->faker->address(),
            'building'    => $this->faker->secondaryAddress(),
            'categry_id' => $this->faker->numberBetween(1, 5),    
            'detail'      => $this->faker->realText(100),
        ];
    }
}
