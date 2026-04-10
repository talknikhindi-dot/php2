<?php
session_start();
$conn = new mysqli("localhost", "root", "", "talknik_db");

// डेटाबेस एरर चेक
if($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// लॉगिन लॉजिक
if(isset($_POST["login"])){ 
    if($_POST["user"]=="admin" && $_POST["pass"]=="talknik"){ $_SESSION["admin"]="ok"; } 
}
if(isset($_GET["logout"])){ session_destroy(); header("Location: index.php"); exit(); }

// --- DELETE LOGIC (डेटाबेस आणि फोल्डर दोन्हीमधून डिलीट होईल) ---
if(isset($_GET["delete_id"])){
    $id = $_GET["delete_id"];
    $res = $conn->query("SELECT profile_pic, video_file, music_file FROM users WHERE id=$id");
    $row = $res->fetch_assoc();
    if($row["profile_pic"] && file_exists("uploads/".$row["profile_pic"])) unlink("uploads/".$row["profile_pic"]);
    if($row["video_file"] && file_exists("uploads/".$row["video_file"])) unlink("uploads/".$row["video_file"]);
    if($row["music_file"] && file_exists("uploads/".$row["music_file"])) unlink("uploads/".$row["music_file"]);
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: index.php"); exit();
}

echo "<body style='background:#f4f7f6; color:#333; font-family:sans-serif; margin:0; padding:0;'>";

if(!isset($_SESSION["admin"])){
    echo "<div style='height:100vh; display:flex; align-items:center; justify-content:center;'>
    <div style='background:#fff; padding:40px; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.1); text-align:center; width:350px;'>
        <h2 style='color:#2c3e50; margin-bottom:30px;'>TALKNIK ADMIN</h2>
        <form method='POST'>
            <input type='text' name='user' placeholder='Username' required style='width:100%; padding:12px; margin:10px 0; border:1px solid #ddd; border-radius:5px;'><br>
            <input type='password' name='pass' placeholder='Password' required style='width:100%; padding:12px; margin:10px 0; border:1px solid #ddd; border-radius:5px;'><br><br>
            <button type='submit' name='login' style='width:100%; padding:12px; background:#2c3e50; color:#fff; border:none; border-radius:5px; cursor:pointer; font-weight:bold;'>LOGIN</button>
        </form>
    </div></div>";
    exit();
}

// --- SAVE LOGIC ---
if(isset($_POST["save"])) {
    if(!is_dir("uploads")){ mkdir("uploads"); }
    $name = $_POST["u_name"];
    $img_name = ""; $vid_name = ""; $mus_name = "";
    if(!empty($_FILES["u_img"]["name"])) { $img_name = time()."_".$_FILES["u_img"]["name"]; move_uploaded_file($_FILES["u_img"]["tmp_name"], "uploads/".$img_name); }
    if(!empty($_FILES["u_vid"]["name"])) { $vid_name = time()."_".$_FILES["u_vid"]["name"]; move_uploaded_file($_FILES["u_vid"]["tmp_name"], "uploads/".$vid_name); }
    if(!empty($_FILES["u_mus"]["name"])) { $mus_name = time()."_".$_FILES["u_mus"]["name"]; move_uploaded_file($_FILES["u_mus"]["tmp_name"], "uploads/".$mus_name); }
    $conn->query("INSERT INTO users (username, profile_pic, video_file, music_file) VALUES ('$name', '$img_name', '$vid_name', '$mus_name')");
    header("Location: index.php"); exit();
}

echo "<div style='background:#fff; padding:15px 50px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 10px rgba(0,0,0,0.05);'>
    <h2 style='color:#2c3e50; margin:0;'>TALKNIK <span style='color:#3498db;'>PREMIUM</span> MULTIMEDIA</h2>
    <a href='index.php?logout=1' style='color:#e74c3c; text-decoration:none; font-weight:bold;'>LOGOUT</a>
</div>";

echo "<div style='padding:40px;'>
    <div style='background:#fff; padding:30px; border-radius:10px; box-shadow:0 5px 20px rgba(0,0,0,0.05); margin-bottom:40px;'>
        <h3 style='margin-top:0;'>Add Media Content</h3>
        <form method='POST' enctype='multipart/form-data' style='display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px; align-items:end;'>
            <div><label>Title</label><br><input type='text' name='u_name' placeholder='Enter Name' required style='width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;'></div>
            <div><label>Photo</label><br><input type='file' name='u_img' accept='image/*'></div>
            <div><label>Video</label><br><input type='file' name='u_vid' accept='video/*'></div>
            <div><label>Music</label><br><input type='file' name='u_mus' accept='audio/*'></div>
            <button name='save' type='submit' style='padding:12px; background:#3498db; color:#fff; border:none; border-radius:5px; cursor:pointer; font-weight:bold;'>UPLOAD NOW</button>
        </form>
    </div>";

echo "<div style='background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 20px rgba(0,0,0,0.05);'>
    <table style='width:100%; border-collapse:collapse; text-align:left;'>
        <thead>
            <tr style='background:#f8f9fa; border-bottom:2px solid #eee;'>
                <th style='padding:15px;'>IMAGE</th>
                <th style='padding:15px;'>MEDIA PLAYERS</th>
                <th style='padding:15px;'>NAME</th>
                <th style='padding:15px;'>ACTION</th>
            </tr>
        </thead>
        <tbody>";
$res = $conn->query("SELECT * FROM users ORDER BY id DESC");
while($row = $res->fetch_assoc()){
    echo "<tr style='border-bottom:1px solid #eee;'>
        <td style='padding:15px;'><img src='uploads/".$row["profile_pic"]."' style='width:60px; height:60px; object-fit:cover; border-radius:8px;'></td>
        <td style='padding:15px;'>";
            if($row["video_file"]) echo "<video width='200' controls style='border-radius:5px;'><source src='uploads/".$row["video_file"]."'></video><br>";
            if($row["music_file"]) echo "<audio controls style='width:200px; margin-top:5px;'><source src='uploads/".$row["music_file"]."'></audio>";
    echo "</td>
        <td style='padding:15px; font-weight:bold;'>".$row["username"]."</td>
        <td style='padding:15px;'><a href='index.php?delete_id=".$row["id"]."' onclick=\"return confirm('Delete this record?')\" style='color:#e74c3c; text-decoration:none; font-weight:bold;'>[DELETE]</a></td>
    </tr>";
}
echo "</tbody></table></div></div></body>";
?>
