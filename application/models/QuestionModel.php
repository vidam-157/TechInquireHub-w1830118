<?php

class QuestionModel extends CI_Model{

	public function getAllQuestions() {

		$question = $this->db->get("Questions");

		if($question->num_rows() > 0) {
			$question_array = $question->result();

			foreach ($question_array as $question) {
				$question_id = $question->questionid;
				$tag_query = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question_id)
					->get();
				$tags = $tag_query->result();
				$question->tags = array_column($tags, 'tags');
			}
			return $question_array;
		}
		else {
			return new stdClass();
		}

	}

	public function getQuestion($question_id) {
		{ 
			$question = $this->db->get_where("Questions", array('questionid' => $question_id))->row();

			if ($question) {
				$tag_query = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question->questionid)
					->get();
				$tags = $tag_query->result();
				$question->tags = array_column($tags, 'tags');

				return $question;
			} 
			else {
				return null; // Return null if no question found
			}
		}
	}

	public function getSearchQuestions($searchWord) {

		$this->db->like('title', $searchWord);
		$this->db->or_like('question', $searchWord);
		$this->db->join('Tags', 'Questions.questionid = Tags.questionid', 'left');
		$this->db->or_like('tags', $searchWord);
		$question = $this->db->get("Questions");

		if ($question->num_rows() > 0) {
			$question_array = $question->result();

			foreach ($question_array as $question) {
				$question_id = $question->questionid;
				$tag_query = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question_id)
					->get();
				$tags = $tag_query->result();
				$question->tags = array_column($tags, 'tags');
			}
			return $question_array;
		} 
		else {
			return new stdClass();
		}
	}

	public function addQuestion($userid, $title, $question, $category, $qaddeddate, $tagArray, $imageurl) {
		$this->db->trans_start(); // Start transaction

		$questionData = array(
			'userid' => $userid,
			'title' => $title,
			'question' => $question,
			'questionimage' => $imageurl, // Ensure that the questionimage field is correctly set here
			'category' => $category,
			'qaddeddate' => $qaddeddate,
		);

		// Insert into 'Questions' table
		$insertDetails = $this->db->insert('Questions', $questionData);

		// Check if the insertion was successful
		if ($insertDetails) {
			// Get the last inserted question ID
			$questionId = $this->db->insert_id();

			// Insert into 'Tags' table
			foreach ($tagArray as $tag) {
				$tagData = array(
					'questionid' => $questionId, // Use the retrieved question ID
					'tags' => trim($tag)
				);
				$this->db->insert('Tags', $tagData);
			}
		}

		$this->db->trans_complete(); // Complete transaction

		return $insertDetails && $this->db->trans_status(); // Return transaction status
	}

	public function upvote($questionid) {

		$currentViewStatus = $this->db->select('viewstatus')
			->from('Questions')
			->where('questionid', $questionid)
			->get()
			->row()
			->viewstatus;

		$newViewStatus = $currentViewStatus + 1;

		$updateViewStatus = $this->db->where('questionid', $questionid)
			->update('Questions', array('viewstatus' => $newViewStatus));

		return $updateViewStatus;
	}

	public function downvote($questionid) {

		$currentViewStatus = $this->db->select('viewstatus')
			->from('Questions')
			->where('questionid', $questionid)
			->get()
			->row()
			->viewstatus;

		$newViewStatus = $currentViewStatus - 1;

		$updateViewStatus = $this->db->where('questionid', $questionid)
			->update('Questions', array('viewstatus' => $newViewStatus));

		return $updateViewStatus;

	}

	public function getBookmark($questionid, $userid) {

		$bookmark = $this->db->get_where("BookmarkQue", array('questionid' => $questionid, 'userid' => $userid));
		
		if($bookmark->num_rows() > 0) {
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	public function removeBookmark($questionid, $userid) {
		$bookmark = $this->db->delete("BookmarkQue", array('questionid' => $questionid, 'userid' => $userid));
		return $bookmark;
	}

	public function addBookmark($questionid, $userid){
		// Check if the combination of questionid and userid already exists in the database
		$this->db->where('questionid', $questionid);
		$this->db->where('userid', $userid);
		$existingBookmark = $this->db->get('BookmarkQue')->row();

		// If the combination already exists, return false to indicate that the bookmark was not added
		if($existingBookmark) {
			return false;
		}

		// If the combination does not exist, add the new bookmark to the database
		$bookmarkData = array(
			'questionid' => $questionid,
			'userid' => $userid
		);
		$bookmark = $this->db->insert('BookmarkQue', $bookmarkData);

		// Return true to indicate that the bookmark was successfully added
		return $bookmark;
	}

	public function getBookmarkQuestions($userid) {

		$this->db->select('Questions.*');
		$this->db->from('Questions');
		$this->db->join('BookmarkQue', 'Questions.questionid = BookmarkQue.questionid');
		$this->db->where('BookmarkQue.userid', $userid);
		$question = $this->db->get();

		if($question->num_rows() > 0) {
			$question_array = $question->result();
			foreach ($question_array as $question) {
				$question_id = $question->questionid;
				$tag_query = $this->db->select('tags')
					->from('Tags')
					->where('questionid', $question_id)
					->get();
				$tags = $tag_query->result();
				$question->tags = array_column($tags, 'tags');
			}
			return $question_array;
		}
		else {
			return new stdClass();
		}
	}
}