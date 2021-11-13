<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $pro_name = $this->faker->unique()->words($nb=4, $asText=true);
        return [
            'name' => $pro_name,
            'slug' => Str::slug($pro_name),
            'short_description' => $this->faker->text(200),
            'description' => $this->faker->text(400),
            'regular_price' => $this->faker->numberBetween(10, 500),
            'SKU' => 'DIGI'.$this->faker->unique()->numberBetween(100, 500),
            'stock_status' => 'instock',
            'quantity' => $this->faker->numberBetween(200, 500),
            'image' => 'digital_'.$this->faker->unique()->numberBetween(1, 22),
            'category_id' => $this->faker->numberBetween(1, 6),
        ];
    }
}
