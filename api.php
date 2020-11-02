<?php
$connection = mysqli_connect('localhost','root','','employees');

$request = $_SERVER['REQUEST_METHOD'];
$returnData = [];

switch($request)
{
    case "GET" : getUsers();
    break;
    case "POST" : postUsers();
    break;
    case "PUT" : putUsers();
    break;
    case "DELETE" : deleteUsers();
    break;
}



function getUsers()
{
    global $connection;
    if(@$_GET['id'])
    {
        @$id = $_GET['id'];
        $where = "WHERE id = '$id'";
    }else{
        $id = 0;
        $where = '';
    }
    $query = "SELECT * FROM users $where";
    $result = mysqli_query($connection,$query);
    while($row = mysqli_fetch_assoc($result))
    {
        $returnData[] = array('id'=>$row['id'],'first_name' => $row['first_name'],'last_name'=>$row['last_name'],'basic_salary'=>$row['basic_salary']);
    }
    if($result)
    {
    response($returnData);
    }else{
        $returnData = array('message' => 'User not found');
        response($returnData);
    }
}

function postUsers()
{
    global $connection;
    if($_GET['first_name'])
    {
        $query = "INSERT INTO users (first_name , last_name , basic_salary) VALUES ('{$_GET['first_name']}','{$_GET['last_name']}','{$_GET['basic_salary']}')";
        $result = mysqli_query($connection,$query);

        if($result)
        {
            $returnData[] = array('message' => 'User added');
            response($returnData);
        }else{
            $returnData[] = array('message' => 'User failed to add');
            response($returnData);
        }
    }
}

function putUsers()
{
    global $connection;
    parse_str(file_get_contents('php://input'),$_PUT);
    if(@$_PUT)
    {
        $query = "UPDATE users SET first_name = '{$_PUT['first_name']}' , last_name = '{$_PUT['last_name']}' , basic_salary = '{$_PUT['basic_salary']}' WHERE id = '{$_GET['id']}'";
        $result = mysqli_query($connection,$query);

        if($result)
        {
            $returnData[] = array('message' => 'User updated successfully');
            response($returnData);
        }else{
            $returnData[] = array('message' => 'User failed to updated');
            response($returnData);
        }
    }
}

function deleteUsers()
{
    global $connection;
    if(@$_GET['id'])
    {
        $query = "DELETE FROM users WHERE id = '{$_GET['id']}'";
        $result = mysqli_query($connection,$query);

        if($result)
        {
            $returnData[] = array('message' => 'User deleted');
            response($returnData);
        }else{
            $returnData[] = array('message' => 'User failed to delete');
            response($returnData);
        }
    }
}

function response($data)
{
    echo json_encode($data);
}

