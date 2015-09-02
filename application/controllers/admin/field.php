<?php
class Field extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Field_model');
    }

    public function index()
    {
        $source_type = $this->input->get('source_type');
        $source_id = $this->input->get('source_id');

        $source_type_name = $this->Field_model->get_source_type_name($source_type);
        $list = array(
            array($source_type_name, 'admin/'.$source_type),
            //array('ExtraFields')
                        array('套餐详情')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $data['source_type'] = $source_type;
        $data['source_id'] = $source_id;
        $data['list'] = $this->Field_model->get_list($source_type, $source_id);
        $this->load->view('admin/field/index.html', $data);
    }

    private function set_form_validation()
    {
        $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('count', 'Count', 'is_natural|required');
    }

    public function create()
    {
        $this->load->library('form_validation');
        $data = array();
        if($this->input->post()){
            $this->set_form_validation();

            $insert = $this->input->post();
            $source_type = $insert['source_type'];
            $source_id = $insert['source_id'];

            if($this->form_validation->run()){
                if($this->Field_model->create($insert)){
                    $this->session->set_flashdata('result_code', 1);
                    $this->session->set_flashdata('result_msg', 'Add Success');
                    redirect('admin/field?source_type='.$source_type.'&source_id='.$source_id);
                }
            }
        }else{
            $source_type = $this->input->get('source_type');
            $source_id = $this->input->get('source_id');
        }

        $source_type_name = $this->Field_model->get_source_type_name($source_type);
        $list = array(
            array($source_type_name, 'admin/'.$source_type),
            array('套餐详请', 'admin/field?source_type='.$source_type.'&source_id='.$source_id),
            array('添加详情')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $data['source_type'] = $source_type;
        $data['source_id'] = $source_id;
        $this->load->view('admin/field/create.html', $data);
    }

    public function update()
    {
        $this->load->library('form_validation');
        if($this->input->post()){
            $this->set_form_validation();
            $update = $this->input->post();
            $id = $update['id'];
            if($this->form_validation->run()){
                if($this->Field_model->update($update)){
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

        $info = $this->Field_model->get_one($id);
        $source_type_name = $this->Field_model->get_source_type_name($info['source_type']);
        $list = array(
            array($source_type_name, 'admin/'.$info['source_type']),
            array('套餐详情', 'admin/field?source_type='.$info['source_type'].'&source_id='.$info['source_id']),
            array('更新详情')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $data['info'] = $info;
        $this->load->view('admin/field/update.html', $data);
    }

    public function delete()
    {
        $id = $this->uri->segment(4);
        if($id){
            $info = $this->Field_model->get_field_by_id($id);
            if($this->Field_model->delete($id)){
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '删除成功');
                redirect('admin/field?source_type='.$info['source_type'].'&source_id='.$info['source_id']);
            }else{
                $this->session->set_flashdata('result_msg', '删除失败');
                redirect('admin/field/update/'.$id);
            }
        }
        redirect('admin/field?source_type='.$info['source_type'].'&source_id='.$info['source_id']);
    }
}