<?php
class ControllerSellerFileManager extends Controller {
	public function index() {
        if (!$this->customer->isLogged() || !$this->customer->isSeller()) {
            die($this->language->get('error_permission'));
        }

        $this->load->language('seller/filemanager');

		if (isset($this->request->get['filter_name'])) {
			$filter_name = rtrim(str_replace(array('../', '..\\', '..', '*'), '', $this->request->get['filter_name']), '/');
		} else {
			$filter_name = null;
		}

        $basePath = $this->validateBasePath();

		// Make sure we have the correct directory
		if (isset($this->request->get['directory'])) {
			$directory = rtrim($basePath . '/' . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
		} else {
			$directory = $basePath;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['images'] = array();

		$this->load->model('tool/image');

		// Get directories
		$directories = glob($directory . '/' . $filter_name . '*', GLOB_ONLYDIR);

		if (!$directories) {
			$directories = array();
		}

		// Get files
		$files = glob($directory . '/' . $filter_name . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);

		if (!$files) {
			$files = array();
		}

		// Merge directories and files
		$images = array_merge($directories, $files);

		// Get total number of files and directories
		$image_total = count($images);

		// Split the array based on current page number and max number of items per page of 10
		$images = array_splice($images, ($page - 1) * 16, 16);

		foreach ($images as $image) {
			//$name = str_split(basename($image), 14);
            $name = str_split(preg_replace('/^.+[\\\\\\/]/', '', $image), 14);

			if (is_dir($image)) {
                //$name = array(preg_replace('/^.+[\\\\\\/]/', '', $image));
                $url = '';

				if (isset($this->request->get['target'])) {
					$url .= '&target=' . $this->request->get['target'];
				}

				if (isset($this->request->get['thumb'])) {
					$url .= '&thumb=' . $this->request->get['thumb'];
				}

                if (isset($this->request->get['pop'])) {
                    $url .= '&pop=' . $this->request->get['pop'];
                }

				$data['images'][] = array(
					'thumb' => '',
					'name'  => implode(' ', $name),
					'type'  => 'directory',
					'path'  => utf8_substr($image, utf8_strlen($basePath)),
					'href'  => $this->url->link('seller/filemanager', 'directory=' . urlencode(utf8_substr($image, utf8_strlen($basePath . '/'))) . $url, 'SSL')
				);
			} elseif (is_file($image)) {
                //$name = array(preg_replace('/^.+[\\\\\\/]/', '', $image));

				// Find which protocol to use to pass the full image link back
				if ($this->request->server['HTTPS']) {
					$server = HTTPS_SERVER;
				} else {
					$server = HTTP_SERVER;
				}

				$data['images'][] = array(
					'thumb' => $this->model_tool_image->resize(utf8_substr($image, utf8_strlen(DIR_IMAGE)), 100, 100),
					'name'  => implode(' ', $name),
					'type'  => 'image',
					'path'  => utf8_substr($image, utf8_strlen($basePath)),
                    'sub_path' => utf8_substr($image, utf8_strlen(DIR_IMAGE)),
					'href'  => '/image/' . utf8_substr($image, utf8_strlen(DIR_IMAGE))
				);
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->request->get['directory'])) {
            $data['heading_title'] .= ' - ' . html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8');
        }

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['entry_search'] = $this->language->get('entry_search');
		$data['entry_folder'] = $this->language->get('entry_folder');

		$data['button_parent'] = $this->language->get('button_parent');
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_folder'] = $this->language->get('button_folder');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_search'] = $this->language->get('button_search');

		//$data['token'] = $this->session->data['token'];

        if (isset($this->request->get['pop'])) {
            $data['pop'] = $this->request->get['pop'];
        } else {
            $data['pop'] = '';
        }

		if (isset($this->request->get['directory'])) {
			$data['directory'] = urlencode($this->request->get['directory']);
		} else {
			$data['directory'] = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$data['filter_name'] = '';
		}

		// Return the target ID for the file manager to set the value
		if (isset($this->request->get['target'])) {
			$data['target'] = $this->request->get['target'];
		} else {
			$data['target'] = '';
		}

		// Return the thumbnail for the file manager to show a thumbnail
		if (isset($this->request->get['thumb'])) {
			$data['thumb'] = $this->request->get['thumb'];
		} else {
			$data['thumb'] = '';
		}

		// Parent
		$url = '';

		if (isset($this->request->get['directory'])) {
			$pos = strrpos($this->request->get['directory'], '/');

			if ($pos) {
				$url .= '&directory=' . urlencode(substr($this->request->get['directory'], 0, $pos));
			}
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}

        if (isset($this->request->get['pop'])) {
            $url .= '&pop=' . $this->request->get['pop'];
        }

		$data['parent'] = $this->url->link('seller/filemanager', $url, 'SSL');

		// Refresh
		$url = '';

		if (isset($this->request->get['directory'])) {
			$url .= '&directory=' . urlencode($this->request->get['directory']);
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}

        if (isset($this->request->get['pop'])) {
            $url .= '&pop=' . $this->request->get['pop'];
        }

		$data['refresh'] = $this->url->link('seller/filemanager', $url, 'SSL');

		$url = '';

		if (isset($this->request->get['directory'])) {
			$url .= '&directory=' . urlencode(html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}

        if (isset($this->request->get['pop'])) {
            $url .= '&pop=' . $this->request->get['pop'];
        }

		$pagination = new Pagination();
		$pagination->total = $image_total;
		$pagination->page = $page;
		$pagination->limit = 16;
		$pagination->url = $this->url->link('seller/filemanager', $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

        $template = 'filemanager';
        if (isset($this->request->get['pop'])) {
            $template = 'filemanager_inner';
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/'.$template.'.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/'.$template.'.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/seller/'.$template.'.tpl', $data));
        }
	}

	public function upload() {
		$this->load->language('seller/filemanager');

		$json = array();

		// Check user has permission
		/*if (!$this->user->hasPermission('modify', 'seller/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}*/

        if (!$this->customer->isLogged() || !$this->customer->isSeller()) {
            $json['error'] = '999'.$this->language->get('error_permission');
        }

        $basePath = $this->validateBasePath();

        // Make sure we have the correct directory
		if (isset($this->request->get['directory'])) {
			$directory = rtrim($basePath . '/' . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
		} else {
			$directory = $basePath;
		}

		// Check its a directory
		if (!is_dir($directory)) {
			$json['error'] = '888'.$this->language->get('error_directory');
		}

		if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
				// Sanitize the filename
				//$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
                $filename = preg_replace('/^.+[\\\\\\/]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

                // Validate the filename length
				if ((utf8_strlen($filename) < 5) || (utf8_strlen($filename) > 255)) {
					$json['error'] = '222'.$this->language->get('error_filename');
				}

				// Allowed file extension types
				$allowed = array(
					'jpg',
					'jpeg',
					'gif',
					'png'
				);

				if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
					$json['error'] = '333'.$this->language->get('error_filetype');
				}

				// Allowed file mime types
				$allowed = array(
					'image/jpeg',
					'image/pjpeg',
					'image/png',
					'image/x-png',
                    'application/octet-stream',
					'image/gif'
				);

				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = '444'.$this->language->get('error_filetype');
				}

				// Check to see if any PHP files are trying to be uploaded
				$content = file_get_contents($this->request->files['file']['tmp_name']);

				if (preg_match('/\<\?php/i', $content)) {
					$json['error'] = '555'.$this->language->get('error_filetype');
				}

				// Return any upload error
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = '666'.$this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = '111'.$this->language->get('error_upload');
			}
		}

		if (!$json) {
			if (is_file($directory . '/' . $filename)) {
                $filename = time() . '_' . $filename;
            }
            move_uploaded_file($this->request->files['file']['tmp_name'], $directory . '/' . $filename);

			$json['success'] = $this->language->get('text_uploaded');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function folder() {
		$this->load->language('seller/filemanager');

		$json = array();

		// Check user has permission
		/*if (!$this->user->hasPermission('modify', 'seller/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}*/

        if (!$this->customer->isLogged() || !$this->customer->isSeller()) {
            $json['error'] = $this->language->get('error_permission');
        }

        $basePath = $this->validateBasePath();

        // Make sure we have the correct directory
		if (isset($this->request->get['directory'])) {
			$directory = rtrim($basePath . '/' . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
		} else {
			$directory = $basePath;
		}

		// Check its a directory
		if (!is_dir($directory)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Sanitize the folder name
			//$folder = str_replace(array('../', '..\\', '..'), '', basename(html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8')));

            $folder = preg_replace('/^.+[\\\\\\/]/', '', html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8'));
            $folder = str_replace(array('../', '..\\', '..'), '', $folder);

			// Validate the filename length
			if ((utf8_strlen($folder) < 2) || (utf8_strlen($folder) > 128)) {
				$json['error'] = $folder.'-'.$this->language->get('error_folder');
			}

			// Check if directory already exists or not
			if (is_dir($directory . '/' . $folder)) {
				$json['error'] = $folder.'-'.$this->language->get('error_exists');
			}
		}

		if (!$json) {
			mkdir($directory . '/' . $folder, 0777);

			$json['success'] = $this->language->get('text_directory');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->load->language('seller/filemanager');

		$json = array();

		// Check user has permission
		/*if (!$this->user->hasPermission('modify', 'seller/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}*/

        if (!$this->customer->isLogged() || !$this->customer->isSeller()) {
            $json['error'] = $this->language->get('error_permission');
        }

        $basePath = $this->validateBasePath();

        if (isset($this->request->post['path'])) {
			$paths = $this->request->post['path'];
		} else {
			$paths = array();
		}

		// Loop through each path to run validations
		foreach ($paths as $path) {
			$path = rtrim($basePath . str_replace(array('../', '..\\', '..'), '', $path), '/');

			// Check path exsists
			if ($path == $basePath) {
				$json['error'] = $this->language->get('error_delete');

				break;
			}
		}

		if (!$json) {
			// Loop through each path
			foreach ($paths as $path) {
				$path = rtrim($basePath . str_replace(array('../', '..\\', '..'), '', $path), '/');

				// If path is just a file delete it
				if (is_file($path)) {
					unlink($path);

				// If path is a directory beging deleting each file and sub folder
				} elseif (is_dir($path)) {
					$files = array();

					// Make path into an array
					$path = array($path . '*');

					// While the path array is still populated keep looping through
					while (count($path) != 0) {
						$next = array_shift($path);

						foreach (glob($next) as $file) {
							// If directory add to path array
							if (is_dir($file)) {
								$path[] = $file . '/*';
							}

							// Add the file to the files to be deleted array
							$files[] = $file;
						}
					}

					// Reverse sort the file array
					rsort($files);

					foreach ($files as $file) {
						// If file just delete
						if (is_file($file)) {
							unlink($file);

						// If directory use the remove directory function
						} elseif (is_dir($file)) {
							rmdir($file);
						}
					}
				}
			}

			$json['success'] = $this->language->get('text_delete');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function rename() {
        $this->load->language('seller/filemanager');

        $json = array();

        // Check user has permission
        /*if (!$this->user->hasPermission('modify', 'seller/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        }*/

        if (!$this->customer->isLogged() || !$this->customer->isSeller()) {
            $json['error'] = $this->language->get('error_permission');
        }

        $basePath = $this->validateBasePath();

        if (isset($this->request->post['folder']) && !empty($this->request->post['folder'])) {
            $path = $this->request->post['folder'];
            $path = rtrim($basePath . str_replace(array('../', '..\\', '..'), '', $path), '/');

            // Check path exsists
            if ($path == $basePath || !is_dir($path)) {
                $json['error'] = $this->language->get('error_directory');
            }
        } else {
            $json['error'] = $this->language->get('error_directory');
        }

        if (!$json) {
            // Sanitize the folder name
            //$folder = str_replace(array('../', '..\\', '..'), '', basename(html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8')));

            $folder = preg_replace('/^.+[\\\\\\/]/', '', html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));
            $folder = str_replace(array('../', '..\\', '..'), '', $folder);

            // Validate the filename length
            if ((utf8_strlen($folder) < 2) || (utf8_strlen($folder) > 128)) {
                $json['error'] = $folder.'-'.$this->language->get('error_folder');
            } else {
                $paths = explode('/', $path);
                array_pop($paths);
                $paths[] = $folder;
                $new_path = join('/', $paths);

                // Check if directory already exists or not
                if (is_dir($new_path)) {
                    $json['error'] = $folder.'-'.$this->language->get('error_exists');
                } else {
                    @rename($path, $new_path);
                    $json['success'] = $this->language->get('text_directory');
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateBasePath() {
        $directory = DIR_IMAGE . 'catalog/shop_image/' .md5($this->customer->getShopId());
        if (!is_dir($directory)) {
            mkdir($directory, 0777);
        }

        return $directory;
    }
}