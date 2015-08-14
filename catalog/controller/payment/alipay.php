<?php
/*
 本支付宝双接口，担保接口，直接到帐 Opencart支付插件由 www.opencartchina.com 开发，并免费提供给用户使用，转载请注明出处.
如有任何问题请联系 30171310@qq.com
*/

require_once("alipay.php");
require_once("alipay_function.php");
require_once("alipay_notify.php");
require_once("alipay_service.php");
class ControllerPaymentAlipay extends Controller {
	public function index() {
    	$data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_back'] = $this->language->get('button_back');

		$data['return'] = HTTPS_SERVER . 'index.php?route=checkout/success';

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		$data['custom'] = $encryption->encrypt($this->session->data['order_id']);

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		$this->load->model('checkout/order');

		$order_id = $this->session->data['order_id'];

		$order_info = $this->model_checkout_order->getOrder($order_id);

		$seller_email = $this->config->get('alipay_seller_email');
		$security_code = $this->config->get('alipay_security_code');
		$trade_type = $this->config->get('alipay_trade_type');
		$partner = $this->config->get('alipay_partner');
		$currency_code ='CNY';
		$item_name = $this->config->get('config_name');
        $full_name = $order_info['payment_fullname'];

		$total = $order_info['total'];

		$currency_value = $this->currency->getValue($currency_code);
		$amount = $total * $currency_value;
		$amount = number_format($amount,2,'.','');

		$_input_charset = "utf-8";
		$sign_type      = "MD5";
		$transport      = "http";
		$notify_url     = HTTP_SERVER . 'catalog/controller/payment/alipay_callback.php';
		$return_url		=HTTPS_SERVER . 'index.php?route=checkout/success';
		$show_url       = "";

		$parameter = array(
			"service"        => $trade_type,
			"partner"        => $partner,
			"return_url"     => $return_url,
			"notify_url"     => $notify_url,
			"_input_charset" => $_input_charset,
			"subject"        => $item_name.' Order:' . $order_id ,
			"body"           => 'Owner ' . $full_name,
			"out_trade_no"   => $order_id,
			"price"          => $amount,
			"payment_type"   => "1",
			"quantity"       => "1",
			"logistics_fee"      =>'0.00',
			"logistics_payment"  =>'BUYER_PAY',
			"logistics_type"     =>'EXPRESS',
			"show_url"       => $show_url,
			"seller_email"   => $seller_email
		);

		$alipay = new alipay_service($parameter,$security_code,$sign_type);
		$action=$alipay->build_url();

		$data['action'] = $action;
		//$this->id = 'payment';

        // Save payment url address for pay later.
        $this->model_checkout_order->setOrderPaymentUrl($order_id, $action);
		
// 		log_result("Alipay test 111111111111");
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/alipay.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/alipay.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/alipay.tpl', $data);
        }
	}

	public function callback() {
		//trade_create_by_buyer 双接口 ,create_direct_pay_by_user 直接到帐，create_partner_trade_by_buyer 担保接口
		$trade_type = $this->config->get('alipay_trade_type');

// 		log_result("Alipay :: exciting callback function.");
		$oder_success = FALSE;
		$this->load->library('encryption');

		$seller_email = $this->config->get('alipay_seller_email'); // 商家邮箱
		$partner = $this->config->get('alipay_partner'); //合作伙伴ID
		$security_code = $this->config->get('alipay_security_code'); //安全检验码

		$_input_charset = "utf-8";
		//$_input_charset = "GBK";
		$sign_type = "MD5";
		$transport = 'http';

		$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
		$verify_result = $alipay->notify_verify();

		// Order status for Opencart
		$order_status = array(
			"Canceled"        => 7,
			"Canceled_Reversal"   => 9,
			"Chargeback"     	=> 13,
			"Complete"     		=> 5,
			"Denied" 			=> 8,
			"Failed"        	=> 10 ,
			"Pending"           => 1,
			"Processing"  		 => 2,
			"Refunded"        	  => 11,
			"Reversed"  		 => 12,
			"Shipped"     	  => 3
		);

// 		log_result("Alipay :: trade_type ".$trade_type." :: verify_result  = ".$verify_result);
		if($verify_result) {
			$order_id   = $_POST['out_trade_no'];   //$_POST['out_trade_no'];
			$trade_status=$_POST['trade_status'];
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);
            //log_result("Alipay order_id :: ".$order_id);

			if ($order_info) {
				$order_status_id = $order_info["order_status_id"];
                //log_result("Alipay order_id :: ".$order_id." order_status_id = ".$order_status_id." , trade_status :: ".$trade_status);
                //log_result("Alipay order_id :: Complete status = ".$order_status['Complete']);
				// 确定订单没有重复支付
				if ($order_status_id != $order_status['Complete']) {
					$currency_code = 'CNY';
					$total = $order_info['total'];
					$currency_value = $this->currency->getValue($currency_code);
					$amount = $total * $currency_value;
					$total  =  $_POST['total_fee'];    //$_POST['total_fee'];
					// 确定支付和订单额度一致
                    //log_result("Alipay total :: ".$_POST['total_fee'].",amount :: ".$amount);
					if($total < $amount){
                        //log_result("Alipay order_id :: ".$order_id." total < amount, order_status_id = ".$order_status_id);
						$this->model_checkout_order->addOrderHistory($order_id, $order_status['Canceled']);
						//echo "success";
                        $this->response->redirect($this->url->link('checkout/failed'));
					}else{
						// 根据接口类型动态使用支付方法
						if($trade_type=='trade_create_by_buyer'){
							$this->func_trade_create_by_buyer($order_id,$order_status_id,$order_status,$trade_status);
							//echo "success";
                            $this->response->redirect($this->url->link('checkout/success/pay'));
                        }else if($trade_type=='create_direct_pay_by_user'){
							$this->func_create_direct_pay_by_user($order_id,$order_status_id,$order_status,$trade_status);
							//echo "success";
                            $this->response->redirect($this->url->link('checkout/success/pay'));
                        }else if($trade_type=='create_partner_trade_by_buyer'){
							$this->func_create_partner_trade_by_buyer($order_id,$order_status_id,$order_status,$trade_status);
							//echo "success";
                            $this->response->redirect($this->url->link('checkout/success/pay'));
                        }
					 }
					}else {
						//echo "fail";
                    $this->response->redirect($this->url->link('checkout/failed'));
                }
			}else{
                //log_result("Alipay No Order Found.");
				//echo "fail";
                $this->response->redirect($this->url->link('checkout/failed'));
            }
		} else {
            //echo "fail";
            $this->response->redirect($this->url->link('checkout/failed'));
        }
	}
		// 双接口
	private function func_trade_create_by_buyer($order_id,$order_status_id,$order_status,$trade_status){
			if($trade_status == 'WAIT_BUYER_PAY') {
// 				log_result("Alipay order_id :: ".$order_id." WAIT_BUYER_PAY, order_status_id = ".$order_status_id);
				if ($order_status['Pending']> $order_status_id){
					$this->model_checkout_order->addOrderHistory($order_id, $order_status['Pending']);
// 					log_result("Alipay order_id :: ".$order_id." Update Successfully.");
				}
			}
			else if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
// 				log_result("Alipay order_id :: ".$order_id." trade_status == WAIT_SELLER_SEND_GOODS, update order_status_id from ".$order_status_id." to ".$this->config->get('alipay_order_status_id'));
				if($this->config->get('alipay_order_status_id')> $order_status_id){
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('alipay_order_status_id'));
// 					log_result("Alipay order_id :: ".$order_id." Update Successfully.");
				}
			}
			else if($trade_status == 'WAIT_BUYER_CONFIRM_GOODS') {
// 				log_result("Alipay order_id :: ".$order_id." trade_status == WAIT_BUYER_CONFIRM_GOODS,update order_status_id from ".$order_status_id." to ".$order_status['Complete']);
				if ( $order_status['Shipped']> $order_status_id){
					$this->model_checkout_order->addOrderHistory($order_id, $order_status['Shipped']);
// 					log_result("Alipay order_id :: ".$order_id." Update Successfully.");
				}
			}
			else if($trade_status == 'TRADE_FINISHED' ||$trade_status == 'TRADE_SUCCESS') {
// 				log_result("Alipay order_id :: ".$order_id." trade_status == TRADE_FINISHED / TRADE_SUCCESS, update order_status_id from ".$order_status_id." to ".$order_status['Complete']);
				if ($order_status['Complete'] > $order_status_id){
					$this->model_checkout_order->addOrderHistory($order_id,$order_status['Complete']);
// 					log_result("Alipay order_id :: ".$order_id." Update Successfully.");
				}
			}
	}
	// 直接到帐
	private function func_create_direct_pay_by_user($order_id,$order_status_id,$order_status,$trade_status){
			if($trade_status == 'TRADE_FINISHED' ||$trade_status == 'TRADE_SUCCESS') {
// 				log_result("Alipay order_id :: ".$order_id." trade_status ==TRADE_FINISHED / TRADE_SUCCESS,  update order_status_id from ".$order_status_id." to ".$this->config->get('alipay_order_status_id'));
				if($this->config->get('alipay_order_status_id')> $order_status_id){
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('alipay_order_status_id'));
// 					log_result("Alipay order_id :: ".$order_id." update order_status_id to ".$this->config->get('alipay_order_status_id'));
				}
			}
	}
	// 双接口
	private function func_create_partner_trade_by_buyer($order_id,$order_status_id,$order_status,$trade_status){
			if($trade_status == 'WAIT_BUYER_PAY') {
// 				log_result("Alipay order_id :: ".$order_id."  trade_status ==  WAIT_BUYER_PAY,  update order_status_id from ".$order_status_id." to ".$order_status['Pending']);
				if ($order_status['Pending']> $order_status_id){
					$this->model_checkout_order->addOrderHistory($order_id, $order_status['Pending']);
// 					log_result("Alipay order_id :: ".$order_id." Update Successfully.");
				}
			}
			else if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
// 				log_result("Alipay order_id :: ".$order_id." trade_status == WAIT_SELLER_SEND_GOODS, update order_status_id from ".$order_status_id." to ".$this->config->get('alipay_order_status_id'));
				if($this->config->get('alipay_order_status_id') ){
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('alipay_order_status_id'));
// 					log_result("Alipay order_id :: ".$order_id." Update Successfully.");
				}
			}
			else if($trade_status == 'WAIT_BUYER_CONFIRM_GOODS') {
// 				log_result("Alipay order_id :: ".$order_id." trade_status == WAIT_BUYER_CONFIRM_GOODS, update order_status_id from ".$order_status_id." to ".$order_status['Complete']);
				if ( $order_status['Shipped']> $order_status_id){
					$this->model_checkout_order->addOrderHistory($order_id, $order_status['Shipped']);
// 					log_result("Alipay order_id :: ".$order_id." Update Successfully.");
				}
			}
			else if($trade_status == 'TRADE_FINISHED' ) {
// 				log_result("Alipay order_id :: ".$order_id." trade_status == TRADE_FINISHED ,update order_status_id from ".$order_status_id." to ".$order_status['Complete']);
				if ($order_status['Complete'] > $order_status_id){
					$this->model_checkout_order->addOrderHistory($order_id,$order_status['Complete']);
// 					log_result("Alipay order_id :: ".$order_id." Update Successfully.");
				}
			}
	}
}

?>