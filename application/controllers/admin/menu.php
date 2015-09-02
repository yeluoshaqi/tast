<?php
class Menu extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Menu_model');
	}

	public function index()
	{
		$list = array(
			array('菜单管理')
		);
		$data['breadcrumbs'] = create_breadcrumbs($list);
		$data['list'] = $this->Menu_model->get_menu();
		$this->load->view('admin/menu/index.html', $data);
	}

    public function upload()
    {
        $wx_id = $this->uri->segment(4, 1);

        $this->load->model(array('Menu_model', 'Api_model', 'Wx_model'));
        $menu = $this->Menu_model->get_wx_menu($wx_id);
        foreach($menu as $key=>$value){
            $menu[$key]['sub_button'] = $this->Menu_model->get_wx_menu($wx_id, $value['id']);
        }
        $menu = urldecode(json_encode(array('button' => $menu)));

        $wx = $this->Wx_model->get_wx_by_id($wx_id);
        $this->Api_model->set_parameters($wx['appid'], $wx['appsecret']);
        if($this->Api_model->sync_menu($menu)){
            $this->session->set_flashdata('result_code',1);
            $this->session->set_flashdata('result_msg','上传成功');
            redirect('admin/menu');
        }else{
            $this->session->set_flashdata('result_code',1);
            $this->session->set_flashdata('result_msg','上传失败');
            redirect('admin/menu');
        }
    }

	private function set_form_validation()
	{
		$this->form_validation->set_error_delimiters('<small class="error">','</small>');
		//$this->form_validation->set_rules('wx_id','Wx_id','trim|required|max_length[4]');
		$this->form_validation->set_rules('top_id','Top_id','trim');
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('type','Type','trim');
		$this->form_validation->set_rules('value','Value','trim');
		$this->form_validation->set_rules('sort','Sort','trim');
	}

	public function create()
	{
		$this->load->library('form_validation');
		//$data = array();
		//print_r($this->input->post()) ;
		if($this->input->post())
		{
			//print_r($this->input->post()) ;
			//exit;
			$this->set_form_validation();
			if($this->form_validation->run())
			{
				$insert = $this->input->post();
				if($this->Menu_model->create_menu($insert))
				{
					$this->session->set_flashdata('result_code',1);
					$this->session->set_flashdata('result_msg','添加成功');
					redirect('admin/menu');
				}
			}
		}
		$list = array(
			array('菜单管理','admin/menu'),
			array('添加菜单')
		);
		$data['breadcrumbs'] = create_breadcrumbs($list);
		$data['parent_menu'] = $this->Menu_model->get_parent_menu();
		$this->load->view('admin/menu/create.html',$data);
	}
	
	public function update()
	{
		$this->load->library('form_validation');

		if($this->input->post()){
			$this->set_form_validation();
			$update = $this->input->post();
			$id = $update['id'];
			if($this->form_validation->run()){
				if($this->Menu_model->update_menu($update)){
					$data['result_code'] = 1;
					$data['result_msg'] = '更新成功';
				}else{
					$data['result_code'] = 0;
					$data['result_msg'] = '更新失败';
				}
			}
		}else{
			$id = $this->uri->segment(4);
		}
		$list = array(
			array('菜单管理','admin/menu'),
			array('编辑菜单')
		);

		$data['breadcrumbs'] = create_breadcrumbs($list);
		$data['info'] = $this->Menu_model->get_menu_by_id($id);
		$data['parent_menu'] = $this->Menu_model->get_parent_menu();

		$this->load->view('admin/menu/update.html',$data);
	}
	
	public function delete()
	{
		$id = $this->uri->segment(4);
		if($id){
			if($this->Menu_model->delete_menu_by_id($id)){
				$this->session->set_flashdata('result_code',1);
				$this->session->set_flashdata('result_msg','删除成功');
			}else{
				$this->session->set_flashdata('result_msg','删除失败');
				redirect('admin/menu/update/'.$id);
			}
		}
		redirect('admin/menu');
	}
}