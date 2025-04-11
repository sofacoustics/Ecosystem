<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Comment::create(array(
				'database_id' => 1,
        'user_id' => 1, 
        'text' => 'hey, this is a comment!',
        ));

       Comment::create(array(
				'database_id' => 1,
        'user_id' => 2, 
        'text' => 'hey, this is a second comment, written by somebody else!',
        ));

       Comment::create(array(
				'database_id' => 2,
        'user_id' => 1, 
        'text' => 'this comment belongs to the @database=1.',
        ));

        //
    }
}
