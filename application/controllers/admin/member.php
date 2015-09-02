<?php
class Member extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Member_model');
    }

    public function index()
    {
        $list = array(
            array('用户管理')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        /** search **/
        if($this->input->post()){
            $search = $this->input->post();
            $this->session->set_userdata('member_search', $search);
        }else{
            if(isset($this->session->userdata['member_search'])){
                $search = $this->session->userdata['member_search'];
            }else{
                $search = array('name'=>'','mobile'=>'','wx_id'=>1);
            }
        }

        /** sort **/
        if($this->input->get()){
            $sort = $this->input->get();
            $this->session->set_userdata('member_sort', $sort);
        }else{
            if(isset($this->session->userdata['member_sort'])){
                $sort = $this->session->userdata['member_sort'];
            }else{
                $sort = array('sort'=>'reg_date', 'type'=>'desc');
            }
        }

        $data['sort'] = $this->Member_model->get_sort($sort);
        $data['search'] = $search;

        $this->load->library('pagination');
        $config['base_url'] = site_url().'/admin/member';
        $config['per_page'] = 10;
        $page = $this->uri->segment(3,1);
        $offset = ($page-1)*$config['per_page'];
        $config['total_rows'] = $this->Member_model->get_count($search);
        $this->pagination->initialize($config);

        $data['list'] = $this->Member_model->get_list($offset, $config['per_page'], $search, $sort);
        $this->load->view('admin/member/index.html', $data);
    }


    public function view()
    {
        $list = array(
            array('用户管理', 'admin/member'),
            array('用户信息')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $id = $this->uri->segment(4);
        $data['info'] = $this->Member_model->get_one($id);

        $this->load->view('admin/member/view.html', $data);
    }

    public function delete()
    {
        if($this->input->post()){
            $ids = $this->input->post('ids');
            echo $this->Member_model->delete($ids);
        }else{
            $id = $this->uri->segment(4);
            if($id){
                if($this->Member_model->delete($id)){
                    $this->session->set_flashdata('result_code', 1);
                    $this->session->set_flashdata('result_msg', '删除成功');
                    redirect('admin/member');
                }else{
                    $this->session->set_flashdata('result_msg', '删除失败');
                    redirect('admin/member/view/'.$id);
                }
            }
            redirect('admin/member');
        }
    }
}