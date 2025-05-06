<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Comment;

class CommentForm extends Component
{
	public $database;
	public $comment;
	public $database_id;
	public $user_id;
	public $text;


	protected $rules = [
		'text' => 'required',
	];

	public function mount($database, $comment = null)
	{
		$this->database = $database;
		if($comment)
		{
			$this->comment = $comment;
			$this->database_id = $comment->database_id;
			$this->user_id = $comment->user_id;
			$this->text = $comment->text;
		}
		else
		{
			$this->database_id = $this->database->id;
			$this->user_id = auth()->id();
		}
	}

	public function save()
	{
		$this->validate();

		$isNew = !$this->comment;

		if($isNew)
		{
			$this->comment = new Comment();
		}
		
		$this->comment->database_id = $this->database_id;
		$this->comment->user_id = $this->user_id;
		$this->comment->text = $this->text;

		$this->comment->save();

    session()->flash('message', $isNew ? 'comment created successfully.' : 'comment updated successfully.');

		//$user = \App\Models\User::where('id', $database->user_id)->first();
		return redirect()->route('databases.show',[ 'database' => $this->database->id ]);
		//route('databases.show', $database->id) 

    //return redirect()->route('databases.comments', $this->database);
	}

	public function render()
	{
			return view('livewire.comment-form');
	}
}