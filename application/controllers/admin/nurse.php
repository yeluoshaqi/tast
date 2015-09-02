<?php
class Nurse extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Member_model');
    }

    public function index()
    {
        $list = array(
            array('众包管理')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        /** search **/
        if($this->input->post()){
            $search = $this->input->post();
            $search['wx_id'] = 2;
            $this->session->set_userdata('nurse_search', $search);
        }else{
            if(isset($this->session->userdata['nurse_search'])){
                $search = $this->session->userdata['nurse_search'];
            }else{
                $search = array('name'=>'','mobile'=>'','wx_id'=>2);
            }
        }

        /** sort **/
        if($this->input->get()){
            $sort = $this->input->get();
            $this->session->set_userdata('nurse_sort', $sort);
        }else{
            if(isset($this->session->userdata['nurse_sort'])){
                $sort = $this->session->userdata['nurse_sort'];
            }else{
                $sort = array('sort'=>'reg_date', 'type'=>'desc');
            }
        }

        $data['sort'] = $this->Member_model->get_sort($sort);
        $data['search'] = $search;

        $this->load->library('pagination');
        $config['base_url'] = site_url().'/admin/nurse';
        $config['per_page'] = 10;
        $page = $this->uri->segment(3,1);
        $offset = ($page-1)*$config['per_page'];
        $config['total_rows'] = $this->Member_model->get_count($search);
        $this->pagination->initialize($config);

        $data['list'] = $this->Member_model->get_list($offset, $config['per_page'], $search, $sort);
        $this->load->view('admin/nurse/index.html', $data);
    }


    public function view()
    {
        $list = array(
            array('众包管理', 'admin/nurse'),
            array('众包信息')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $year = $this->uri->segment(5, idate('Y'));
        $month = $this->uri->segment(6, date('m'));
        $prefs = array(
            'show_next_prev' => TRUE,
            'next_prev_url' => site_url().'/admin/nurse/view/'.$this->uri->segment(4)
        );
        $prefs['template'] = '
   {table_open}<table border="0" cellpadding="0" cellspacing="0" id="nurse_date">{/table_open}
   {heading_row_start}<tr>{/heading_row_start}
   {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
   {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
   {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
   {heading_row_end}</tr>{/heading_row_end}
   {week_row_start}<tr>{/week_row_start}
   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
   {week_row_end}</tr>{/week_row_end}
   {cal_row_start}<tr>{/cal_row_start}
   {cal_cell_start}<td onclick="get_time(this)" class="nurse_date_item">{/cal_cell_start}
   {cal_cell_no_content}{day}{/cal_cell_no_content}
   {cal_cell_no_content_today}{day}{/cal_cell_no_content_today}
   {cal_cell_blank}&nbsp;{/cal_cell_blank}
   {cal_cell_end}</td>{/cal_cell_end}
   {cal_row_end}</tr>{/cal_row_end}
   {table_close}</table>{/table_close}
';

        $this->load->library('calendar', $prefs);
        $data['calendar'] = $this->calendar->generate($year, $month);
        $data['year'] = $year;
        $data['month'] = $month;

        $id = $this->uri->segment(4);
        $data['info'] = $this->Member_model->get_one($id);
        $data['status_list'] = $this->Member_model->get_status(2);
        $this->load->view('admin/nurse/view.html', $data);
    }

    public function update()
    {
        $post = $this->input->post();
        $id = $post['id'];
        if(!$post['city']){
            unset($post['city']);
        }
        if($this->Member_model->update($post,$id)){
            $this->session->set_flashdata('result_code', 1);
            $this->session->set_flashdata('result_msg', '更新成功');
            redirect('admin/nurse/view/'.$id);
        }else{
            $this->session->set_flashdata('result_code', 0);
            $this->session->set_flashdata('result_msg', '更新失败');
            redirect('admin/nurse/view/'.$id);
        }
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
                    redirect('admin/nurse');
                }else{
                    $this->session->set_flashdata('result_msg', '删除失败');
                    redirect('admin/nurse/view/'.$id);
                }
            }
            redirect('admin/nurse');
        }
    }
}
