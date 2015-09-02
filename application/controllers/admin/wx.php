<?php
//20150618liao
class Wx extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Wx_model');
	}
	
	public function index()
	{
		$list = array(
			array('微信管理')
		);
		$data['breadcrumbs'] = create_breadcrumbs($list);
		$data['list'] = $this->Wx_model->get_wx();
		$this->load->view('admin/wx/index.html',$data);
	}
	
	private function set_form_validation()
	{
		$this->form_validation->set_error_delimiters('<small class="error">','</small>');
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('token','Token','trim|required');
		$this->form_validation->set_rules('appid','Appid','trim|required');
		$this->form_validation->set_rules('appsecret','Appsecret','trim|required');
	}
	public function create()
	{
	$this->load->library('form_validation');
	$data = array();
	//echo 'create()   ';
	if($this->input->post()){
		//print_r($this->input->post()) ;
		$this->set_form_validation();
		if($this->form_validation->run()){
			//echo 'run()   ';
			$insert = $this->input->post();
			//echo 'run()-post()   ';
			if($this->Wx_model->create_wx($insert)){
				//echo 'create_wx   ';
				$this->session->set_flashdata('result_code',1);
				$this->session->set_flashdata('result_msg','添加成功');
				redirect('admin/wx');
			}
		}
	}
	$list = array(
		array('微信管理','admin/wx'),
		array('添加微信')
	);
	$data['breadcrumbs'] = create_breadcrumbs($list);
	$this->load->view('admin/wx/create.html',$data);
	}
	
	public function update()
	{
		$this->load->library('form_validation');
		if($this->input->post()){
			$this->set_form_validation();
			$update = $this->input->post();
			$id = $update['id'];
			if($this->form_validation->run()){
				if($this->Wx_model->update_wx($update)){
					$data['result_code'] = 1;
					$data['result_msg'] = '更新成功';
				}else{
					$data['result_code'] = 0;
					$data['result_msg'] = '更新失败';
				}
				}
		}else{
			$id = $this->uri->segment(4); //地址栏中第4个参数
		}
		$list = array(
			array('微信管理','admin/wx'),
			array('编辑微信')
		);
		$data['breadcrumbs'] = create_breadcrumbs($list);
		$data['info'] = $this->Wx_model->get_wx_by_id($id);
		$this->load->view('admin/wx/update.html',$data); 
	}
	
	public function delete()
	{
		$id = $this->uri->segment(4);
		if($id){
			if($this->Wx_model->delete_wx_by_id($id)){
				$this->session->set_flashdata('result_code',1);
				$this->session->set_flashdata('result_msg','删除成功');
				redirect('admin/wx');
			}else{
				$this->session->set_flashdata('result_msg','删除失败');
				redirect('admin/wx/update/'.$id);
			}
		}
		redirect('admin/wx');
	}
}