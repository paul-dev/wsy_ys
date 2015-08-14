<?php
class ControllerAccountLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('account/customer');

		// Login override for admin users
		if (!empty($this->request->get['token'])) {
			$this->event->trigger('pre.customer.login');

			$this->customer->logout();
			$this->cart->clear();

			unset($this->session->data['wishlist']);
			unset($this->session->data['payment_address']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);

			$customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

			if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
				// Default Addresses
				$this->load->model('account/address');

				if ($this->config->get('config_tax_customer') == 'payment') {
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}

				if ($this->config->get('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}

				$this->event->trigger('post.customer.login');

				$this->response->redirect($this->url->link('account/account', '', 'SSL'));
			}
		}

		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$this->load->language('account/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			unset($this->session->data['guest']);

			// Default Shipping Address
			$this->load->model('account/address');

			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFullName()
			);

			$this->model_account_activity->addActivity('login', $activity_data);

			// Added strpos check to pass McAfee PCI compliance test (http://forum.mycncart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
				$this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
			} else {
				$this->response->redirect($this->url->link('account/account', '', 'SSL'));
			}
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_login'),
			'href' => $this->url->link('account/login', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_new_customer'] = $this->language->get('text_new_customer');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_register_account'] = $this->language->get('text_register_account');
		$data['text_returning_customer'] = $this->language->get('text_returning_customer');
		$data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
		$data['text_forgotten'] = $this->language->get('text_forgotten');

		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_password'] = $this->language->get('entry_password');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_login'] = $this->language->get('button_login');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('account/login', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

		// Added strpos check to pass McAfee PCI compliance test (http://forum.mycncart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
			$data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
			$data['redirect'] = $this->session->data['redirect'];

			unset($this->session->data['redirect']);
		} else {
			$data['redirect'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/login.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/login.tpl', $data));
		}
	}

	protected function validate() {
		// Check how many login attempts have been made.
		$login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);
				
		if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			$this->error['warning'] = $this->language->get('error_attempts');
		}

        $customer_info = false;
        if (preg_match('/^1[1-9]{1}[0-9]{9}$/i', $this->request->post['email'])) {
            // Check if customer has been approved.
            $customer_info = $this->model_account_customer->getCustomerByPhone($this->request->post['email']);
        } elseif ((utf8_strlen($this->request->post['email']) <= 96) && preg_match('/^[^\@]+@[^\@]+.[a-z]{2,15}$/i', $this->request->post['email'])) {
            // Check if customer has been approved.
            $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
        } else {
            $this->error['warning'] = $this->language->get('error_format');
        }

        if (!$customer_info) {
            $this->error['warning'] = $this->language->get('error_login');
        } elseif ($customer_info && !$customer_info['approved']) {
            $this->error['warning'] = $this->language->get('error_approved');
        }

		if (!$this->error) {
			if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
				$this->error['warning'] = $this->language->get('error_login');
			
				$this->model_account_customer->addLoginAttempt($this->request->post['email']);
			} else {
				$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
			}			
		}
		
		return !$this->error;
	}

    public function qq() {
        $this->load->model('account/customer');
        $this->load->language('account/login');

        if($_REQUEST['state'] == $_SESSION['state']) //csrf
        {
            $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
                . "client_id=" . $_SESSION["appid"]. "&redirect_uri=" . urlencode($_SESSION["callback"])
                . "&client_secret=" . $_SESSION["appkey"]. "&code=" . $_REQUEST["code"];

            $response = file_get_contents($token_url);
            if (strpos($response, "callback") !== false)
            {
                $lpos = strpos($response, "(");
                $rpos = strrpos($response, ")");
                $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
                $msg = json_decode($response);
                if (isset($msg->error))
                {
                    /*echo "<h3>error:</h3>" . $msg->error;
                    echo "<h3>msg  :</h3>" . $msg->error_description;
                    exit;*/
                    $this->error['warning'] = $msg->error . '<br/>' . $msg->error_description;
                    $this->index();
                    exit;
                }
            }

            $params = array();
            parse_str($response, $params);

            //debug
            //print_r($params);

            //set access token to session
            $_SESSION["access_token"] = $params["access_token"];

            $graph_url = "https://graph.qq.com/oauth2.0/me?access_token="
                . $_SESSION['access_token'];

            $str  = file_get_contents($graph_url);
            if (strpos($str, "callback") !== false)
            {
                $lpos = strpos($str, "(");
                $rpos = strrpos($str, ")");
                $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
            }

            $user = json_decode($str);
            if (isset($user->error))
            {
                /*echo "<h3>error:</h3>" . $user->error;
                echo "<h3>msg  :</h3>" . $user->error_description;
                exit;*/
                $this->error['warning'] = $user->error . '<br/>' . $user->error_description;
                $this->index();
                exit;
            }

            //debug
            //echo("Hello " . $user->openid);

            //set openid to session
            $_SESSION["openid"] = $user->openid;
            //echo "<p>";
            //echo "OpenId:".$user->openid;
            //echo "</p>";

            $_email = $user->openid . '@qq.com';
            // Check if customer has been approved.
            $customer_info = $this->model_account_customer->getCustomerByEmail($_email);

            if ($customer_info && !$customer_info['approved']) {
                $this->error['warning'] = $this->language->get('error_approved');
                $this->index();
                exit;
            } elseif ($customer_info) {
                if (!$this->customer->login($_email, '', true)) {
                    $this->error['warning'] = $this->language->get('error_login');

                    $this->model_account_customer->addLoginAttempt($_email);
                    $this->index();
                    exit;
                } else {
                    $this->model_account_customer->deleteLoginAttempts($_email);
                    // Default Addresses
                    $this->load->model('account/address');

                    if ($this->config->get('config_tax_customer') == 'payment') {
                        $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                    }

                    if ($this->config->get('config_tax_customer') == 'shipping') {
                        $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                    }

                    $this->event->trigger('post.customer.login');

                    $this->response->redirect($this->url->link('account/account', '', 'SSL'));
                }
            } else {
                $get_user_info = "https://graph.qq.com/user/get_user_info?"
                    . "access_token=" . $_SESSION['access_token']
                    . "&oauth_consumer_key=" . $_SESSION["appid"]
                    . "&openid=" . $_SESSION["openid"]
                    . "&format=json";

                $info = file_get_contents($get_user_info);
                $arr = json_decode($info, true);

                $rand_password = substr(md5(uniqid(rand(), true)), 0, 10);
                $user_data = array(
                    'fullname' => $arr["nickname"],
                    'email' => $_email,
                    'telephone' => '',
                    'password'  => $rand_password
                );

                $customer_id = $this->model_account_customer->addCustomer($user_data);

                // Clear any previous login attempts for unregistered accounts.
                $this->model_account_customer->deleteLoginAttempts($_email);

                $this->customer->login($_email, $rand_password);

                unset($this->session->data['guest']);

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $customer_id,
                    'name'        => $arr["nickname"]
                );

                $this->model_account_activity->addActivity('register', $activity_data);

                $this->response->redirect($this->url->link('account/account', '', 'SSL'));
            }
        }
        else
        {
            $this->error['warning'] = "The state does not match. You may be a victim of CSRF.";
            $this->index();
        }
    }
}