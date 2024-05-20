<?php

class AnswerModel extends CI_Model {

	public function getAnswers($questionid) {

		$answer = $this->db->get_where("Answers", array('questionid' => $questionid));

		if($answer->num_rows() > 0) {
			return $answer->result();
		}
		else {
			return null;
		}
	}

	public function addAnswer($questionid, $userid, $answer, $answeraddreddate, $imageurl, $rate){

		$rate = floatval($rate);

		$pastRateResult = $this->db->select('rate')
							->from('Questions')
							->where('questionid', $questionid)
							->get();

		if ($pastRateResult->num_rows() > 0 && $rate > 0) {
			// Extract the rate from the result
			$pastRate = $pastRateResult->row()->rate;

			// Convert the past rate from string to double
			$pastRate = floatval($pastRate);

			if ($pastRate == 0) {
				$pastRate = $rate;
			}
			// Calculate the new rate
			$rate = ($rate + $pastRate) / 2;
		}

		// Update the rate in the Questions table
		$this->db->where('questionid', $questionid)
			->update('Questions', array('rate' => $rate));

		$answerData = array(
			'questionid' => $questionid,
			'userid' => $userid,
			'answer' => $answer,
			'answerimage' => $imageurl,
			'answeraddeddate' => $answeraddreddate
		);

		$insertAns = $this->db->insert('Answers', $answerData);

		return $insertAns;
	}

}
