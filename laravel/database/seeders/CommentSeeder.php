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
		'commentable_id' => 1,
		'commentable_type' => 'App\Models\Database',
		'user_id' => 1, 
		'text' => 'hey, this is a comment!',
		));

		Comment::create(array(
		'commentable_id' => 1,
		'commentable_type' => 'App\Models\Database',
		'user_id' => 2, 
		'text' => 'hey, this is a second comment, written by somebody else!',
		));

		Comment::create(array(
		'commentable_id' => 2,
		'commentable_type' => 'App\Models\Database',
		'user_id' => 1, 
		'text' => 'this comment belongs to the @database=2.',
		));

		Comment::create(array(
		'commentable_id' => 1,
		'commentable_type' => 'App\Models\Tool',
		'user_id' => 2, 
		'text' => 'this comment belongs to the Tool with ID 1.',
		));
	}
}
