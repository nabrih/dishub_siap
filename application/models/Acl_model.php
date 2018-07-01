<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Access Controll List
* @author nabrih
*/
class Acl_model extends CI_Model
{
	public $tables = array();


	
	function __construct()
	{
		$this->load->database();

		$this->tables['menu'] = "list_menu";
		$this->tables['access'] = "access";
		$this->tables['users'] = "users";
	}

	/**
	 * groups
	 *
	 * @return static
	 * @author nabrih
	 */
	public function menus()
	{
		// $response = $this->db->get($this->tables['menu']);

		$this->db->select('l.*, par.menu_desc parent_name');
		
		$this->db->from($this->tables['menu'].' l');
		$this->db->join('list_menu par',' par.menu_id=l.parent_id', 'left');

		$response = $this->db->get();

		return $response;
	}

	/**
	 * groups
	 *
	 * @return static
	 * @author nabrih
	 */
	public function parent_menus()
	{
		$this->db->select('*');
		
		$this->db->from($this->tables['menu']);
		$this->db->where('ashead',1);

		$response = $this->db->get();

		$response2 = array(0 => "None");
		foreach ($response->result_array() as $row) {
		 	$response2[$row['menu_id']] = $row['menu_desc'];
		 }

		return $response2;
	}

	/**
	* cek user_id, group, access
	*/
	public function checkMenu($name)
	{
		$user_id = $this->session->userdata('user_id');// initial id user

		$this->db->select('a.access_id');
		
		$this->db->from($this->tables['users'].' u');
		$this->db->join('users_groups ug', 'ug.user_id=u.id');
		$this->db->join('access a',' a.group_id=ug.group_id');
		$this->db->join('list_menu l',' l.menu_id=a.menu_id');
		$this->db->join('groups g',' g.id=ug.group_id');
		$this->db->where(array('l.menu_name' => $name, 'u.id' => $user_id));

		return $this->db->count_all_results();
	}

	/**
	* list allowed menu
	*/
	public function get_allowed_menu($parent_id=0)
	{
		$user_id = $this->session->userdata('user_id');// initial id user

		$this->db->select('l.menu_id, l.ashead, l.menu_name, l.url, l.menu_desc, l.icon_name');
		
		$this->db->from($this->tables['users'].' u');
		$this->db->join('users_groups ug', 'ug.user_id=u.id');
		$this->db->join('access a',' a.group_id=ug.group_id');
		$this->db->join('list_menu l',' l.menu_id=a.menu_id');
		$this->db->join('groups g',' g.id=ug.group_id');
		// $this->db->join('list_menu par',' par.menu_id=l.parent_id', 'left');
		// if ($parent_id!=0) {
		// 	$this->db->where(array('l.parent_id' => $parent_id));
		// }else
			$this->db->where(array('l.parent_id' => $parent_id));
		
		$this->db->where(array('u.id' => $user_id, 'l.status' => '1'));
		$this->db->order_by('menu_desc', 'asc');

		// echo $this->db->get_compiled_select();		

		$result = $this->db->get();
		return $result;
		// return false;

	}

	/**
	*Create menu
	*/
	public function create_menu($data_menu)
	{
		$identity = $this->session->userdata('identity');
		
		$data = array(
				'menu_name'		=>	$data_menu['menu_name'],
				'menu_desc'		=>	$data_menu['menu_desc'],
				'url'			=>	$data_menu['url'],
				'icon_name'		=>	$data_menu['icon_name'],
				'status'		=>	$data_menu['status'],
				'created_by'	=>	$identity,
				'created_time'	=>	date('Y-m-d H:i:s'));

		$this->db->insert($this->tables['menu'], $data);


		$menu_id = $this->db->insert_id();

		return $menu_id;
	}

	public function deleteMenu($id)
	{
		$this->db->trans_begin();
		
		$this->db->delete($this->tables['menu'], array('menu_id' => $id));
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}

	public function menu($id)
	{
		$this->db->select('*');
		
		$this->db->from($this->tables['menu']);
		$this->db->where('menu_id', $id);

		$row = $this->db->get()->row();

		return $row;
	}

	// public function edit_menu($menu_id, $menu_name)
	// {
	// 	$this->db->trans_begin();

	// 	$identity = $this->session->userdata('identity');

	// 	$this->db->set(
	// 		array('menu_name'=>$menu_name,
	// 			'modified_by'=>$identity,
	// 			'modified_time'=>date('Y-m-d H:i:s') ));
	// 	$this->db->where('menu_id', $menu_id);
	// 	$result = $this->db->update($this->tables['menu']);

	// 	if ($this->db->trans_status() === FALSE) {
	// 		$this->db->trans_rollback();
	// 		return false;
	// 	}
		
	// 	$this->db->trans_commit();
	// 	return true;
	// }

	public function edit_menu($data_menu)
	{
		$this->db->trans_begin();

		$identity = $this->session->userdata('identity');

		$this->db->set(array(
				'menu_name'		=>	$data_menu['menu_name'],
				'menu_desc'		=>	$data_menu['menu_desc'],
				'url'			=>	$data_menu['url'],
				'icon_name'		=>	$data_menu['icon_name'],
				'status'		=>	$data_menu['status'],
				'ashead'		=>	$data_menu['ashead'],
				'parent_id'		=>	$data_menu['parent_id'],
				'modified_by'	=>	$identity,
				'modified_time'	=>	date('Y-m-d H:i:s') ));

		$this->db->where('menu_id', $data_menu['id']);
		$result = $this->db->update($this->tables['menu']);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		}
		
		$this->db->trans_commit();
		return true;
	}



	public function group_menus($group_id)
	{
		$this->db->select('*');
		$this->db->from($this->tables['access']);
		$this->db->where(array('group_id'=> $group_id ));
		$result = $this->db->get();
		return $result;
	}

	public function update_group_menu($group_id, $menus)
	{
		$updated = false;
		$identity = $this->session->userdata('identity');

		// delete proses
		$this->db->delete($this->tables['access'], array('group_id'=>$group_id)); 

		//insert proses
		if (isset($menus)) {
			$menu_ids = array();
			foreach($menus as $menu_id){
				array_push($menu_ids, array('group_id'=>$group_id, 
					'menu_id'=>$menu_id, 
					'created_by'=>$identity,
					'created_time'=>date('Y-m-d H:i:s')
				));
			}

			$this->db->insert_batch($this->tables['access'], $menu_ids);
		}

		$updated=true;

		
		return $updated;
	}

}