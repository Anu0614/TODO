<?php
include 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_todo'])) {
    $todo_text = $_POST['todo_text'];

    $sql = "INSERT INTO todos (user_id, todo_text) VALUES ('$user_id', '$todo_text')";

    if ($conn->query($sql) === TRUE) {
        header("Location: task.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['delete_todo'])) {
    $todo_id = $_GET['delete_todo'];

    $sql = "DELETE FROM todos WHERE id='$todo_id' AND user_id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: task.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM todos WHERE user_id='$user_id'";
$result = $conn->query($sql);

if ($result === FALSE) {
    echo "Error fetching todos: " . $conn->error;
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color :#364766 ;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #b3adaf;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        form {
            margin-top: 10px;
        }

        input[type="text"],
        textarea {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #af4ca3;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #050305;
        }
       

        .task {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #364766;
            border-radius: 5px;
        }

        .task button {
            background-color: #364766;
        }
    </style>

    
</head>
<body>
    
    <div class="container">
    <div class="box">
  
        <h1>To-Do List:</h1><br>
        
        <form method="POST">
            <div class="input-group">
                <div class="input-field">
            <input type="text" name="todo_text" placeholder="New Task Name" required>
            <button type="submit" class="input-submit" name="add_todo">Add Todo</button>

</div>

            </div>
        </form>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li>{$row['todo_text']} <a href='task.php?delete_todo={$row['id']}'>Delete Me</a></li>";
                }
            } else {
                echo "<li>Empty todos</li>";
            }
            ?>
        </ul>
    
    </div>
    </div>
    </div>


    <script>
   $(document).ready(function() {
  
    // Delete Todo
    $(document).on('click', '.deleteTodo', function(e) {
    e.preventDefault();
    var todoId = $(this).data('id');

    });

    $(document).ready(function(){
    $("#addTodoButton").click(function(){
        // Get the todo text from input field
        var todoText = $("#todoInput").val();

        // Make AJAX request
        $.ajax({
            type: "POST",
            url: "task.php", // PHP script to handle the AJAX request
            data: {
                todo: todoText
            },
            success: function(response){
                // Handle success response
                console.log("Todo added successfully!");
                // Add the new todo to the list
                $("#todoList").append("<li class='todoItem'>" + todoText + "</li>");
                // Clear the input field
                $("#todoInput").val("");
            },
            error: function(xhr, status, error){
                // Handle error
                console.error("Error adding todo:", error);
            }
        });
    });
});
    
       
        });
    
    </script>
</body>
</html>
