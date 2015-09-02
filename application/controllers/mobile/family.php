<?php
class Family extends MY_Controller
{

    public function __construct()
    {
        parent::__construct('family');
        $this->member_id = $this->session->userdata['member_id'];
        $this->load->model('Parent_model');
    }

    public function index()
    {
        $data['list'] = $this->Parent_model->get_list_by_member($this->member_id);
        $this->load->view('mobile/family/index.html', $data);
    }

    public function health_list()
    {

        if($this->session->userdata['wx'] == 1){
            $data['list'] = $this->Parent_model->get_list_by_member($this->member_id);
        }else{
            $data['list'] = $this->Parent_model->get_list_by_nurse($this->member_id);
        }

        $this->load->view('mobile/family/health_list.html', $data);
    }

    public function health_info()
    {
        $this->load->model(array('Order_item_model','Field_value_model'));
        $family_id = $this->uri->segment(4);
        $search = array(
            'family_id' => $family_id,
            'status' => 6
        );
        $list = $this->Order_item_model->get_list(0,50,$search);
        if($list){
            foreach($list as $key=>$value){
                $entry_list = $this->Field_value_model->get_list('service', 1,$value['id'], 1);
                if($entry_list){
                    $list[$key]['entry_list'] = $entry_list;
                }else{
                    $list[$key]['entry_list'] = array();
                }
            }
        }
        $data['list'] = $list;
        $data['id'] =  $family_id;
        $this->load->view('mobile/family/health_info.html', $data);
    }

    private function set_form_validation()
    {
        $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[5]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|exact_length[11]');
        //$this->form_validation->set_rules('idcard', 'IDcard', 'trim|required');
        //$this->form_validation->set_rules('birthday', 'Birthday', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
    }

    public function create()
    {
        $this->load->library('form_validation');
        if($this->input->post()){
            $this->set_form_validation();
            if($this->form_validation->run()){
                $insert = $this->input->post();
                $insert['member_id'] = $this->member_id;
                if($this->Parent_model->create($insert)){
                    $this->session->set_flashdata('result_code', 1);
                    $this->session->set_flashdata('result_msg', '添加成功');
                    redirect('mobile/family?wx=1');
                }
            }
        }
        $this->load->view('mobile/family/create.html');
    }

    public function create_once()
    {
        $this->load->library('form_validation');
        if($this->input->post()){
            $this->set_form_validation();
            if($this->form_validation->run()){
                $insert = $this->input->post();
                $insert['member_id'] = $this->member_id;
                if($this->Parent_model->create($insert)){
                    redirect('mobile/service?wx=1');
                }
            }
        }
        $this->load->view('mobile/family/create_once.html');
    }

    public function update()
    {
        $this->load->library('form_validation');
        if($this->input->post()){
            $this->set_form_validation();
            $update = $this->input->post();
            if(!$update['city']){
                unset($update['city']);
            }
            $id = $update['id'];
            if($this->form_validation->run()){
                if($this->Parent_model->update($update, $this->member_id)){
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '更新成功');
                redirect('mobile/family?wx=1');
                }else{
                    $data['result_code'] = 0;
                    $data['result_msg'] = '更新失败';
                }
            }
        }else{
            $id = $this->uri->segment(4);
        }
        $data['info'] = $this->Parent_model->get_one($id, $this->member_id);
        $this->load->view('mobile/family/update.html', $data);
    }

    public function delete()
    {
        $id = $this->uri->segment(4);
        if($id){
            if($this->Parent_model->delete($id, $this->member_id)){
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '删除成功');
                redirect('mobile/family?wx=1');
            }else{
                $this->session->set_flashdata('result_msg', '删除失败');
                redirect('mobile/family/update/'.$id.'?wx=1');
            }
        }
        redirect('mobile/family?wx=1');
    }

}
