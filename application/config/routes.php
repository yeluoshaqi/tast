<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/************************ PC ******************************/
$route['join'] = 'home/index/join';
$route['question'] = 'home/index/question';
$route['contact'] = 'home/index/contact';
$route['register'] = 'home/index/register';

/************************ static page **********************/
$route['mobile/page/(:any)'] = 'mobile/index/page';

/************************ api  *****************************/
$route['api'] = 'api/wx';
$route['api/check_date'] = 'api/check_date';
$route['api/update_date_time'] = 'api/update_date_time';
$route['api/member'] = 'api/member';
$route['api/pay/(:any)'] = 'api/pay';
$route['api/pay_notify'] = 'api/pay_notify';
$route['api/get_data/(:any)'] = 'api/get_data';
$route['api/get_city/(:any)'] = 'api/get_city';
$route['api/get_city_name/(:any)'] = 'api/get_city_name';
$route['api/get_comment_data_by_family/(:any)'] = 'api/get_comment_data_by_family';

/************************ mobile ***************************/
$route['mobile'] = 'mobile/index/index';
$route['mobile/service'] = 'mobile/index/service';
$route['mobile/pay'] = 'mobile/index/pay';
$route['mobile/success'] = 'mobile/index/success';
//$route['mobile/delete/(:any)'] = 'mobile/index/delete';

$route['mobile/member'] = 'mobile/member/index';
$route['mobile/member/base'] = 'mobile/member/base';
$route['mobile/member/date'] = 'mobile/member/date';
$route['mobile/member/date/(:any)'] = 'mobile/member/date';
$route['mobile/member/register'] = 'mobile/member/register';
$route['mobile/member/register_success'] = 'mobile/member/register_success';

$route['mobile/family'] = 'mobile/family/index';
$route['mobile/family/create'] = 'mobile/family/create';
$route['mobile/family/create_once'] = 'mobile/family/create_once';
$route['mobile/family/update'] = 'mobile/family/update';
$route['mobile/family/update/(:any)'] = 'mobile/family/update';
$route['mobile/family/delete/(:any)'] = 'mobile/family/delete';
$route['mobile/family/(:any)'] = 'mobile/family/index';

$route['mobile/health/health_list'] = 'mobile/family/health_list';
$route['mobile/health/health_info/(:any)'] = 'mobile/family/health_info';

$route['mobile/order'] = 'mobile/order/index';
$route['mobile/order/info/(:any)'] = 'mobile/order/info';
$route['mobile/order/cancel/(:any)'] = 'mobile/order/cancel';
$route['mobile/order/comment'] = 'mobile/order/comment';
$route['mobile/order/comment/(:any)'] = 'mobile/order/comment';
$route['mobile/order/entry'] = 'mobile/order/entry';
$route['mobile/order/entry/(:any)'] = 'mobile/order/entry';
$route['mobile/order/set_status/(:any)'] = 'mobile/order/set_status';

$route['mobile/message'] = 'mobile/index/message';
$route['mobile/message/message_success'] = 'mobile/index/message_success';

$route['mobile/me'] = 'mobile/me/index';

/************************ admin ****************************/

$route['admin/check_new'] = 'admin/order/check_new';

$route['admin'] = 'admin/index/index';
$route['admin/login'] = 'admin/auth/login';
$route['admin/logout'] = 'admin/index/logout';

/** 20150708 host**/
$route['admin/host'] = 'admin/host/index';
$route['admin/host/textcreate'] = 'admin/host/textcreate';
$route['admin/host/textupdate'] = 'admin/host/textupdate';
$route['admin/host/textupdate/(:any)'] = 'admin/host/textupdate';
$route['admin/host/textdelete/(:any)'] = 'admin/host/textdelete';
$route['admin/host/newscreate'] = 'admin/host/newscreate';
$route['admin/host/newsupdate'] = 'admin/host/newsupdate';
$route['admin/host/newsupdate/(:any)'] = 'admin/host/newsupdate';
$route['admin/host/newsdelete/(:any)'] = 'admin/host/newsdelete';

/** 20150623 wx_menu**/
$route['admin/menu'] = 'admin/menu/index';
$route['admin/menu/create'] = 'admin/menu/create';
$route['admin/menu/update'] = 'admin/menu/update';
$route['admin/menu/update/(:any)'] = 'admin/menu/update';
$route['admin/menu/upload/(:any)'] = 'admin/menu/upload';
$route['admin/menu/delete/(:any)'] = 'admin/menu/delete';

/** 20150618 wx**/
$route['admin/wx'] = 'admin/wx/index';
$route['admin/wx/create'] = 'admin/wx/create';
$route['admin/wx/update'] = 'admin/wx/update';
$route['admin/wx/update/(:any)'] = 'admin/wx/update';
$route['admin/wx/delete/(:any)'] = 'admin/wx/delete';

