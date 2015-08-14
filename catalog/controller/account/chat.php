<?php
class ControllerAccountChat extends Controller {
	public function index() {
        $json = array();

        if (!$this->customer->isLogged()) {
            $json['error'] = 'please login first!';
        }

        if (empty($json)) {
            $json['message'] = array();
            $msgIds = array();
            $this->load->model('account/chat');
            $this->load->model('tool/image');
            $results = $this->model_account_chat->getMessages($this->customer->getId());
            foreach ($results as $result) {
                $avatar = $this->model_tool_image->resize('no_image.png', 50, 50);
                $result['custom_field'] = unserialize($result['custom_field']);
                if (isset($result['custom_field'][2]) && is_file(DIR_IMAGE . $result['custom_field'][2])) {
                    $avatar = $this->model_tool_image->resize($result['custom_field'][2], 50, 50);
                }
                $json['message'][] = array(
                    'id' => $result['from_id'],
                    'name' => $result['fullname'],
                    'avatar' => $avatar,
                    'text' => htmlspecialchars($result['text']),
                    'date' => date('Y-m-d H:i', strtotime($result['date_added']))
                );
                $msgIds[] = (int)$result['msg_id'];
            }

            if (!empty($msgIds)) {
                $this->model_account_chat->updateMessagesStatus($msgIds);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

	public function send() {
		$json = array();

        if (!$this->customer->isLogged()) {
            $json['error'] = 'please login first!';
        } elseif(!isset($this->request->post['to_id']) || !isset($this->request->post['text']) || empty($this->request->post['text'])) {
            $json['error'] = 'param error!';
        } elseif ($this->request->post['to_id'] == $this->customer->getId()) {
            $json['error'] = 'Can not send message to yourself!';
        } else {
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomer($this->request->post['to_id']);
            if (!$customer_info) {
                $json['error'] = 'shop customer not exist!';
            }
        }

        if (empty($json)) {
            $this->load->model('account/chat');
            $this->request->post['from_id'] = $this->customer->getId();
            $msg_id = $this->model_account_chat->addMessage($this->request->post);
            if ($msg_id > 0) {
                $json['success'] = 'send success!';
            } else {
                $json['error'] = 'send failed!';
            }
        }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}