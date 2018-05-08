<?php 
/*** begin the session ***/
session_start();

if(!isset($_SESSION['user_id']))
{
    $message = 'You must be logged in to access this page';
    header("Location: index.php");

}
else
{
    try
    {
        /*** connect to database ***/
        /*** mysql hostname ***/
        $mysql_hostname = 'localhost';

        /*** mysql username ***/
        $mysql_username = 'root';

        /*** mysql password ***/
        $mysql_password = '';

        /*** database name ***/
        $mysql_dbname = 'noor';


        /*** select the users name from the database ***/
        $dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password);
        /*** $message = a message saying we have connected ***/

        /*** set the error mode to excptions ***/
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*** prepare the insert ***/
        $stmt = $dbh->prepare("SELECT username FROM users 
        WHERE user_id = :user_id");

        /*** bind the parameters ***/
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** check for a result ***/
        $username = $stmt->fetchColumn();

        /*** get all posts ***/
        $stmt = $dbh->prepare("SELECT * FROM posts");

        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** check for a result ***/
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /*** if we have no something is wrong ***/
        if($username == false)
        {
            $message = 'Access Error';
        }
        else
        {
            $message = 'Welcome '.$username;
        }
    }
    catch (Exception $e)
    {
        /*** if we are here, something is wrong in the database ***/
        $message = 'We are unable to process your request. Please try again later"';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Connect-It</title>
    <meta charset="UTF-8">
    <meta name="description" content="Social media website">
    <meta name="keywords" content="friends,share,add,connect,chat">
    <!--viewport to make the website look good on all devices-->
    <meta name="viewport" content="width=device-width , initial-scale=1.0">
    <!--  stylesheets -->
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- fonts -->
  <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Pontano+Sans" rel="stylesheet">
</head>
<body>
<div class="grid-container">
<div class="grid-item">
<div id="nav">
  <div class="user-banner"><img src="https://i.pinimg.com/originals/eb/0a/56/eb0a56531fd15cc44ee72546c351b4bb.jpg"/>
    <h4><a href="#" style="color:white"><?php echo $username; ?></a>
      <div class="log-out"><a href="logout.php">Log Out?</a></div>
    </h4>
    
  </div>
  <ul>
    <li class="head">General</li>
    <li class="active"><a href="#"><i class=" fa fa-newspaper-o dashboard"></i><span>Discover</span></a></li>
    <li><a href="#"><i class="fa fa-envelope-o inbox"></i><span class="swatch light-grey">Inbox</span></a></li>
    <li><a href="#"><i class="fa fa-calendar-check-o schedule"></i><span>Schedule</span></a></li>
    <li><a href="#"><i class="fa fa-code projects"></i><span>Projects</span></a></li>
    <li><a href="#"><i class="fa fa-cogs settings"></i><span>Settings</span></a></li>
  </ul>
</div>
</div>
  <div class="search-bar">
       <form>
         <input class="search-input" type="search"  name="search" placeholder="Search">
         <button class="search__btn">Search</button>
       </form>  
  </div> 
  <form action="post.php" method="POST" name="form">
      <div class="post-field">
       <!--<input class="post" type="text" name="post" placeholder="What are you thinking"> -->
       <input type="hidden" name="username" value="<?php echo($username) ?>">
       <input class="post" type="text" name="post" id="post" placeholder="What are you thinking" />
       <button type="submit" class="post_btn">Post</button>
      </div>
  </form>
  <div class="post-field">
    <?php 
    foreach ($posts as $post) {
      echo "<h4>" . $post['username'] . "</h4>";
      echo "<p>". $post['post'] . "</p>";
      echo "===================================================";
    }
    ?>
  </div>

 </div>
</body>