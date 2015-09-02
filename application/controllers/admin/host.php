<?php
class Host extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Host_model');
		$this->load->model('News_model');
		$this->load->model('Text_model');
		$this->load->helper(array('form', 'url'));//为上传图片设置
	}

    public function keyword_check($keyword, $type='', $id=0)
    {
        //print_r($type);exit;
        if($this->Host_model->keyword_check($keyword,$type,$id)){
            $this->form_validation->set_message('keyword_check', '关键字不可用');
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	public function index()
	{
		$list = array(
			array('回复管理')
		);
		$data['breadcrumbs'] = create_breadcrumbs($list);
		$data['list'] = $this->Host_model->get_host();

		
		/**search**/
		
		if($this->input->post()){
			//print_r($this->input->post());
			$search = $this->input->post();
			$this->session->set_userdata('host_search',$search);
		}else{
			if(isset($this->session->userdata['host_search'])){
				$search = $this->session->userdata['host_search'];
			}else{
				$search = array('keyword'=>'');
			}
		}
		$data['search'] = $search;
		
        /** sort **/
        if($this->input->get()){
            $sort = $this->input->get();
            $this->session->set_userdata('host_sort', $sort);
        }else{
            if(isset($this->session->userdata['host_sort'])){
                $sort = $this->session->userdata['host_sort'];
            }else{
                $sort = array('sort'=>'motify_date', 'type'=>'desc');
            }
        }
		
		
		//$data['sort'] = $this->host_model->get_sort($sort);
		//$data['search'] = $search;
	
			
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/admin/host';
		$config['per_page'] = 10;
		$page = $this->uri->segment(3,1);
		$offset = ($page-1)*$config['per_page'];
		$config['total_rows'] = $this->Host_model->get_count($search);
		$this->pagination->initialize($config);
		
		$data['list'] = $this->Host_model->get_list($offset, $config['per_page'], $search ,$sort);
//		$this->load->view('admin/host/index.html',$data); 

		$this->load->view('admin/host/index.html',$data);
	}


    private function set_form_validation()
    {
        $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('wx_id', 'Wx_id', 'trim|required');
		$this->form_validation->set_rules('keyword', 'Keyword', 'trim|required');
    }
/*文本回复*/	
    private function set_text_form_validation()
    {
        $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
        $this->form_validation->set_rules('keyword', 'Keyword', 'trim|required');
        $this->form_validation->set_rules('wx_id', 'Wx_id', 'trim|required');
		$this->form_validation->set_rules('content', 'content', 'trim|required');
    }
	
	public function textcreate()
	{
		//secho 'host-textcreate';
		//print_r($this->input->post());exit;
		$this->load->library('form_validation');
		$data = array();
		if($this->input->post()){
			//print_r($this->input->post());exit;
            $this->set_text_form_validation();
            if($this->form_validation->run()){
				//echo 1;
				//exit;
                $insert = $this->input->post();
                //if($this->Host_model->create_host($insert)){
				if($this->Text_model->textcreate($insert)){
                    $this->session->set_flashdata('result_code', 1);
                    $this->session->set_flashdata('result_msg', '添加成功');
                    redirect('admin/host');
                }
            }
        }
        $list = array(
            array('回复管理', 'admin/host'),
            array('添加文本回复')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $this->load->view('admin/host/textcreate.html', $data);
	}

	
	    public function textupdate()
    {
        $this->load->library('form_validation');
		
		
		//print_r($this->input->post());
        if($this->input->post()){
            $this->set_text_form_validation();;
            $update = $this->input->post();
            $id = $update['id'];
            if($this->form_validation->run()){
                if($this->Text_model->textupdate($update)){
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
            array('回复管理', 'admin/host'),
			array('编辑文本回复')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $data['info'] = $this->Text_model->get_text_by_id($id);
		
        $this->load->view('admin/host/textupdate.html', $data);
    }

    public function textdelete()
    {
        $id = $this->uri->segment(4);
        if($id){
            if($this->Text_model->textdelete($id)){
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '删除成功');
                redirect('admin/host');
            }else{
                $this->session->set_flashdata('result_msg', '删除失败');
                redirect('admin/host/textupdate/'.$id);
            }
        }
        redirect('admin/host');
    }
/*图文回复*/
	    private function set_news_form_validation()
    {
        $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
        $this->form_validation->set_rules('keyword', 'Keyword', 'trim|required');
        $this->form_validation->set_rules('wx_id', 'Wx_id', 'trim|required');
		$this->form_validation->set_rules('front_id', 'Front_id', 'trim');
        $this->form_validation->set_rules('title', 'Title', 'trim');
        $this->form_validation->set_rules('desc', 'Desc', 'trim');
		$this->form_validation->set_rules('content', 'Content', 'trim');
        $this->form_validation->set_rules('link', 'Link', 'trim');
    }
	
//	public function newscreate()
//	{
//		$this->load->library('form_validation');
//		$data = array();
//		if($this->input->post()){
//            $this->set_news_form_validation();
//            if($this->form_validation->run()){
//                $insert = $this->input->post();
//				if($this->News_model->newscreate($insert)){
//                    $this->session->set_flashdata('result_code', 1);
//                    $this->session->set_flashdata('result_msg', '添加成功');
//                    redirect('admin/host');
//                }
//            }
//        }
//        $list = array(
//            array('回复管理', 'admin/host')
//           // array('添加套餐')
//        );
//        $data['breadcrumbs'] = create_breadcrumbs($list);
//        $this->load->view('admin/host/newscreate.html', $data);
//	}

	public function newscreate()
	{
		$this->load->library('form_validation');
		$this->load->library('upload');
		if($this->input->post()){
            $this->set_news_form_validation();
            if($this->form_validation->run()){
                $insert = $this->input->post();
				//上传图片
				if($this->upload->do_upload('userfile')){
					$upload = $this->upload->data();
					$insert['userfile'] = $upload['file_name'];
				}
				if($this->News_model->newscreate($insert)){
                    $this->session->set_flashdata('result_code', 1);
                    $this->session->set_flashdata('result_msg', '添加成功');
                    redirect('admin/host');
                }
            }
        }
        $list = array(
            array('回复管理', 'admin/host'),
            array('添加图文回复')
        );
   
   
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $this->load->view('admin/host/newscreate.html', $data);

	}

	    public function newsupdate()
    {
		
        $this->load->library('form_validation');
		$this->load->library('upload');
//		if(!$this->input->post()){
//			echo 2;
//            exit;
//        }
		//print_r($this->input->post());
        if($this->input->post()){
            $this->set_news_form_validation();;
            $update = $this->input->post();
			//上传图片
			if($this->upload->do_upload('userfile')){
				$upload = $this->upload->data();
				$update['userfile'] = $upload['file_name'];
			}
			
            $id = $update['id'];
            if($this->form_validation->run()){
                if($this->News_model->newsupdate($update)){
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
            array('回复管理', 'admin/host'),
			array('编辑图文回复')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $data['info'] = $this->News_model->get_news_by_id($id);
		
        $this->load->view('admin/host/newsupdate.html', $data);
    }

//	    public function newsupdate()
//    {
//        $this->load->library('form_validation');
//        if($this->input->post()){
//            $this->set_news_form_validation();;
//            $update = $this->input->post();
//            $id = $update['id'];
//            if($this->form_validation->run()){
//                if($this->News_model->newsupdate($update)){
//                    $data['result_code'] = 1;
//                    $data['result_msg'] = '更新成功';
//                }else{
//                    $data['result_code'] = 0;
//                    $data['result_msg'] = '更新失败';
//                }
//            }
//        }else{
//            $id = $this->uri->segment(4);
//			
//        }
//        $list = array(
//            array('回复管理', 'admin/host'),
//			array('')
//        );
//        $data['breadcrumbs'] = create_breadcrumbs($list);
//        $data['info'] = $this->News_model->get_news_by_id($id);
//		
//        $this->load->view('admin/host/newsupdate.html', $data);
//    }

    public function newsdelete()
    {
        $id = $this->uri->segment(4);
        if($id){
            if($this->News_model->newsdelete($id)){
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '删除成功');
                redirect('admin/host');
            }else{
                $this->session->set_flashdata('result_msg', '删除失败');
                redirect('admin/host/newsupdate/'.$id);
            }
        }
        redirect('admin/host');
    }

}