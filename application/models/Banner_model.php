<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_model extends CI_Model
{
	private $table = 'banner';

	public function allBanner()
	{
		$query = $this->db->select('*')
		                  ->order_by('banner_id', 'desc')
		                  ->get($this->table);
		return $query;
	}

	public function get_active_banner()
	{
		$query = $this->db->select('*')
						->where('status', 1)
		                ->order_by('banner_id', 'desc')
		                ->get($this->table);
		return $query;
	}

	public function getOne($banner_id)
	{
		$query = $this->db->select('*')
				->where('banner_id', $banner_id)
				->limit(1)
				->get($this->table);
		$row = $query->row_array();

		if (isset($row))
		{
			return $row;
		}else
			return false;
	}

	public function save($data)
	{
		$this->db->trans_begin();

		$toinsert = array(
	        'image_name' =>  $data['image_name'],
	        'status' =>  $data['status'],
	        'note' =>  $data['note'],
	        'created_by' => $data['created_by'],
	        'created_time'	=> date('Y-m-d H:i:s')
		);

		$this->db->insert($this->table, $toinsert);

		if ($this->db->trans_status() === FALSE)
		{
		    return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
		
	}

	public function delete($banner_id)
	{
		$this->db->where('banner_id', $banner_id);
		$exec = $this->db->delete($this->table);
		if ($exec) {
			return true;
		}else
			return false;
	}

	public function update($data)
	{
		$data['modified_time'] = date('Y-m-d H:i:s');

		$this->db->where('banner_id', $data['banner_id']);

		$query = $this->db->update($this->table, $data); 

		if ($query) {
			return true;
		}else
			return FALSE;
	}
}