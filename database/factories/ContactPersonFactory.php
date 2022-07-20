<?php

namespace Database\Factories;

use App\Models\ContactPerson;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactPersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContactPerson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::where('role',User::CLIENT)->get();
        return [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'user_id' => $user->random(),
        ];
    }
}
