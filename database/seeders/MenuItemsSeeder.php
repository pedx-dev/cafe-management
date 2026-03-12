<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class MenuItemsSeeder extends Seeder
{
    public function run()
    {
        $items = [
            // Coffee Items (15 items)
            ['name' => 'Espresso', 'description' => 'Strong black coffee made by forcing steam through ground coffee beans.', 'price' => 85.00, 'category' => 'Coffee', 'stock' => 50, 'is_featured' => true, 'calories' => 5, 'ingredients' => 'Coffee beans, Water'],
            ['name' => 'Americano', 'description' => 'Espresso with hot water for a smooth, rich flavor.', 'price' => 95.00, 'category' => 'Coffee', 'stock' => 50, 'is_featured' => false, 'calories' => 10, 'ingredients' => 'Espresso, Hot water'],
            ['name' => 'Cappuccino', 'description' => 'Espresso with steamed milk and a thick layer of foam.', 'price' => 120.00, 'category' => 'Coffee', 'stock' => 50, 'is_featured' => true, 'calories' => 120, 'ingredients' => 'Espresso, Steamed milk, Milk foam'],
            ['name' => 'Latte', 'description' => 'Espresso with steamed milk and a light layer of foam.', 'price' => 130.00, 'category' => 'Coffee', 'stock' => 50, 'is_featured' => true, 'calories' => 150, 'ingredients' => 'Espresso, Steamed milk'],
            ['name' => 'Mocha', 'description' => 'Rich espresso with chocolate and steamed milk.', 'price' => 145.00, 'category' => 'Coffee', 'stock' => 45, 'is_featured' => true, 'calories' => 250, 'ingredients' => 'Espresso, Chocolate, Steamed milk, Whipped cream'],
            ['name' => 'Caramel Macchiato', 'description' => 'Vanilla-flavored latte with caramel drizzle.', 'price' => 150.00, 'category' => 'Coffee', 'stock' => 45, 'is_featured' => false, 'calories' => 240, 'ingredients' => 'Espresso, Vanilla syrup, Steamed milk, Caramel sauce'],
            ['name' => 'Flat White', 'description' => 'Smooth microfoam over espresso.', 'price' => 125.00, 'category' => 'Coffee', 'stock' => 40, 'is_featured' => false, 'calories' => 120, 'ingredients' => 'Espresso, Microfoam milk'],
            ['name' => 'Iced Coffee', 'description' => 'Cold brewed coffee served over ice.', 'price' => 110.00, 'category' => 'Coffee', 'stock' => 50, 'is_featured' => false, 'calories' => 80, 'ingredients' => 'Coffee, Ice, Sugar'],
            ['name' => 'Cold Brew', 'description' => 'Smooth coffee steeped in cold water for 12 hours.', 'price' => 135.00, 'category' => 'Coffee', 'stock' => 35, 'is_featured' => true, 'calories' => 5, 'ingredients' => 'Cold brew concentrate, Ice, Water'],
            ['name' => 'Affogato', 'description' => 'Vanilla ice cream drowned in hot espresso.', 'price' => 160.00, 'category' => 'Coffee', 'stock' => 30, 'is_featured' => false, 'calories' => 200, 'ingredients' => 'Espresso, Vanilla ice cream'],
            ['name' => 'Irish Coffee', 'description' => 'Coffee with Irish cream and whipped cream.', 'price' => 155.00, 'category' => 'Coffee', 'stock' => 25, 'is_featured' => false, 'calories' => 210, 'ingredients' => 'Coffee, Irish cream, Whipped cream, Sugar'],
            ['name' => 'Spanish Latte', 'description' => 'Espresso with condensed and steamed milk.', 'price' => 140.00, 'category' => 'Coffee', 'stock' => 40, 'is_featured' => false, 'calories' => 180, 'ingredients' => 'Espresso, Condensed milk, Steamed milk'],
            ['name' => 'Vanilla Latte', 'description' => 'Classic latte with vanilla syrup.', 'price' => 140.00, 'category' => 'Coffee', 'stock' => 45, 'is_featured' => false, 'calories' => 190, 'ingredients' => 'Espresso, Vanilla syrup, Steamed milk'],
            ['name' => 'Hazelnut Coffee', 'description' => 'Rich coffee with hazelnut flavor.', 'price' => 135.00, 'category' => 'Coffee', 'stock' => 40, 'is_featured' => false, 'calories' => 170, 'ingredients' => 'Coffee, Hazelnut syrup, Milk'],
            ['name' => 'Vietnamese Coffee', 'description' => 'Strong coffee with sweet condensed milk.', 'price' => 125.00, 'category' => 'Coffee', 'stock' => 35, 'is_featured' => false, 'calories' => 150, 'ingredients' => 'Dark roast coffee, Condensed milk, Ice'],

            // Tea Items (8 items)
            ['name' => 'Green Tea', 'description' => 'Freshly brewed Japanese green tea.', 'price' => 75.00, 'category' => 'Tea', 'stock' => 60, 'is_featured' => true, 'calories' => 2, 'ingredients' => 'Green tea leaves, Hot water'],
            ['name' => 'Earl Grey', 'description' => 'Classic black tea with bergamot essence.', 'price' => 80.00, 'category' => 'Tea', 'stock' => 55, 'is_featured' => false, 'calories' => 3, 'ingredients' => 'Black tea, Bergamot oil'],
            ['name' => 'Chamomile Tea', 'description' => 'Soothing herbal tea perfect for relaxation.', 'price' => 85.00, 'category' => 'Tea', 'stock' => 50, 'is_featured' => false, 'calories' => 0, 'ingredients' => 'Chamomile flowers, Hot water'],
            ['name' => 'Matcha Latte', 'description' => 'Premium Japanese matcha with steamed milk.', 'price' => 145.00, 'category' => 'Tea', 'stock' => 40, 'is_featured' => true, 'calories' => 140, 'ingredients' => 'Matcha powder, Steamed milk, Sugar'],
            ['name' => 'Thai Tea', 'description' => 'Sweet and creamy Thai iced tea.', 'price' => 105.00, 'category' => 'Tea', 'stock' => 45, 'is_featured' => true, 'calories' => 180, 'ingredients' => 'Thai tea mix, Condensed milk, Evaporated milk, Ice'],
            ['name' => 'Lemon Tea', 'description' => 'Refreshing black tea with fresh lemon.', 'price' => 90.00, 'category' => 'Tea', 'stock' => 50, 'is_featured' => false, 'calories' => 30, 'ingredients' => 'Black tea, Lemon, Honey'],
            ['name' => 'Milk Tea', 'description' => 'Classic milk tea with tapioca pearls.', 'price' => 110.00, 'category' => 'Tea', 'stock' => 45, 'is_featured' => true, 'calories' => 200, 'ingredients' => 'Black tea, Milk, Tapioca pearls, Sugar'],
            ['name' => 'Jasmine Tea', 'description' => 'Fragrant jasmine-infused green tea.', 'price' => 85.00, 'category' => 'Tea', 'stock' => 50, 'is_featured' => false, 'calories' => 2, 'ingredients' => 'Green tea, Jasmine flowers'],

            // Pastry Items (7 items)
            ['name' => 'Croissant', 'description' => 'Buttery, flaky French pastry.', 'price' => 95.00, 'category' => 'Pastry', 'stock' => 40, 'is_featured' => true, 'calories' => 230, 'ingredients' => 'Flour, Butter, Yeast, Sugar, Salt'],
            ['name' => 'Chocolate Croissant', 'description' => 'Flaky croissant filled with rich chocolate.', 'price' => 115.00, 'category' => 'Pastry', 'stock' => 35, 'is_featured' => true, 'calories' => 290, 'ingredients' => 'Croissant dough, Dark chocolate'],
            ['name' => 'Blueberry Muffin', 'description' => 'Moist muffin bursting with fresh blueberries.', 'price' => 85.00, 'category' => 'Pastry', 'stock' => 45, 'is_featured' => false, 'calories' => 320, 'ingredients' => 'Flour, Blueberries, Sugar, Eggs, Butter'],
            ['name' => 'Cinnamon Roll', 'description' => 'Sweet roll with cinnamon and cream cheese frosting.', 'price' => 105.00, 'category' => 'Pastry', 'stock' => 30, 'is_featured' => true, 'calories' => 420, 'ingredients' => 'Dough, Cinnamon, Brown sugar, Cream cheese frosting'],
            ['name' => 'Danish Pastry', 'description' => 'Sweet pastry with fruit or custard filling.', 'price' => 100.00, 'category' => 'Pastry', 'stock' => 35, 'is_featured' => false, 'calories' => 350, 'ingredients' => 'Pastry dough, Custard, Fresh fruit'],
            ['name' => 'Banana Bread', 'description' => 'Moist and flavorful homemade banana bread.', 'price' => 75.00, 'category' => 'Pastry', 'stock' => 40, 'is_featured' => false, 'calories' => 280, 'ingredients' => 'Bananas, Flour, Sugar, Eggs, Butter'],
            ['name' => 'Scone', 'description' => 'Traditional British scone with clotted cream.', 'price' => 90.00, 'category' => 'Pastry', 'stock' => 35, 'is_featured' => false, 'calories' => 260, 'ingredients' => 'Flour, Butter, Cream, Sugar, Raisins'],

            // Sandwich Items (5 items)
            ['name' => 'Club Sandwich', 'description' => 'Triple-decker with turkey, bacon, lettuce, and tomato.', 'price' => 195.00, 'category' => 'Sandwich', 'stock' => 30, 'is_featured' => true, 'calories' => 480, 'ingredients' => 'Bread, Turkey, Bacon, Lettuce, Tomato, Mayo'],
            ['name' => 'BLT Sandwich', 'description' => 'Classic bacon, lettuce, and tomato sandwich.', 'price' => 175.00, 'category' => 'Sandwich', 'stock' => 35, 'is_featured' => false, 'calories' => 420, 'ingredients' => 'Bread, Bacon, Lettuce, Tomato, Mayo'],
            ['name' => 'Tuna Melt', 'description' => 'Tuna salad with melted cheese on toasted bread.', 'price' => 185.00, 'category' => 'Sandwich', 'stock' => 30, 'is_featured' => false, 'calories' => 450, 'ingredients' => 'Bread, Tuna, Cheese, Mayo, Celery'],
            ['name' => 'Ham & Cheese Panini', 'description' => 'Grilled panini with ham and melted cheese.', 'price' => 165.00, 'category' => 'Sandwich', 'stock' => 35, 'is_featured' => true, 'calories' => 390, 'ingredients' => 'Panini bread, Ham, Cheese, Butter'],
            ['name' => 'Chicken Caesar Wrap', 'description' => 'Grilled chicken with Caesar dressing in a wrap.', 'price' => 180.00, 'category' => 'Sandwich', 'stock' => 30, 'is_featured' => false, 'calories' => 420, 'ingredients' => 'Tortilla, Chicken, Romaine, Caesar dressing, Parmesan'],

            // Dessert Items (5 items)
            ['name' => 'Cheesecake', 'description' => 'Creamy New York style cheesecake with berry compote.', 'price' => 145.00, 'category' => 'Dessert', 'stock' => 25, 'is_featured' => true, 'calories' => 450, 'ingredients' => 'Cream cheese, Graham cracker crust, Berries, Sugar'],
            ['name' => 'Chocolate Cake', 'description' => 'Rich chocolate layer cake with ganache.', 'price' => 135.00, 'category' => 'Dessert', 'stock' => 30, 'is_featured' => true, 'calories' => 520, 'ingredients' => 'Chocolate, Flour, Eggs, Sugar, Cocoa powder'],
            ['name' => 'Tiramisu', 'description' => 'Classic Italian dessert with coffee and mascarpone.', 'price' => 155.00, 'category' => 'Dessert', 'stock' => 20, 'is_featured' => true, 'calories' => 380, 'ingredients' => 'Ladyfingers, Espresso, Mascarpone, Cocoa powder'],
            ['name' => 'Apple Pie', 'description' => 'Homemade apple pie with cinnamon and vanilla ice cream.', 'price' => 125.00, 'category' => 'Dessert', 'stock' => 25, 'is_featured' => false, 'calories' => 410, 'ingredients' => 'Apples, Pie crust, Cinnamon, Sugar, Vanilla ice cream'],
            ['name' => 'Brownie Sundae', 'description' => 'Warm chocolate brownie with vanilla ice cream.', 'price' => 140.00, 'category' => 'Dessert', 'stock' => 30, 'is_featured' => false, 'calories' => 550, 'ingredients' => 'Chocolate brownie, Vanilla ice cream, Chocolate sauce, Whipped cream'],
        ];
        
        foreach ($items as $item) {
            MenuItem::create($item);
        }
        
        echo "40 menu items created successfully!\n";
    }
}