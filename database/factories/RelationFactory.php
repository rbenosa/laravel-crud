<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Person;
use App\Models\Relation;
use Illuminate\Database\Eloquent\Factories\Factory;

class RelationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Relation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'person_id' => Person::inRandomOrder()->first(),
            'organization_id' => Organization::inRandomOrder()->first(),
        ];
    }
}
