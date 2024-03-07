<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $position = $_POST['position'];
    $fullName = $_POST['fullName'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $experience = $_POST['experience'];
    
    // File upload handling
    $targetDir = "uploads/";
    $fileName = basename($_FILES["resume"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
    
    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $targetFilePath)) {
        // Email sending
        $to = "hr@utkalb2b.in";
        $subject = "Job Application for $position";
        $message = "Position: $position\n";
        $message .= "Full Name: $fullName\n";
        $message .= "Age: $age\n";
        $message .= "Sex: $sex\n";
        $message .= "Mobile Number: $mobile\n";
        $message .= "Email: $email\n";
        $message .= "Experience: $experience years\n";
        $headers = "From: $email" . "\r\n" .
                   "Reply-To: $email" . "\r\n" .
                   "Content-Type: text/plain; charset=UTF-8" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();
        
        // Attach resume
        $file = $targetFilePath;
        $content = file_get_contents($file);
        $content = chunk_split(base64_encode($content));
        $uid = md5(uniqid(time()));
        $name = basename($file);
        
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
        $headers .= "Content-Disposition: attachment; filename=\"".$fileName."\"\r\n";
        $headers .= "Content-Transfer-Encoding: base64\r\n";
        $headers .= $content."\r\n";
        
        // Send email
        if (mail($to, $subject, $message, $headers)) {
            echo "Your application has been submitted successfully.";
        } else {
            echo "Failed to submit your application. Please try again later.";
        }
    } else {
        echo "Failed to upload resume.";
    }
} else {
    echo "Access denied.";
}
?>
