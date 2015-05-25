<?php

class database
{
    private static $instance = null;
    private static $conn;

    private function connect() 
    {
        if(!isset(self::$conn)){
            self::$conn = mysqli_connect(getenv('IP'), "jogordon", null, "c9");
            
            if(self::$conn->connect_errno > 0 || self::$conn === false){
                return mysqli_connect_error(); 
            }
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new database();
            self::$instance->connect();
        }
        return self::$instance;
    }
    
    public static function close() 
    {
        mysql_close($this->conn);
        echo "Connection closed";
    }
    
    public static function createUser($userName, $password)
    {
        $stmt = self::$conn->prepare("INSERT INTO user(userName, password) VALUES(?, ?)");
        $stmt->bind_param("ss", $userName, $password);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public static function deleteUser($userName)
    {
        $stmt = self::$conn->prepare("DELETE FROM user WHERE userName = ?");
        $stmt->bind_param("s", $userName);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public static function validate($userName, $password)
    {
        $stmt = self::$conn->prepare("SELECT * FROM user WHERE userName = ? AND password = ?");
        $stmt->bind_param("ss", $userName, $password);
        $stmt->execute();
        $success = $stmt->fetch();
        $stmt->close();
        return $success;
    }
    
    public static function findCustomer($firstName, $lastName)
    {
        $sql="SELECT * FROM customer";
        
        if($firstName || $lastName){
            $sql .= " WHERE ";
            if($firstName){
                $sql .= " firstName = '$firstName' ";
            }
            if($firstName && $lastName){
                $sql .= " AND ";
            }
            if($lastName){
                $sql .= " lastName = '$lastName' ";
            }
        }
        
        $result=mysqli_query(self::$conn,$sql);
        $data = array();
        if($result){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }
        return $data;
    }
    
    public static function editCustomer($firstName, $lastName, $street, $city, $state, 
                                          $zip, $homePhone, $cellPhone, $email, $customerID)
    {
        $stmt = self::$conn->prepare("UPDATE customer SET firstName = ?, lastName = ?, street = ?, city = ?, state = ?, zip = ?, 
                                      homePhone = ?, cellPhone = ?, email = ? WHERE customerID = ?");
    
        $stmt->bind_param("ssssssssss", $firstName, $lastName, $street, $city, $state, 
                                          $zip, $homePhone, $cellPhone, $email, $customerID);
                                          
        $success = $stmt->execute();
        $stmt->close();
        return $success;
        
    }
    
    
    public static function createBall(
        $customerID,    $date,
        $conv,          $FT,     $weight,
        $pin,           $layout, $surface,
        $bhp,           $size,   $depth, 
        $paph,          $papud,  $papclt,
        $thumb,         $fingers, 
        $LH,            $RH,     $BH, 
        $ctcleft,       $ctcright,
        $fsleft,        $fsright,
        $clerk,         $comments
    ){
        //mysqli_report(MYSQLI_REPORT_ALL);
        $stmt = self::$conn->prepare("Insert into ball(
        customerID, date,      conv,      FT,          weight,
        pin,        layout,    surface,   BHPosition,  BHSize, 
        BHDepth,    PAP_Horz,  PAP_Vert,  CLT,         thumb,
        fingers,    LDiameter, RDiameter, TDiameter,   LCC,
        RCC,        LFS,       RFS,       clerk,       comments)
        values
        (?,?,?,?,?,
         ?,?,?,?,?,
         ?,?,?,?,?,
         ?,?,?,?,?,
         ?,?,?,?,?
         )");
        $stmt->bind_param(
        "isssdssssssssssssssbbbbss",
        $customerID,    $date,   $conv,          $FT,     $weight,
        $pin,           $layout, $surface,       $bhp,    $size, 
        $depth,         $paph,   $papud,         $papclt, $thumb, 
        $fingers,       $LH,     $RH,            $BH,     $ctcleft,       
        $ctcright,      $fsleft, $fsright,       $clerk,  $comments
        );
        $success = $stmt->execute();
        $stmt->close();
        if($success)return 'success';else return "failed";
    }
    
    
    public static function createCustomer($firstName, $lastName, $street, $city, $state, 
                                          $zip, $homePhone, $cellPhone, $email)
    {
        $stmt = self::$conn->prepare("INSERT INTO customer(firstName, lastName, street, city, state, zip, 
                                      homePhone, cellPhone, email) values(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
        $stmt->bind_param("sssssssss", $firstName, $lastName, $street, $city, $state, 
                                          $zip, $homePhone, $cellPhone, $email);
        $success = $stmt->execute();
        $stmt->close();
        if($success){
            return mysqli_insert_id(self::$conn);
        } else {
            return 'failed';
        }
    }
    
    public static function getBalls($id)
    {
        $sql="SELECT * FROM ball WHERE customerID = '$id'";
        
        $result=mysqli_query(self::$conn,$sql);
        $data = array();
        if($result){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }
        return $data;
    }
    
    public static function deleteBall($id)
    {
        $stmt = self::$conn->prepare("DELETE FROM ball WHERE ballID = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>