/** service **/
$route['admin/service'] = 'admin/service/index';
$route['admin/service/create'] = 'admin/service/create';
$route['admin/service/update'] = 'admin/service/update';
$route['admin/service/update/(:any)'] = 'admin/service/update';
$route['admin/service/delete/(:any)'] = 'admin/service/delete';

/** service log **/
$route['admin/service_log'] = 'admin/service_log/index';
$route['admin/service_log/view/(:any)'] = 'admin/service_log/view';

$route['admin/service_log/delete/(:any)'] = 'admin/service_log/delete';
$route['admin/service_log/(:any)'] = 'admin/service_log/index';

$route['admin/comment'] = 'admin/comment/index';
$route['admin/comment/view/(:any)'] = 'admin/comment/view';
$route['admin/comment/(:any)'] = 'admin/comment/index';

$route['admin/register'] = 'admin/register/index';
$route['admin/register/view/(:any)'] = 'admin/register/view';
$route['admin/register/delete/(:any)'] = 'admin/register/delete';
$route['admin/register/(:any)'] = 'admin/register/index';

$route['admin/message'] = 'admin/message/index';
$route['admin/message/view/(:any)'] = 'admin/message/view';
$route['admin/message/delete/(:any)'] = 'admin/message/delete';
$route['admin/message/(:any)'] = 'admin/message/index';

/** field **/
$route['admin/field'] = 'admin/field/index';
$route['admin/field/create'] = 'admin/field/create';
$route['admin/field/update'] = 'admin/field/update';
$route['admin/field/update/(:any)'] = 'admin/field/update';
$route['admin/field/delete/(:any)'] = 'admin/field/delete';

/** city **/
$route['admin/city'] = 'admin/city/index';
$route['admin/city/create'] = 'admin/city/create';
$route['admin/city/update'] = 'admin/city/update';
$route['admin/city/update/(:any)'] = 'admin/city/update';
$route['admin/city/upload/(:any)'] = 'admin/city/upload';
$route['admin/city/delete/(:any)'] = 'admin/city/delete';
$route['admin/city/(:any)'] = 'admin/city/index';
/** coupon **/
$route['admin/coupon'] = 'admin/coupon/index';
$route['admin/coupon/index/(:any)'] = 'admin/coupon/index';
$route['admin/coupon/create_icoupons'] = 'admin/coupon/create_icoupons';
$route['admin/coupon/update_icoupons'] = 'admin/coupon/update_icoupons';
$route['admin/coupon/update_icoupons/(:any)'] = 'admin/coupon/update_icoupons';
$route['admin/coupon/create_coupon'] = 'admin/coupon/create_coupon';
$route['admin/coupon/create_coupon/(:any)'] = 'admin/coupon/create_coupon';
$route['admin/coupon/show_coupon/(:any)'] = 'admin/coupon/show_coupon';

/** order **/
$route['admin/order'] = 'admin/order/index';
$route['admin/order/create'] = 'admin/order/create';
$route['admin/order/view/(:any)'] = 'admin/order/view';
$route['admin/order/update'] = 'admin/order/update';
$route['admin/order/update/(:any)'] = 'admin/order/update';
$route['admin/order/delete/(:any)'] = 'admin/order/delete';
$route['admin/order/(:any)'] = 'admin/order/index';

/** order_item **/
$route['admin/order_item'] = 'admin/order_item/index';
$route['admin/order_item/view/(:any)'] = 'admin/order_item/view';
$route['admin/order_item/update'] = 'admin/order_item/update';
$route['admin/order_item/update/(:any)'] = 'admin/order_item/update';
$route['admin/order_item/delete/(:any)'] = 'admin/order_item/delete';
$route['admin/order_item/(:any)'] = 'admin/order_item/index';

/** member **/
$route['admin/member'] = 'admin/member/index';
$route['admin/member/view/(:any)'] = 'admin/member/view';
$route['admin/member/delete'] = 'admin/member/delete';
$route['admin/member/delete/(:any)'] = 'admin/member/delete';
$route['admin/member/(:any)'] = 'admin/member/index';

/** member **/
$route['admin/nurse'] = 'admin/nurse/index';
$route['admin/nurse/view/(:any)'] = 'admin/nurse/view';
$route['admin/nurse/update'] = 'admin/nurse/update';
$route['admin/nurse/delete'] = 'admin/nurse/delete';
$route['admin/nurse/delete/(:any)'] = 'admin/nurse/delete';
$route['admin/nurse/(:any)'] = 'admin/nurse/index';

/** family **/
$route['admin/family'] = 'admin/family/index';
$route['admin/family/view/(:any)'] = 'admin/family/view';
$route['admin/family/delete/(:any)'] = 'admin/family/delete';
$route['admin/family/(:any)'] = 'admin/family/index';

$route['default_controller'] = 'home/index/index';
$route['(:any)'] = 'home/index/index';
$route['404_override'] = '';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
