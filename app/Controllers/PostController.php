<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PostModel;
use Config\Validation;
use PHPUnit\TextUI\XmlConfiguration\Validator;

class PostController extends BaseController {
    public function index() {
        return view('index');
    }
    

    // handle add new post ajax request
    public function add() {
        $file = $this->request->getFile('file');
        $fileName = $file->getRandomName();

        $data = [
            'title' => $this->request->getPost('title'),
            'category' => $this->request->getPost('category'),
            'body' => $this->request->getPost('body'),
            'image' => $fileName,
            'created_at' => date('Y-m-d H:i:s')
        ];

        
        $validation =[
            'image' => 'uploaded[file]|max_size[file,1024]|is_image[file]|mime_in[file,image/jpg,image/jpeg,image/png]',
        ];
        if (! $this->validateData(($data ), $validation)) {
            return view('add');
        } 
        $file->move('uploads/avatar', $fileName);
        $postModel = new PostModel();
        $postModel->save($data);
        return $this->response->setJSON([
            'error' => false,
            'message' => 'Successfully added new post!'
        ]); 
        
    }
    
    // handle fetch all posts ajax request
    public function fetch() {
        $postModel = new PostModel();
        $posts = $postModel->findAll();
    
        $data = [
            'posts' => $posts
        ];
    
        $response = view('fetch_posts', $data);
    
        return $this->response->setJSON([
            'error' => false,
            'message' => $response
        ]);
    }
    



    // handle edit post ajax request
    public function edit($id = null) {
        $postModel = new PostModel();
        $post = $postModel->find($id);
        return $this->response->setJSON([
            'error' => false,
            'message' => $post
        ]);
    }

    // handle update post ajax request
    public function update() {
        $id = $this->request->getPost('id');
        $file = $this->request->getFile('file');
        $fileName = $file->getFilename();

        if ($fileName != '') {
            $fileName = $file->getRandomName();
            $file->move('uploads/avatar', $fileName);
            if ($this->request->getPost('old_image') != '') {
                unlink('uploads/avatar/' . $this->request->getPost('old_image'));
            }
        } else {
            $fileName = $this->request->getPost('old_image');
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'category' => $this->request->getPost('category'),
            'body' => $this->request->getPost('body'),
            'image' => $fileName,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $postModel = new PostModel();
        $postModel->update($id, $data);
        return $this->response->setJSON([
            'error' => false,
            'message' => 'Successfully updated post!'
        ]);
    }

    // handle delete post ajax request
    public function delete($id = null) {
        $postModel = new PostModel();
        $post = $postModel->find($id);
        $postModel->delete($id);
        unlink('uploads/avatar/' . $post['image']);
        return $this->response->setJSON([
            'error' => false,
            'message' => 'Successfully deleted post!'
        ]);
    }

    // handle fetch post detail ajax request
    public function detail($id = null) {
        $postModel = new PostModel();
        $post = $postModel->find($id);
        return $this->response->setJSON([
            'error' => false,
            'message' => $post
        ]);
    }
}