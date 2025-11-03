<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'author_id' => 1,
                'content'   => 'Safety reminder: Always have your emergency go-bag ready. Include water, food, flashlight, first aid kit, important documents, and medicines. #BeFrepared #SalbaKitaPH',
                'category'  => 'tips'
            ],
            [
                'author_id' => 2,
                'content'   => 'Community update: Evacuation drill scheduled for next Saturday at Naga City Sports Complex. All residents are encouraged to participate. This is important for our preparedness!',
                'category'  => 'update'
            ],
            [
                'author_id' => 3,
                'content'   => 'During last week\'s flooding, our barangay came together to help each other evacuate. Salamat sa lahat ng volunteers! This is what community spirit looks like. 🙏',
                'category'  => 'story'
            ],
            [
                'author_id' => 2,
                'content'   => 'Typhoon season tip: Secure loose items outside your house. Trim tree branches near power lines. Stock up on emergency supplies now, not when the typhoon is approaching!',
                'category'  => 'tips'
            ],
            [
                'author_id' => 1,
                'content'   => 'New evacuation center opened in Barangay Calabanga. Fully equipped with medical facilities, food supplies, and emergency generators. Know your nearest evacuation center!',
                'category'  => 'update'
            ],
            [
                'author_id' => 4,
                'content'   => 'Earthquake preparedness: Drop, Cover, and Hold On! Stay away from windows and heavy furniture. After the shaking stops, check for injuries and damage before evacuating.',
                'category'  => 'tips'
            ],
            [
                'author_id' => 3,
                'content'   => 'Grateful to our local officials and volunteers who worked tirelessly during the recent flood. You are true heroes of our community! 💪❤️',
                'category'  => 'story'
            ],
            [
                'author_id' => 2,
                'content'   => 'Weather update: PAGASA monitoring low pressure area east of Mindanao. May develop into tropical depression. Stay informed and prepare your emergency kits.',
                'category'  => 'update',
            ]
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
