<?php

if (! defined( 'BASEPATH' ))
	exit( 'No direct script access allowed' );
class Fms_contract_model extends CI_Model {
	private $em;
	
	/* APPLICANT STATUS CODES */
	const ACCEPTED = 1;
	const REJECTED = - 1;
	const PENDING = 0;
	const PAY = 1;
	const EQUITY = 2;
	const MIXED =3;
	
	public function __construct() {
		$this->em = $this->doctrine->em;
		// $params = array('em' => $this->em);
		// $this->load->library('EntitySerializer',$params);
	}
	public function getEntityById($id) {
		$contract = $this->em->find( 'Entity\Contract', $id );
		return $contract;
	}
	public function createEntity($project, $signees, $type) {
		$contract = new Entity\Contract();
		$contract->setProject($project);
		$contract->setCreationTime(new DateTime());
		$contract->setStatus(self::PENDING);
		$contract->setType($type);

		$this->em->flush();
		return $contract;
	}

	public function deleteContract($contract) {
		$this->em->remove( $contrac );
		$this->em->flush();
	}
}

?>