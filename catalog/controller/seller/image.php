<?php
/**
 * Created by PhpStorm.
 * User: paul_dev
 * Date: 6/22/15
 * Time: 4:33 PM
 */

class ControllerSellerImage extends Controller
{
    private $error = array();

    public function index()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('seller/category', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        if (!$this->customer->isSeller()) {
            $this->session->data['redirect'] = $this->url->link('seller/category', '', 'SSL');

            $this->response->redirect($this->url->link('seller/shop/add', '', 'SSL'));
        }

        $data = array();

        $this->load->language('seller/filemanager');

        $this->document->setTitle($this->language->get('heading_title') . ' - ' . $this->language->get('seller_home'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('seller_home'),
            'href' => $this->url->link('seller/home', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('seller/image', '', 'SSL')
        );

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $data['content_url'] = $this->url->link('seller/filemanager', 'pop=1', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/image_list.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/image_list.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/seller/image_list.tpl', $data));
        }
    }
}