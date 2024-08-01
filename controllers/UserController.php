<?php
require_once 'models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    public function index() {
        $users = $this->userModel->getAllUsers();
        include 'views/admin/index.php';
    }

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        switch ($action) {
            case 'edit':
                if ($id) {
                    $this->edit($id);
                } else {
                    header('Location: index.php?module=user');
                }
                break;
            case 'delete':
                if ($id) {
                    $this->delete($id);
                } else {
                    header('Location: index.php?module=user');
                }
                break;
            default:
                $this->index();
                break;
        }
    }

    public function edit($id) {
        $module = 'user';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->updateUser($id)) {
                header('Location: index.php?module=user');
                exit;
            } else {
                $error = "Failed to update user";
                $user = $this->userModel->getUserById($id);
                include 'views/admin/edit.php';
            }
        } else {
            $user = $this->userModel->getUserById($id);
            include 'views/admin/edit.php';
        }
    }

    public function delete($id) {
        if ($this->userModel->deleteUser($id)) {
            header('Location: index.php?module=user');
            exit;
        }
    }
}
?>



<!--Yêu cầu: phân quyền
B1: Quản lý ds user: add, edit, delete ; --
B2: Phần quyền admin & user: admin thì được ql ds user, user thì ko đc
B3: Phân quyền từng hành động của user: add - edit - delete. Vd: user1 thì có quyền add sp, ko đc sửa và xóa; vd: user2 thì đc sửa và xóa, ko đc thêm.
B4: admin có thể tùy chỉnh quyền từng user.
-->
