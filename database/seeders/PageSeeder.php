<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::create([
            'type' => 'Cookie Policy',
            'name' => 'Cookie Policy',
            'slug' => "cookie",
            'visibility' => "Unlisted",
            'data' => "Lorem ipsum dolor sit amet consectetur, adipisicing elit. In exercitationem explicabo inventore minus vero aliquam corporis sed cumque, fugit corrupti, nisi ratione? Neque temporibus dicta debitis earum nobis ipsum perspiciatis, numquam ullam ea dignissimos veritatis officiis quidem voluptate sequi amet tempora laboriosam asperiores fuga iste consequuntur maiores illo! Officia neque atque fugit sequi ad? Eveniet architecto, dolorem cum consequuntur similique, blanditiis veritatis, expedita perspiciatis porro inventore facere harum ipsam praesentium in et soluta tenetur eos! Cum ipsum enim, ea excepturi voluptas eum, tempora architecto neque quidem quisquam sint recusandae laboriosam, maxime accusantium placeat? Ea atque voluptatibus quasi quisquam veritatis impedit?
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Commodi mollitia amet accusamus perferendis neque? Eos quo dolorem repudiandae laborum magni corrupti omnis voluptatum quidem accusamus, inventore neque quae, aliquam cupiditate nesciunt placeat nulla labore sed sit, possimus distinctio! Laudantium molestias alias id eos veniam. Amet quibusdam tempore veritatis earum! Libero."

        ]);
        Page::create([
            'type' => 'Terms of Service',
            'name' => 'Terms of Service',
            'slug' => "terms",
            'visibility' => "Unlisted",
            'data' => "Lorem ipsum dolor sit amet consectetur, adipisicing elit. In exercitationem explicabo inventore minus vero aliquam corporis sed cumque, fugit corrupti, nisi ratione? Neque temporibus dicta debitis earum nobis ipsum perspiciatis, numquam ullam ea dignissimos veritatis officiis quidem voluptate sequi amet tempora laboriosam asperiores fuga iste consequuntur maiores illo! Officia neque atque fugit sequi ad? Eveniet architecto, dolorem cum consequuntur similique, blanditiis veritatis, expedita perspiciatis porro inventore facere harum ipsam praesentium in et soluta tenetur eos! Cum ipsum enim, ea excepturi voluptas eum, tempora architecto neque quidem quisquam sint recusandae laboriosam, maxime accusantium placeat? Ea atque voluptatibus quasi quisquam veritatis impedit?
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Commodi mollitia amet accusamus perferendis neque? Eos quo dolorem repudiandae laborum magni corrupti omnis voluptatum quidem accusamus, inventore neque quae, aliquam cupiditate nesciunt placeat nulla labore sed sit, possimus distinctio! Laudantium molestias alias id eos veniam. Amet quibusdam tempore veritatis earum! Libero."

        ]);
        Page::create([
            'type' => 'Privacy Policy',
            'name' => 'Privacy Policy',
            'slug' => 'privacy',
            'visibility' => "Unlisted",
            'data' => "Lorem ipsum dolor sit amet consectetur, adipisicing elit. In exercitationem explicabo inventore minus vero aliquam corporis sed cumque, fugit corrupti, nisi ratione? Neque temporibus dicta debitis earum nobis ipsum perspiciatis, numquam ullam ea dignissimos veritatis officiis quidem voluptate sequi amet tempora laboriosam asperiores fuga iste consequuntur maiores illo! Officia neque atque fugit sequi ad? Eveniet architecto, dolorem cum consequuntur similique, blanditiis veritatis, expedita perspiciatis porro inventore facere harum ipsam praesentium in et soluta tenetur eos! Cum ipsum enim, ea excepturi voluptas eum, tempora architecto neque quidem quisquam sint recusandae laboriosam, maxime accusantium placeat? Ea atque voluptatibus quasi quisquam veritatis impedit?
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Commodi mollitia amet accusamus perferendis neque? Eos quo dolorem repudiandae laborum magni corrupti omnis voluptatum quidem accusamus, inventore neque quae, aliquam cupiditate nesciunt placeat nulla labore sed sit, possimus distinctio! Laudantium molestias alias id eos veniam. Amet quibusdam tempore veritatis earum! Libero."

        ]);
    }
}
