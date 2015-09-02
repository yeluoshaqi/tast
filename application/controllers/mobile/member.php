<?php
class Member extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('member');
        $this->load->model('Member_model');
        $this->member_id = $this->session->userdata['member_id'];
    }

    public function index()
    {
        $data['info'] = $this->Member_model->get_one($this->member_id);
        $this->load->view('mobile/member/index.html', $data);
    }


    public function base()
    {
        if($this->input->post()){
            $update = $this->input->post();
            if(!$update['city']){
                unset($update['city']);
            }
            if($this->Member_model->update($update, $this->member_id)){
                $data['result_code'] = 1;
                $data['result_msg'] = '更新成功';
            }else{
                $data['result_code'] = 0;
                $data['result_msg'] = '更新失败';
            }
        }
        $data['info'] = $this->Member_model->get_one($this->member_id);
        $this->load->view('mobile/member/base.html', $data);
    }

    private function set_form_validation()
    {
        $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|exact_length[11]');
    }

    public function register()
    {
        $this->load->library('form_validation');
        if($this->input->post()){
            $this->set_form_validation();
            if($this->form_validation->run()){
                $insert = $this->input->post();
                $this->load->model('Register_model');
                if($this->Register_model->create($insert)){
                    redirect('mobile/member/register_success');
                }
            }
        }
        $this->load->view('mobile/member/register.html');
    }

    public function register_success()
    {
        $this->load->view('mobile/member/register_success.html');
    }

    public function date()
    {
        $data['nurse_id'] = $this->member_id;
        $year = $this->uri->segment(4, idate('Y'));
        $month = $this->uri->segment(5, date('m'));
        $prefs = array(
            'show_next_prev' => TRUE,
            'next_prev_url' => site_url().'/mobile/member/date',
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
        $this->load->view('mobile/member/date.html', $data);
    }

}