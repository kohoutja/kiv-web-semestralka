<?php


class Users_management extends Controller
{
    public function defaultMethod()
    {
        $this->prepare_parts();
        $this->params["content"] = $this->buildUsersTable();
        $this->renderTemplate();
    }

    private function buildUsersTable()
    {
        $users = $this->model->getAllUsers();
        if (empty($users)) {
            return file_get_contents("view/html/no_users.html");
        }

        $usersParams = array("users" => $users, "roles" => $this->model->getUserRoles());
        return $this->twig->render("users_table.twig", $usersParams);
    }

    public function changeRole()
    {
        $email = $_POST["email"];
        $role = $_POST["role"];

        var_dump($_POST);
        $this->model->updateUserRole($email, $role);
    }
}