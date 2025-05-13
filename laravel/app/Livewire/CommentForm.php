<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Comment;

class CommentForm extends Component
{
	public $commentable;
	public $commentable_type;
	public $user_id;
	public $text;


	protected $rules = [
		'text' => 'required',
	];

	public function mount($commentable, $comment = null)
	{
		$this->commentable = $commentable;
		$this->commentable_id = $commentable->id;
		$this->commentable_type = get_class($commentable);
		if($comment)
		{
			$this->comment = $comment;
			$this->user_id = $comment->user_id;
			$this->text = $comment->text;
		}
		else
		{
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
		
		$this->comment->commentable_id = $this->commentable_id;
		$this->comment->commentable_type = $this->commentable_type;
		$this->comment->user_id = $this->user_id;
		$this->comment->text = $this->text;

		$this->comment->save();

		session()->flash('message', $isNew ? 'comment created successfully.' : 'comment updated successfully.');

		if($commentable_type === 'App\Models\Database')
			return redirect()->route('databases.show',[ 'database' => $this->commentable->id ]);
		else
			return redirect()->route('tools.show',[ 'tool' => $this->commentable->id ]);

    //return redirect()->route('databases.comments', $this->database);
	}

	public function render()
	{
			return view('livewire.comment-form', ['comment' => $this->comment]);
	}
}